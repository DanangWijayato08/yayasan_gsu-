# Panduan Pengembangan (Development Guide)

Panduan teknis bagi para pengembang (*developers*) yang berkontribusi dalam pengembangan dan pemeliharaan kode pada proyek **Website Yayasan Gerakan Sedekah Umat (GSU)**.

---

## 1. Lingkungan Kerja yang Direkomendasikan

- **Editor Kode**: [Visual Studio Code](https://code.visualstudio.com/)
- **Ekstensi VS Code yang Direkomendasikan**:
  - *Frontend*: Vue - Official (Volar), Prettier - Code Formatter, ESLint.
  - *Backend*: PHP Intelephense, Laravel Extra Intellisense, PHP CS Fixer.
  - *Umum*: Docker, GitLens, Thunder Client / Postman (untuk pengujian API).
- **Terminal**: PowerShell (Windows) atau Bash/Zsh (Linux/Mac).

---

## 2. Struktur Direktori Proyek

### Frontend (`frontend/src/`)
Struktur frontend mengikuti konvensi modular Vue 3:
```text
src/
├── assets/          # main.css (design tokens warna, tipografi, utility classes)
├── components/      # Komponen UI modular
│   ├── admin/       # Komponen khusus panel CMS (AdminShell.vue)
│   ├── common/      # Komponen global (Navbar, Footer, Stats, Testimonial, Counter)
│   └── sections/    # Seksi halaman (Hero, About, ProgramSection, Contact, DonationSection)
├── composables/     # Logika reaktif Vue yang dapat digunakan kembali (useScrollReveal)
├── router/          # Definisi rute, layout, dan navigation guards (index.js)
├── services/        # Abstraksi panggilan API Axios (api.js, adminService, publicService)
├── stores/          # State management Pinia (auth.js)
├── utils/           # Konstanta dan helper (constants.js)
├── views/           # Halaman utama aplikasi (HomeView, DonasiView, LoginPage, CmsView, dll.)
├── App.vue          # Root component
└── main.js          # Inisialisasi aplikasi Vue dan directive animasi
```

### Backend (`backend/app/`)
Struktur backend mematuhi standar arsitektur Laravel 11:
```text
app/
├── Http/
│   ├── Controllers/ # RESTful API Controllers (Auth, Program, Gallery, Contact)
│   │   └── Api/
│   │       ├── Admin/  # DashboardController, SingletonController, UploadController
│   │       └── Public/ # ContentController
│   └── Middleware/  # EnsureUserIsAdmin (Proteksi RBAC)
├── Models/          # Eloquent Models (User, Program, Gallery, Contact, dll.)
├── Providers/       # AppServiceProvider (Registrasi Rate Limiter)
└── Services/        # PublicContentCache, SupabaseStorageService
```

---

## 3. Konvensi & Standar Kode

### A. Standar Frontend (Vue 3)
1. **Gaya Komponen**: Gunakan format `<script setup>` (Composition API) di seluruh komponen baru.
2. **Desain & Gaya Tampilan**:
   - Seluruh warna, ukuran sudut (*border-radius*), dan jarak (*spacing*) wajib mengacu pada token CSS di `main.css`:
     - Warna: `var(--color-primary)`, `var(--color-secondary)`, `var(--color-accent)`, `var(--color-surface)`
     - Tipografi: `var(--font-heading)` (*Newsreader* serif) dan `var(--font-body)` (*Plus Jakarta Sans*)
   - Hindari efek animasi berlebihan (*floating blobs*, gradien teks transparan, atau *sheen animations*) untuk menjaga wibawa dan kredibilitas yayasan resmi.
3. **Panggilan API**: Jangan melakukan panggilan `axios` langsung di dalam komponen; gunakan modul service di direktori `services/`.
4. **Keamanan Rute**: Setiap rute baru yang diperuntukkan bagi admin wajib menyertakan properti `meta: { requiresAuth: true, layout: 'admin' }`.

### B. Standar Backend (Laravel 11)
1. **Format Kode**: Wajib mematuhi standar **PSR-12**.
2. **Validasi Input**:
   - Seluruh request yang mengubah data (`POST`, `PUT`, `PATCH`) wajib divalidasi dengan aturan eksplisit (`$request->validate([...])`).
   - Data teks dari formulir publik wajib disanitasi menggunakan `strip_tags()` sebelum disimpan ke database.
3. **Otorisasi & Keamanan**:
   - Rute administrasi wajib berada di bawah grup middleware `['auth:sanctum', 'admin']`.
   - Gunakan `currentAccessToken()->delete()` pada proses logout untuk mencabut token sesi yang sedang aktif.
4. **Dukungan PostgreSQL**:
   - Untuk pencarian teks pada database PostgreSQL, gunakan operator `ILIKE` agar pencarian bersifat *case-insensitive*.
5. **Serialisasi Cache**:
   - Jangan pernah menyimpan model objek Eloquent mentah ke dalam cache database. Konversikan selalu ke dalam array murni via `->toArray()`.

---

## 4. Alur Kerja Kontrol Versi (Git Workflow)

1. **Percabangan (Branching)**:
   - `main`: Branch rilis produksi yang selalu stabil.
   - `feature/<nama-fitur>`: Branch untuk penambahan fitur baru (contoh: `feature/laporan-keuangan`).
   - `fix/<nama-bug>`: Branch untuk perbaikan celah keamanan atau bug (contoh: `fix/upload-mime-validation`).
2. **Pesan Komit (Conventional Commits)**:
   Gunakan format commit berbahasa terstruktur:
   - `feat: tambah modul laporan penyaluran donasi`
   - `fix: tangani error rate limiting pada form kontak`
   - `refactor: rapikan struktur view dan bersihkan file usang`
   - `docs: perbarui panduan arsitektur sistem`

---

## 5. Prosedur Pengujian (Testing)

Sebelum melakukan merge atau push kode baru, selalu jalankan pengujian berikut:

### 1. Jalankan Unit & Feature Test Backend
```powershell
cd backend
php artisan test
```
Pastikan seluruh 9 test case berstatus hijau (Passed).

### 2. Jalankan Uji Kompilasi Frontend
```powershell
cd frontend
npm run build
```
Pastikan tidak ada kesalahan kompilasi, tipe data, atau impor berkas yang rusak.

---
*Dokumen panduan ini ditujukan untuk menjaga konsistensi kualitas kode proyek Yayasan GSU.*
