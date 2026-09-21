# Backend API - Yayasan Gerakan Sedekah Umat (GSU)

Layanan backend RESTful API untuk Website Publik dan Content Management System (CMS) Admin Yayasan Gerakan Sedekah Umat, dibangun menggunakan **Laravel 11** dan **PHP 8.3+**.

---

## 1. Stack Teknologi

- **Framework**: Laravel 11
- **Bahasa**: PHP 8.3+ (ekstensi `pdo_pgsql`, `mbstring`, `openssl`, `curl`)
- **Autentikasi**: Laravel Sanctum (Bearer Token dengan masa aktif 24 jam)
- **Database**: PostgreSQL 16 (melalui Docker kontainer lokal `gsu_postgres` atau Supabase)
- **Penyimpanan Berkas**: Local Storage publik (`storage/app/public`) dengan dukungan cloud Supabase Storage
- **Testing**: PHPUnit / Pest Test Suite

---

## 2. Cara Menjalankan Backend

### Persiapan Pertama Kali
```powershell
# 1. Masuk ke folder backend
cd backend

# 2. Pasang dependensi PHP
composer install

# 3. Buat berkas environment
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Jalankan migrasi dan seeding data awal
php artisan migrate --force
php artisan db:seed --force

# 6. Buat tautan storage publik
php artisan storage:link

# 7. Jalankan server lokal
php artisan serve
```
Server backend akan aktif di: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**.

---

## 3. Konfigurasi Environment (`.env`)

```env
APP_NAME="Yayasan Gerakan Sedekah Umat"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

# Konfigurasi Database PostgreSQL Lokal (Docker)
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=gsu_local
DB_USERNAME=postgres
DB_PASSWORD=your_local_password
DB_SSLMODE=disable

# Keamanan Sesi Token Sanctum (1440 Menit = 24 Jam)
SANCTUM_EXPIRATION=1440

# Penyimpanan Upload (Otomatis fallback ke disk lokal jika kredensial kosong)
UPLOAD_DISK=supabase
SUPABASE_URL=
SUPABASE_SERVICE_ROLE_KEY=
SUPABASE_STORAGE_BUCKET=public-assets
```

---

## 4. Struktur Arsitektur Kode

```text
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/
│   │   │   ├── Admin/             # Controller khusus CMS terproteksi
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── HomepageSectionController.php
│   │   │   │   ├── SingletonController.php
│   │   │   │   └── UploadController.php
│   │   │   ├── Public/            # Controller untuk halaman publik
│   │   │   │   └── ContentController.php
│   │   │   ├── AuthController.php # Login, logout sesi spesifik, profil /me
│   │   │   ├── ContactController.php
│   │   │   ├── GalleryController.php
│   │   │   └── ProgramController.php
│   │   └── Middleware/
│   │       └── EnsureUserIsAdmin.php # Validasi role admin (403 Forbidden)
│   ├── Models/                    # 10 Eloquent Models
│   ├── Providers/
│   │   └── AppServiceProvider.php # Registrasi rate limiter login & kontak
│   └── Services/
│       ├── PublicContentCache.php # Caching data beranda (serialisasi array)
│       └── SupabaseStorageService.php # Layanan upload gambar + local fallback
├── config/                        # app, auth, cors, database, sanctum, filesystems
├── database/
│   ├── migrations/                # Skema tabel database
│   └── seeders/                   # Seeder akun admin dan data awal
├── routes/
│   └── api/v1.php                 # Daftar endpoint API v1
└── tests/
    └── Feature/
        └── SecurityHardeningTest.php # Pengujian otomatis role, rate limit & upload
```

---

## 5. Fitur Keamanan

1. **Proteksi Role Admin (`role:admin`)**: Seluruh rute `/api/admin/*` diproteksi middleware `EnsureUserIsAdmin`. Pengguna tanpa atribut `role === 'admin'` ditolak dengan status **`403 Forbidden`**.
2. **Rate Limiting Anti Brute-Force & Spam**:
   - `throttle:login`: Maksimal 5 kali percobaan per menit. Jika terlampaui, mengembalikan status **`429 Too Many Requests`**.
   - `throttle:contact-submission`: Maksimal 6 pesan per menit per IP.
3. **Masa Aktif Token Terbatas**: Token kedaluwarsa dalam 24 jam.
4. **Sanitasi Data XSS**: Input formulir publik disanitasi menggunakan `strip_tags()`.

---

## 6. Menjalankan Pengujian Otomatis

Untuk memastikan seluruh fungsi dan aturan keamanan berjalan dengan baik:
```powershell
php artisan test
```
*Seluruh 9 test case harus berstatus hijau (100% Passed).*

---

## 7. Perintah Artisan yang Sering Digunakan

- `php artisan migrate:fresh --seed`: Mengosongkan dan membangun ulang database dari nol beserta data seed default.
- `php artisan cache:clear`: Membersihkan seluruh cache aplikasi.
- `php artisan route:list --path=api`: Menampilkan seluruh daftar rute API beserta middleware yang melindunginya.
