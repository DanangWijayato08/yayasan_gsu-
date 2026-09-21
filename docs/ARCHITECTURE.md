# Arsitektur Sistem - Website Yayasan Gerakan Sedekah Umat (GSU)

Dokumen ini mendokumentasikan cetak biru arsitektur teknis, lapisan sistem (*system layers*), alur data, model keamanan, dan pola integrasi pada aplikasi full-stack **Website Yayasan Gerakan Sedekah Umat (GSU)**.

---

## 1. Diagram Arsitektur Tingkat Tinggi

```text
┌─────────────────────────────────────────────────────────────────────────┐
│                           LAPISAN PENGGUNA                              │
│  ┌───────────────────────────────┐     ┌─────────────────────────────┐  │
│  │     Pengunjung Publik         │     │     Administrator CMS       │  │
│  │  (Donasi, Program, Kontak)    │     │  (Manajemen Konten & Data)  │  │
│  └───────────────┬───────────────┘     └──────────────┬──────────────┘  │
└──────────────────┼────────────────────────────────────┼─────────────────┘
                   │                                    │
                   ▼                                    ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                      LAPISAN FRONTEND (VUE 3 + VITE)                    │
│  ┌───────────────────────────────────────────────────────────────────┐  │
│  │ Komponen Antarmuka:                                               │  │
│  │ - Tipografi Editorial: Newsreader (Heading) + Plus Jakarta Sans   │  │
│  │ - State Management: Pinia Store (auth store)                      │  │
│  │ - Router & Guards: Vue Router (Role Admin Guard, Smart Redirect)  │  │
│  │ - HTTP Client: Axios (Auto Token Bearer + Global 401 Interceptor) │  │
│  └───────────────────────────────────┬───────────────────────────────┘  │
└──────────────────────────────────────┼──────────────────────────────────┘
                                       │ Request API (JSON / Multipart)
                                       ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                    LAPISAN KEAMANAN & GATEWAY (MIDDLEWARE)              │
│  ┌───────────────────────────────────────────────────────────────────┐  │
│  │ 1. HandleCors : Mengizinkan request dari domain frontend resmi    │  │
│  │ 2. Throttle   : - 'login': 5x/menit per email & IP (Anti Brute)   │  │
│  │                 - 'contact-submission': 6x/menit per IP           │  │
│  │                 - 'api': 60x/menit per request umum               │  │
│  │ 3. Sanctum    : Autentikasi Bearer Token (Masa aktif 24 jam)      │  │
│  │ 4. Admin Role : EnsureUserIsAdmin (Blokir non-admin: 403)         │  │
│  └───────────────────────────────────┬───────────────────────────────┘  │
└──────────────────────────────────────┼──────────────────────────────────┘
                                       │ Request Tervalidasi
                                       ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                   LAPISAN LOGIKA BISNIS & API (LARAVEL 11)              │
│  ┌───────────────────────────────┐     ┌─────────────────────────────┐  │
│  │   Public Controllers          │     │   Admin CMS Controllers     │  │
│  │   - ContentController         │     │   - DashboardController     │  │
│  │   - ProgramController         │     │   - SingletonController     │  │
│  │   - GalleryController         │     │   - HomepageSectionContr.   │  │
│  │   - ContactController         │     │   - UploadController        │  │
│  └───────────────┬───────────────┘     └──────────────┬──────────────┘  │
│                  │                                    │                 │
│                  ▼                                    ▼                 │
│  ┌───────────────────────────────────────────────────────────────────┐  │
│  │ Services Layer:                                                   │  │
│  │ - PublicContentCache : Cache performa tinggi (serialisasi murni)  │  │
│  │ - SupabaseStorageService: Upload Supabase dengan Local Fallback   │  │
│  └───────────────────────────────────┬───────────────────────────────┘  │
└──────────────────────────────────────┼──────────────────────────────────┘
                                       │
                   ┌───────────────────┴───────────────────┐
                   ▼                                       ▼
┌──────────────────────────────────────┐ ┌────────────────────────────────┐
│      DATABASE (POSTGRESQL 16)        │ │      STORAGE PENYIMPANAN       │
│  - Docker Container `gsu_postgres`   │ │  - Disk Lokal: storage/public  │
│  - Port 5432 (Database: gsu_local)   │ │  - Cloud: Supabase Storage     │
│  - Web Adminer Client: Port 8080     │ │    (Bucket: public-assets)     │
└──────────────────────────────────────┘ └────────────────────────────────┘
```

---

## 2. Rincian Lapisan Sistem

### A. Lapisan Frontend (Presentation Layer)
- **Framework**: Vue 3 (Composition API dengan `<script setup>`).
- **Bundler & Build Tool**: Vite 6.
- **Sistem Desain & Tipografi**:
  - *Heading*: **Newsreader** (Google Fonts editorial serif untuk wibawa dan sentuhan humanis).
  - *Body & Form*: **Plus Jakarta Sans** (bersih, tegas, dan modern).
  - *Palet Warna*: Deep Forest Emerald (`#0F8A5F`, `#0C5A3E`), Warm Ivory (`#FBF9F5`), Ochre Gold Accent (`#C27803`).
- **Arsitektur State**:
  - `stores/auth.js`: Mengelola token JWT Sanctum, status autentikasi, dan identitas user yang sedang aktif.
- **Navigasi & Proteksi Rute**:
  - `router/index.js`: Dilengkapi `beforeEach` navigation guard yang memverifikasi keberadaan token, menyegarkan identitas user, dan memastikan hak akses role `admin` sebelum merender halaman manajemen CMS.
- **Resiliensi Jaringan**:
  - `services/api.js`: Interceptor Axios otomatis menyisipkan header `Authorization: Bearer <token>` pada setiap request terautentikasi dan secara cerdas menangani status `401 Unauthorized` dengan membersihkan storage lokal dan mengarahkan ke halaman login.

---

### B. Lapisan Keamanan & Middleware (Security Layer)
1. **Otorisasi Role Administrator (`EnsureUserIsAdmin`)**:
   - Terpasang pada seluruh rute di bawah prefiks `/api/admin/*`.
   - Menguji apakah `Auth::user()->role === 'admin'`. Jika bukan admin, melempar respons `403 Forbidden` terformat JSON.
2. **Anti Brute-Force & Rate Limiter (`throttle`)**:
   - `throttle:login`: Membatasi maksimal 5 kali kegagalan login per menit per kombinasi email & IP address.
   - `throttle:contact-submission`: Membatasi maksimal 6 kali pengiriman pesan per menit per IP untuk menangkal spam bot.
   - `throttle:api`: Membatasi request umum maksimal 60 request per menit.
3. **Masa Berlaku Token Sanctum**:
   - Diatur sebesar **1440 menit (24 jam)** secara terpusat melalui `SANCTUM_EXPIRATION`.
   - Logout hanya menghapus `currentAccessToken()` untuk menjaga keamanan multi-perangkat.
4. **Sanitasi Data Masukan**:
   - Penghapusan tag HTML (`strip_tags`) pada data masukan publik guna mencegah injeksi skrip berbahaya (*XSS*).

---

### C. Lapisan Backend & Logika Bisnis (Backend Layer)
- **Framework**: Laravel 11.
- **Pola Kontroler**:
  - *Public Controllers*: Mengembalikan data beranda, program, galeri, dan pengaturan situs publik dengan akselerasi cache.
  - *Admin Controllers*: Mengelola operasi CRUD data master dan CMS.
  - *SingletonController*: Mengelola entitas tunggal (`site-settings`, `about`, `donation-settings`, `contact-settings`, `footer-settings`) dengan validasi skema ketat sebelum penyimpanan.
- **Strategi Caching (`PublicContentCache`)**:
  - Menggunakan teknik serialisasi array murni (`->toArray()`) saat disimpan ke database cache, mengeliminasi risiko bug *PHP Incomplete Class*.
- **Abstraksi Penyimpanan Berkas (`SupabaseStorageService`)**:
  - Mendeteksi ketersediaan API key Supabase secara otomatis.
  - Jika Supabase belum dikonfigurasi pada lingkungan lokal, sistem beralih otomatis (*failover*) ke penyimpanan disk lokal publik (`storage/app/public/...`) sehingga pengembang dapat bekerja secara *offline*.

---

### D. Lapisan Database & Data Models (Data Layer)
Aplikasi menggunakan **PostgreSQL 16** dengan struktur skema terkelola:

1. **`users`**: Data kredensial pengguna dan perannya (`role`: `admin`).
2. **`site_settings`**: Konfigurasi global nama web, logo, favicon, warna tema, dan status pemeliharaan (*maintenance mode*).
3. **`homepage_sections`**: Konten dinamis seksi beranda (*hero banner, call-to-action, statistik*).
4. **`about_pages`**: Profil lengkap, visi, misi, nilai luhur, dan legalitas resmi yayasan.
5. **`programs`**: Daftar program donasi, target dana, dana terkumpul, penerima manfaat, dan status unggulan.
6. **`galleries`**: Dokumentasi visual kegiatan lapangan dan tanggal kegiatan.
7. **`donation_settings`**: Rekening bank resmi, gambar QRIS, nomor WhatsApp konfirmasi, dan nominal donasi rekomendasi.
8. **`contacts`**: Riwayat pesan masuk dari formulir kontak publik beserta status (`unread`, `read`, `archived`).
9. **`contact_settings`**: Alamat kantor, nomor kontak resmi, media sosial, dan sematan Google Maps.
10. **`footer_settings`**: Teks hak cipta (*copyright*), tautan cepat, dan tautan sosial media.
11. **`personal_access_tokens`**: Token autentikasi API berbasis Laravel Sanctum.

---
*Dokumentasi arsitektur ini disusun sebagai acuan standar rekayasa perangkat lunak Yayasan GSU.*
