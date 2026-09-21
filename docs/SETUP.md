# Panduan Instalasi & Persiapan Lingkungan (Setup Guide)

Dokumen ini berisi panduan langkah demi langkah untuk menyiapkan dan menjalankan proyek **Website Yayasan Gerakan Sedekah Umat (GSU)** pada komputer pengembang lokal dari nol.

---

## 1. Prasyarat Sistem

Sebelum memulai instalasi, pastikan perangkat Anda telah terpasang perangkat lunak berikut:

### Kebutuhan Perangkat Lunak
- **Sistem Operasi**: Windows 10/11 (dengan WSL 2 aktif), macOS, atau Linux Ubuntu 22.04+.
- **Docker Desktop**: Versi terbaru dengan dukungan WSL 2 backend (untuk menjalankan database PostgreSQL dan Adminer secara terisolasi).
- **Node.js**: Versi 18.x atau 20.x LTS (disertai `npm`). Unduh di [nodejs.org](https://nodejs.org/).
- **PHP**: Versi 8.2 atau 8.3 dengan ekstensi aktif: `pdo_pgsql`, `openssl`, `mbstring`, `tokenizer`, `xml`, `ctype`, `curl`, `fileinfo`.
- **Composer**: Manajer paket PHP versi 2.6+. Unduh di [getcomposer.org](https://getcomposer.org/).
- **Git**: Untuk kontrol versi kode.

---

## 2. Langkah Instalasi

### Langkah 1: Kloning Repositori
Buka terminal (PowerShell / Command Prompt / Terminal) dan arahkan ke direktori kerja Anda:
```powershell
git clone <url-repositori-gsu>
cd gsu-website
```

---

### Langkah 2: Menyalakan Database PostgreSQL & Adminer (Docker)
Proyek ini telah dilengkapi dengan konfigurasi `docker-compose.yml` untuk menyalakan database PostgreSQL dan web interface Adminer secara otomatis.

1. Pastikan aplikasi **Docker Desktop** sudah terbuka dan aktif.
2. Di direktori root proyek (`gsu-website/`), jalankan perintah:
   ```powershell
   docker-compose up -d
   ```
3. Periksa status kontainer:
   ```powershell
   docker ps
   ```
   Pastikan terdapat dua kontainer aktif:
   - `gsu_postgres`: PostgreSQL 16 pada port `5432`
   - `gsu_adminer`: Adminer Web DB Client pada port `8080`

> [!TIP]
> Anda dapat membuka **Adminer** di browser melalui [http://localhost:8080](http://localhost:8080) dengan parameter:
> - **Sistem**: PostgreSQL
> - **Server**: `db` (atau `localhost` jika mengakses dari luar docker network)
> - **Pengguna**: `postgres`
> - **Sandi**: `your_local_password`
> - **Basis data**: `gsu_local`

---

### Langkah 3: Menyiapkan Backend Laravel 11

1. Masuk ke direktori backend:
   ```powershell
   cd backend
   ```
2. Pasang dependensi PHP melalui Composer:
   ```powershell
   composer install
   ```
3. Salin berkas konfigurasi lingkungan:
   ```powershell
   cp .env.example .env
   ```
4. Buat kunci enkripsi aplikasi Laravel:
   ```powershell
   php artisan key:generate
   ```
5. Periksa konfigurasi database di file `backend/.env`:
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=gsu_local
   DB_USERNAME=postgres
   DB_PASSWORD=your_local_password
   DB_SSLMODE=disable

   SANCTUM_EXPIRATION=1440
   ```
6. Jalankan migrasi tabel dan seeding data awal:
   ```powershell
   php artisan migrate --force
   php artisan db:seed --force
   ```
   *Data awal yang di-seed mencakup akun admin default, program unggulan, seksi homepage, dan data profil yayasan.*

7. Buat symlink untuk storage publik (jika belum ada):
   ```powershell
   php artisan storage:link
   ```

8. Jalankan server lokal Laravel:
   ```powershell
   php artisan serve
   ```
   Backend API sekarang berjalan di **[http://127.0.0.1:8000](http://127.0.0.1:8000)**.

---

### Langkah 4: Menyiapkan Frontend Vue 3 + Vite

1. Buka jendela terminal baru dan masuk ke direktori frontend:
   ```powershell
   cd frontend
   ```
2. Pasang dependensi JavaScript melalui npm:
   ```powershell
   npm install
   ```
3. Pastikan berkas `frontend/.env` telah terarah ke URL backend:
   ```env
   VITE_API_URL=http://127.0.0.1:8000/api
   ```
4. Jalankan server pengembangan Vite:
   ```powershell
   npm run dev
   ```
   Frontend sekarang berjalan di **[http://localhost:5173](http://localhost:5173)**.

---

## 3. Akun Administrator Default

Setelah proses seeder selesai, Anda dapat masuk ke Dashboard Admin CMS menggunakan kredensial berikut:

| Parameter | Nilai |
| :--- | :--- |
| **URL Login** | [http://localhost:5173/admin/login](http://localhost:5173/admin/login) |
| **Email** | `admin@sedekahumat.org` |
| **Password** | `admin123456` |
| **Role** | `admin` |

---

## 4. Pengujian & Verifikasi

### Uji Otomatis Backend
Untuk menjalankan test suite pengujian keamanan, rate limiting, otorisasi role admin, dan fungsionalitas upload:
```powershell
cd backend
php artisan test
```
*Hasil yang diharapkan: 9 unit/feature test berhasil (100% Passed).*

### Uji Kompilasi Produksi Frontend
Untuk memastikan seluruh modul Vue terkompilasi bersih tanpa error:
```powershell
cd frontend
npm run build
```
*Hasil yang diharapkan: Berkas produksi berhasil dibuat di folder `dist/`.*

---

## 5. Pemecahan Masalah (Troubleshooting)

### Kendala 1: Port 5432 Sudah Digunakan
**Gejala**: Error `bind: address already in use` saat menjalankan `docker-compose up -d`.
**Solusi**: Terdapat instalasi PostgreSQL lokal yang sedang berjalan di host. Matikan service PostgreSQL lokal atau ubah mapping port host pada file `docker-compose.yml` (misal `"5433:5432"`), lalu sesuaikan `DB_PORT=5433` pada file `backend/.env`.

### Kendala 2: Ekstensi `pdo_pgsql` Tidak Ditemukan
**Gejala**: Error `could not find driver` saat menjalankan `php artisan migrate`.
**Solusi**: Buka file `php.ini` Anda (dapat dicari via `php --ini`), hapus tanda titik koma (`;`) di depan baris `extension=pdo_pgsql` dan `extension=pgsql`, lalu simpan dan restart terminal.

### Kendala 3: Gambar yang Diunggah Tidak Muncul
**Gejala**: Upload berhasil tetapi gambar berstatus 404.
**Solusi**: Pastikan perintah `php artisan storage:link` telah dijalankan sehingga direktori `backend/storage/app/public` terhubung ke `backend/public/storage`.

---
*Dokumen ini diperbarui secara berkala oleh Tim Pengembang Yayasan GSU.*
