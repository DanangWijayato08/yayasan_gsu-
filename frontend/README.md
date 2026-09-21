# Frontend SPA - Yayasan Gerakan Sedekah Umat (GSU)

Aplikasi antarmuka web modern (*Single Page Application* / SPA) untuk situs publik dan Panel Manajemen Konten (CMS) Yayasan Gerakan Sedekah Umat, dibangun menggunakan **Vue 3**, **Vite**, dan **CSS Native Token** berestetika editorial institusional terpercaya.

---

## 1. Stack Teknologi

- **Framework**: Vue 3 (Composition API dengan `<script setup>`)
- **Build Tool**: Vite 6
- **Routing**: Vue Router 4 (dilengkapi Navigation Guard dan Role Verification)
- **State Management**: Pinia
- **HTTP Client**: Axios (dilengkapi Token Injector dan 401 Interceptor)
- **Ikon**: Font Awesome 6 (via CDN)
- **Gaya Desain**: CSS Murni (Native Tokens di `main.css`, tanpa framework Tailwind/Bootstrap guna menjamin efisiensi performa dan desain orisinal)

---

## 2. Tipografi & Desain Editorial

Sistem desain menggunakan pendekatan **Modern Editorial Philanthropy**:
- **Heading**: **Newsreader** (Google Fonts) — Tipografi serif hangat, tajam, dan berwibawa khas jurnalisme kemanusiaan.
- **Body & Form**: **Plus Jakarta Sans** (Google Fonts) — Sans-serif bersih, tegas, dan mudah dibaca pada layar perangkat bergerak.
- **Palet Warna Utama**:
  - `Primary Trust`: Deep Forest Emerald (`#0F8A5F`, `#0C5A3E`)
  - `Warm Accent`: Ochre Gold (`#C27803`)
  - `Surface`: Warm Paper Ivory (`#FBF9F5`, `#F3EFE6`)
  - `Border`: Subtle Neutral (`#E6E0D4`)

---

## 3. Cara Menjalankan Frontend

### Menjalankan Server Pengembangan (Development)
```powershell
cd frontend
npm install
npm run dev
```
Situs web akan aktif di: **[http://localhost:5173](http://localhost:5173)**.

### Membangun Berkas Rilis Produksi (Production Build)
```powershell
npm run build
```
Hasil kompilasi siap saji akan dibuat di folder `dist/`.

---

## 4. Konfigurasi Lingkungan (`frontend/.env`)

```env
# Alamat URL Backend API Laravel
VITE_API_URL=http://127.0.0.1:8000/api
```

---

## 5. Struktur Direktori

```text
frontend/src/
├── assets/
│   └── main.css         # Token desain, variabel warna, tipografi, dan utility
├── components/
│   ├── admin/
│   │   └── AdminShell.vue # Layout shell navigasi dan sidebar CMS Admin
│   ├── common/
│   │   ├── Counter.vue    # Animasi perhitungan angka statistik
│   │   ├── Footer.vue     # Footer situs dengan legalitas yayasan
│   │   ├── Navbar.vue     # Navigasi utama dengan border presisi
│   │   ├── ProgressBar.vue# Bar progres pengumpulan dana
│   │   ├── Stats.vue      # Grounded impact ribbon bar
│   │   └── Testimonial.vue# Kutipan testimoni donatur otentik
│   └── sections/
│       ├── About.vue      # Seksi visi, misi, dan nilai yayasan
│       ├── Contact.vue    # Formulir kontak dan peta lokasi
│       ├── DonationSection.vue # Formulir donasi WhatsApp terformat
│       ├── Gallery.vue    # Galeri dokumentasi kegiatan
│       ├── Hero.vue       # Hero berbingkai editorial
│       ├── ProgramCard.vue# Kartu program donasi
│       └── ProgramSection.vue # Grid program unggulan
├── composables/
│   └── useScrollReveal.js # Directive animasi scroll lembut
├── router/
│   └── index.js           # Rute publik & proteksi rute admin (RBAC)
├── services/
│   ├── api.js             # Konfigurasi Axios + 401 response interceptor
│   ├── adminService.js    # API calls untuk modul CMS admin
│   ├── authService.js     # API calls login, logout, me
│   ├── publicService.js   # API calls beranda, program, donasi, galeri
│   └── uploadService.js   # API calls upload berkas multipart
├── stores/
│   └── auth.js            # Pinia store untuk state user dan token login
├── utils/
│   └── constants.js       # Konstanta rute dan delay animasi
├── views/
│   ├── admin/             # Tampilan panel admin
│   │   ├── CmsView.vue    # Form editor dinamis seluruh modul CMS
│   │   └── DashboardView.vue # Tampilan ringkasan statistik
│   ├── DonasiView.vue     # Halaman panduan & rekening donasi
│   ├── GaleriView.vue     # Halaman dokumentasi galeri
│   ├── HomeView.vue       # Halaman beranda utama
│   ├── KontakView.vue     # Halaman informasi kontak & pesan
│   ├── LoginPage.vue      # Halaman masuk administrator
│   ├── ProgramDetailView.vue # Detail lengkap program donasi
│   ├── ProgramView.vue    # Daftar seluruh program donasi
│   └── TentangView.vue    # Halaman profil & legalitas yayasan
├── App.vue                # Root wrapper component
└── main.js                # Titik masuk aplikasi
```

---

## 6. Fitur Unggulan Antarmuka

1. **Formulir Donasi Dinamis**: Donatur dapat memilih nominal cepat atau memasukkan nominal kustom, memilih metode bayar (QRIS, Transfer, E-Wallet), dan langsung membuka chat konfirmasi WhatsApp resmi dengan pesan yang sudah terisi otomatis.
2. **Navigation Guard Cerdas**: Memeriksa keberadaan token dan hak akses administrator secara otomatis saat mengakses halaman `/admin/*`. Jika token habis masa berlakunya, Axios interceptor secara mulus mengarahkan pengguna kembali ke `/admin/login`.
3. **Desain Ramah Semua Perangkat**: Responsif penuh mulai dari ponsel berlayar kecil hingga monitor desktop lebar.
