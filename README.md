# Website Resmi Yayasan Gerakan Sedekah Umat (GSU)

Platform digital resmi Yayasan Gerakan Sedekah Umat (GSU) yang mencakup portal publik kemanusiaan dan sistem manajemen konten (*Content Management System* / CMS Admin) berbasis arsitektur modern berstandar industri.

---

## Daftar Isi
- [Ikhtisar Proyek](#ikhtisar-proyek)
- [Teknologi Utama](#teknologi-utama)
- [Struktur Direktori](#struktur-direktori)
- [Panduan Memulai Cepat (Quick Start)](#panduan-memulai-cepat-quick-start)
- [Konfigurasi Lingkungan (.env)](#konfigurasi-lingkungan-env)
- [Keamanan & Fitur Utama](#keamanan--fitur-utama)
- [Akun Administrator Default](#akun-administrator-default)
- [Dokumentasi Lengkap](#dokumentasi-lengkap)

---

## Ikhtisar Proyek
Aplikasi ini dibangun untuk menjamin transparansi, akuntabilitas, dan kemudahan bagi para donatur serta pengurus yayasan:
- **Situs Publik**: Menampilkan profil yayasan, program sedekah/wakaf/infaq unggulan, transparansi dana & penerima manfaat, galeri dokumentasi kegiatan, serta integrasi konfirmasi donasi otomatis via WhatsApp.
- **Admin CMS**: Dashboard terproteksi untuk mengelola teks dan visual beranda (*hero & stats*), program donasi, galeri, pesan kontak masuk, rekening bank & QRIS, serta pengaturan SEO situs.

---

## Teknologi Utama

| Lapisan | Teknologi | Deskripsi |
| :--- | :--- | :--- |
| **Frontend** | Vue 3, Vite, Pinia, Vue Router, Axios | Antarmuka SPA modern dengan tipografi editorial *Newsreader* dan *Plus Jakarta Sans*. |
| **Backend** | Laravel 11, Laravel Sanctum, PHP 8.3 | RESTful API terstruktur dengan otorisasi berbasis peran (*RBAC*), rate limiting, dan sanitasi data. |
| **Database** | PostgreSQL 16 (via Docker / Supabase) | Penyimpanan data relasional yang andal dengan dukungan pencarian teks *case-insensitive* (`ILIKE`). |
| **Penyimpanan Gambar** | Local Storage Fallback / Supabase Storage | Upload gambar otomatis tersimpan di storage lokal publik jika kredensial cloud belum diisi. |
| **Manajemen DB** | Adminer 4.8 (via Docker) | Web interface ringan untuk inspeksi dan administrasi database PostgreSQL. |

---

## Struktur Direktori

```text
gsu-website/
├── backend/            # Aplikasi API Laravel 11
│   ├── app/            # Controllers, Models, Middleware, Services
│   ├── config/         # Konfigurasi aplikasi, auth, cors, dan sanctum
│   ├── database/       # Migrasi tabel dan seeder data awal
│   ├── routes/         # Definisi rute API v1 (publik & admin)
│   └── tests/          # Unit & Feature Test Suite (PHPUnit)
├── frontend/           # Aplikasi Web SPA Vue 3 + Vite
│   ├── public/         # Aset statis publik (logo, favicon)
│   └── src/
│       ├── assets/     # Style CSS global dan token desain
│       ├── components/ # Komponen UI common, sections, dan admin shell
│       ├── router/     # Rute Vue Router dan navigation guard
│       ├── services/   # Axios client dan API services
│       ├── stores/     # State management autentikasi Pinia
│       └── views/      # Tampilan halaman publik dan CMS admin
├── docs/               # Dokumentasi teknis lengkap
│   ├── ARCHITECTURE.md # Arsitektur sistem & diagram data
│   ├── DEVELOPMENT.md  # Panduan alur pengembangan & konvensi kode
│   └── SETUP.md        # Panduan instalasi lokal dari nol
├── docker-compose.yml  # Definisi kontainer PostgreSQL & Adminer
├── CHANGELOG.md        # Catatan riwayat rilis proyek
└── README.md           # Dokumentasi utama proyek
```

---

## Panduan Memulai Cepat (Quick Start)

### 1. Menjalankan Database (Docker)
Pastikan Docker Desktop aktif di komputer Anda:
```powershell
docker-compose up -d
```
- PostgreSQL berjalan di port `5432` (`localhost:5432`).
- Web Database Manager (Adminer) dapat diakses di: [http://localhost:8080](http://localhost:8080) (System: PostgreSQL, Server: `db`, Username: `postgres`, Password: `your_local_password`, Database: `gsu_local`).

### 2. Menjalankan Backend (Laravel)
Buka terminal baru di direktori `backend`:
```powershell
cd backend
composer install
cp .env.example .env # jika belum ada
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan serve
```
Backend API akan aktif di: [http://127.0.0.1:8000](http://127.0.0.1:8000).

### 3. Menjalankan Frontend (Vue 3)
Buka terminal baru di direktori `frontend`:
```powershell
cd frontend
npm install
npm run dev
```
Frontend akan aktif di: [http://localhost:5173](http://localhost:5173).

---

## Konfigurasi Lingkungan (.env)

### Backend (`backend/.env`):
```env
APP_NAME="Yayasan Gerakan Sedekah Umat"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=gsu_local
DB_USERNAME=postgres
DB_PASSWORD=your_local_password
DB_SSLMODE=disable

# Keamanan Sesi Token (24 Jam)
SANCTUM_EXPIRATION=1440

# Penyimpanan Gambar (Otomatis fallback ke disk lokal bila Supabase kosong)
UPLOAD_DISK=supabase
SUPABASE_URL=
SUPABASE_SERVICE_ROLE_KEY=
SUPABASE_STORAGE_BUCKET=public-assets
```

### Frontend (`frontend/.env`):
```env
VITE_API_URL=http://127.0.0.1:8000/api
```

---

## Keamanan & Fitur Utama

1. **Role-Based Access Control (RBAC)**: Endpoint `/api/admin/*` diproteksi middleware `role:admin`. Akses tanpa izin ditolak dengan status `403 Forbidden`.
2. **Anti Brute-Force & Anti Spam Throttling**:
   - Endpoint login dibatasi maksimal 5 percobaan gagal per menit (`throttle:login`).
   - Endpoint pengiriman pesan kontak dibatasi maksimal 6 pesan per menit (`throttle:contact-submission`).
3. **Resiliensi Token & 401 Interceptor**: Axios otomatis mendeteksi sesi kedaluwarsa, membersihkan token lama, dan mengarahkan admin ke halaman login secara aman.
4. **Penyimpanan Gambar Fleksibel**: Mendukung integrasi cloud Supabase Storage dengan kemampuan *fallback* ke penyimpanan lokal publik tanpa kendala koneksi.
5. **Pesan WhatsApp Interaktif**: Formulir donasi memformat konfirmasi donasi secara dinamis dengan nama donatur, nominal, program pilihan, dan metode pembayaran.

---

## Akun Administrator Default

```text
URL Login : http://localhost:5173/admin/login
Email     : admin@sedekahumat.org
Password  : admin123456
```
> [!WARNING]
> Segera perbarui password administrator pada lingkungan produksi sebelum sistem diluncurkan ke publik.

---

## Dokumentasi Lengkap

- [Panduan Instalasi & Setup Lengkap (SETUP.md)](file:///d:/GSU/gsu-website/docs/SETUP.md)
- [Arsitektur Sistem & Alur Data (ARCHITECTURE.md)](file:///d:/GSU/gsu-website/docs/ARCHITECTURE.md)
- [Panduan Pengembangan & Standar Kode (DEVELOPMENT.md)](file:///d:/GSU/gsu-website/docs/DEVELOPMENT.md)
- [Dokumentasi RESTful API Backend (API_DOCS.md)](file:///d:/GSU/gsu-website/backend/API_DOCS.md)
- [Riwayat Pembaruan & Rilis (CHANGELOG.md)](file:///d:/GSU/gsu-website/CHANGELOG.md)

---
*Dikelola dengan penuh amanah oleh Tim Pengembang Yayasan Gerakan Sedekah Umat.*
