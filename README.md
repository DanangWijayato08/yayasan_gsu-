# Website Resmi Yayasan Gerakan Sedekah Umat (GSU)

Platform digital resmi Yayasan Gerakan Sedekah Umat (GSU) yang mencakup portal publik kemanusiaan dan sistem manajemen konten (CMS Admin) berbasis arsitektur modern berstandar industri.

---

## Daftar Isi

- [Ikhtisar Proyek](#ikhtisar-proyek)
- [Teknologi Utama](#teknologi-utama)
- [Struktur Direktori](#struktur-direktori)
- [Panduan Mulai Cepat](#panduan-mulai-cepat)
- [Konfigurasi Lingkungan](#konfigurasi-lingkungan-env)
- [Keamanan & Fitur Utama](#keamanan--fitur-utama)
- [Akun Administrator Bawaan](#akun-administrator-bawaan)
- [Dokumentasi Lengkap](#dokumentasi-lengkap)

---

## Ikhtisar Proyek

Aplikasi ini dibangun untuk menjamin transparansi, akuntabilitas, dan kemudahan bagi para donatur serta pengurus yayasan:

- **Situs Publik**: Menampilkan profil yayasan, program sedekah/wakaf/infaq unggulan, transparansi dana & penerima manfaat, galeri dokumentasi kegiatan, serta integrasi konfirmasi donasi otomatis via WhatsApp.
- **Admin CMS**: Dashboard terproteksi untuk mengelola teks dan visual beranda, program donasi, galeri, pesan kontak masuk, rekening bank & QRIS, serta pengaturan SEO situs.

---

## Teknologi Utama

| Lapisan | Teknologi | Deskripsi |
| :--- | :--- | :--- |
| **Frontend** | Vue 3, Vite, Pinia, Vue Router, Axios | Antarmuka SPA modern dengan tipografi editorial Newsreader dan Plus Jakarta Sans. |
| **Backend** | Laravel 11, Laravel Sanctum, PHP 8.3 | API RESTful terstruktur dengan otorisasi berbasis peran, pembatasan laju, dan sanitasi data. |
| **Database** | PostgreSQL 16 (via Docker / Supabase) | Penyimpanan data relasional dengan pencarian teks yang tidak peka huruf besar-kecil. |
| **Penyimpanan Gambar** | Penyimpanan Lokal / Supabase Storage | Upload gambar otomatis tersimpan di penyimpanan lokal publik. |
| **Manajemen Database** | Adminer 4.8 (via Docker) | Antarmuka web untuk inspeksi database PostgreSQL. |

---

## Struktur Direktori

```text
gsu-website/
├── backend/                    # Aplikasi API Laravel 11
├── frontend/                   # Aplikasi Web SPA Vue 3 + Vite
├── docs/                       # Dokumentasi teknis lengkap
├── docker-compose.yml          # Definisi kontainer
├── CHANGELOG.md                # Catatan rilis
└── README.md                   # Dokumentasi utama
```

---

## Panduan Mulai Cepat

### 1. Menjalankan Database (Docker)

```powershell
docker-compose up -d
```

- PostgreSQL: `localhost:5432`
- Adminer: [http://localhost:8080](http://localhost:8080)

### 2. Menjalankan Backend (Laravel)

```powershell
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan serve
```

Backend: [http://127.0.0.1:8000](http://127.0.0.1:8000)

### 3. Menjalankan Frontend (Vue 3)

```powershell
cd frontend
npm install
npm run dev
```

Frontend: [http://localhost:5173](http://localhost:5173)

---

## Konfigurasi Lingkungan (.env)

### Backend

```env
APP_NAME="Yayasan Gerakan Sedekah Umat"
APP_ENV=local
APP_DEBUG=false
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_DATABASE=gsu_local
SANCTUM_EXPIRATION=480
```

### Frontend

```env
VITE_API_URL=http://127.0.0.1:8000/api
```

---

## Keamanan & Fitur Utama

1. **Kontrol Akses Berbasis Peran**: Titik akhir admin dilindungi middleware role:admin
2. **Pembatasan Laju**: Login 5x/menit, kontak 6x/menit
3. **Resiliensi Token**: Axios mendeteksi sesi kedaluwarsa otomatis
4. **Penyimpanan Gambar**: Fallback ke penyimpanan lokal publik
5. **Pesan WhatsApp**: Konfirmasi donasi dinamis

---

## Akun Administrator Bawaan

```
URL   : http://localhost:5173/admin/login
Email : admin@sedekahumat.org
Kata Sandi: admin123456
```

⚠️ Perbarui kata sandi di produksi!

---

## Dokumentasi Lengkap

- [Panduan Setup](docs/PANDUAN_SETUP.md)
- [Arsitektur Sistem](docs/ARSITEKTUR_SISTEM.md)
- [Panduan Development](docs/PANDUAN_DEVELOPMENT.md)
- [Keamanan](docs/KEAMANAN.md)
- [API Reference](docs/DOKUMENTASI_API.md)

---

*Dikelola dengan penuh amanah oleh Tim Pengembang Yayasan Gerakan Sedekah Umat.*
