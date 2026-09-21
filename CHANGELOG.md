# Catatan Perubahan (Changelog) - Website Yayasan GSU

Seluruh pembaruan dan perubahan penting pada proyek **Website Yayasan Gerakan Sedekah Umat (GSU)** dicatat secara kronologis dalam dokumen ini.

Format pencatatan mengacu pada standar [Keep a Changelog](https://keepachangelog.com/id/1.0.0/) dan mematuhi aturan [Semantic Versioning](https://semver.org/lang/id/).

---

## [1.2.0] - 2026-09-21

### Ditambahkan (Added)
- Berkas konfigurasi `.gitignore` khusus pada folder `frontend/` untuk mencegah file build (`dist`) dan cache editor ter-commit.
- Dokumentasi lengkap dalam **Bahasa Indonesia** pada seluruh berkas panduan (`README.md`, `SETUP.md`, `ARCHITECTURE.md`, `DEVELOPMENT.md`, `API_DOCS.md`, dan `STRUCTURE.md`).

### Diubah (Changed)
- Restrukturisasi folder frontend: Memindahkan `LoginPage.vue` dari `src/pages/` ke `src/views/LoginPage.vue` sehingga seluruh halaman terpusat rapi di direktori `src/views/`.
- Memperbarui rute navigasi `admin-login` pada `router/index.js` agar mengarah ke lokasi view baru.

### Dihapus (Removed)
- Menghapus berkas usang `frontend/src/views/AdminDashboardView.vue` (wrapper kosong yang tidak digunakan).
- Membersihkan direktori-direktori kosong yang tidak terpakai: `frontend/src/pages/`, `frontend/src/views/public/`, `frontend/src/components/layout/`, `frontend/src/assets/icons/`, `frontend/src/assets/images/`, `backend/app/Http/Requests/`, dan `backend/app/Http/Resources/`.

---

## [1.1.0] - 2026-09-21

### Keamanan (Security)
- **Otorisasi Role Admin**: Menambahkan middleware `EnsureUserIsAdmin` (`role:admin`) pada seluruh endpoint `/api/admin/*`. Pengguna non-admin otomatis ditolak dengan status **`403 Forbidden`**.
- **Perlindungan Anti Brute-Force**: Menerapkan rate limiter `throttle:login` (maksimal 5 kali percobaan per menit) pada rute login admin.
- **Perlindungan Anti Spam Kontak**: Menerapkan rate limiter `throttle:contact-submission` (maksimal 6 pesan per menit) pada formulir kontak.
- **Siklus Hidup Token**: Menetapkan kedaluwarsa token Sanctum menjadi 24 jam (1440 menit) melalui `SANCTUM_EXPIRATION`.
- **Targeted Token Revocation**: Mengubah proses logout agar hanya mencabut token sesi saat ini (`currentAccessToken()->delete()`).
- **Sanitasi Data XSS**: Menambahkan filter `strip_tags()` pada input nama, subjek, dan pesan kontak publik.
- **Validasi Skema Singleton**: Menambahkan validasi tipe data dan batasan panjang string pada seluruh pengaturan CMS (`SingletonController`).

### Fungsionalitas & Keandalan (Fixed & Improved)
- **Storage Local Fallback**: Menyempurnakan `SupabaseStorageService` dan `UploadController` agar otomatis menyimpan file ke penyimpanan lokal publik (`storage/app/public/...`) apabila kredensial cloud Supabase belum diisi di `.env`.
- **Perbaikan Serialisasi Cache Database**: Mengubah objek model pada `PublicContentCache`, `ProgramController`, dan `GalleryController` menjadi array murni (`toArray()`), menuntaskan bug `__PHP_Incomplete_Class_Name`.
- **Pencarian PostgreSQL Case-Insensitive**: Mengubah query pencarian data di PostgreSQL menggunakan operator `ILIKE`.
- **Axios 401 Interceptor**: Menambahkan penanganan otomatis saat token kedaluwarsa untuk membersihkan storage dan mengarahkan admin kembali ke halaman login.
- **Formulir Donasi WhatsApp**: Memperkaya template pesan konfirmasi WhatsApp dengan rincian nama donatur, nomor kontak, nominal terformat, dan metode pembayaran.
- **Test Suite Otomatis**: Membuat `SecurityHardeningTest` di Laravel dengan 9 test case yang mencakup pengujian role, rate limiting, dan upload (100% lulus).

---

## [1.0.1] - 2026-09-20

### Diubah (Changed)
- **Transformasi Desain Editorial (Non-AI Slop)**:
  - Mengganti font generik dengan tipografi berwibawa: **Newsreader** (Google Fonts editorial serif) untuk heading dan **Plus Jakarta Sans** untuk body text & formulir.
  - Memperbarui palet warna ke warna alamiah: *Forest Emerald* (`#0F8A5F`, `#0C5A3E`), *Warm Ivory Paper* (`#FBF9F5`), dan *Warm Ochre Gold* (`#C27803`).
  - Menghapus ornamen template AI sintetis: bola gradien melayang (*floating blobs*), badge bintang (*sparkles*), foto avatar Pravatar palsu, kartu kaca melayang (*glass cards*), dan animasi kilatan tombol (*sheen sweeps*).
  - Menyederhanakan bar statistik dampak menjadi *Grounded Impact Strip* yang kokoh dan mudah dibaca.

---

## [1.0.0] - 2026-07-04

### Ditambahkan (Added)
- Inisialisasi awal repositori proyek Yayasan Gerakan Sedekah Umat (GSU).
- Backend RESTful API berbasis Laravel dengan autentikasi Laravel Sanctum.
- Frontend Single Page Application (SPA) berbasis Vue 3 + Vite.
- Migrasi tabel database untuk pengguna, program, galeri, dan kontak.
