# Dokumentasi RESTful API - Yayasan GSU

Dokumen ini adalah referensi teknis lengkap seluruh endpoint RESTful API Backend **Yayasan Gerakan Sedekah Umat (GSU)**.

---

## Informasi Umum

- **Base URL**: `http://localhost:8000/api`
- **Format Pertukaran Data**: JSON (`Content-Type: application/json` dan `Accept: application/json`)
- **Format Header Autentikasi**:
  ```text
  Authorization: Bearer <personal-access-token>
  ```

---

## Kode Status HTTP (HTTP Status Codes)

| Kode | Keterangan | Situasi Penggunaan |
| :--- | :--- | :--- |
| **`200 OK`** | Berhasil | Pengambilan data (`GET`) atau pembaruan data (`PUT`) berhasil. |
| **`201 Created`** | Dibuat | Penambahan data baru (`POST`) atau upload berkas berhasil. |
| **`401 Unauthorized`** | Belum Terautentikasi | Token tidak disertakan, tidak valid, atau telah kedaluwarsa. |
| **`403 Forbidden`** | Akses Ditolak | Pengguna terautentikasi tetapi tidak memiliki peran administrator (`role !== 'admin'`). |
| **`422 Unprocessable`** | Validasi Gagal | Data yang dikirimkan tidak memenuhi skema validasi yang ditentukan. |
| **`429 Too Many Requests`** | Batas Frekuensi Terlampaui | Terkena *rate limiter* (terlalu banyak percobaan login atau spam pesan kontak). |

---

## 1. Endpoint Autentikasi (Auth)

### Login Admin
- **URL**: `POST /login` atau `POST /admin/login`
- **Rate Limit**: Maksimal 5 percobaan gagal per menit (`throttle:login`)
- **Payload Permintaan**:
  ```json
  {
    "email": "admin@sedekahumat.org",
    "password": "admin123456"
  }
  ```
- **Respons Berhasil (`200 OK`)**:
  ```json
  {
    "token": "1|eUqW9TBja9P4Z1...",
    "user": {
      "id": 1,
      "name": "Admin GSU",
      "email": "admin@sedekahumat.org",
      "role": "admin"
    }
  }
  ```

### Logout Sesi Aktif
- **URL**: `POST /logout` atau `POST /admin/logout`
- **Header**: `Authorization: Bearer <token>`
- **Respons Berhasil (`200 OK`)**:
  ```json
  {
    "message": "Logout berhasil"
  }
  ```

### Profil Pengguna Aktif
- **URL**: `GET /me` atau `GET /admin/me`
- **Header**: `Authorization: Bearer <token>`
- **Respons Berhasil (`200 OK`)**: Mengembalikan objek user yang sedang terautentikasi.

---

## 2. Endpoint Publik (Public Content)

### Data Beranda Lengkap
- **URL**: `GET /public/home`
- **Keterangan**: Mengembalikan data gabungan pengaturan situs, seksi beranda, 3 program unggulan, preview galeri, profil tentang, dan rekening donasi dalam satu request berkecepatan tinggi dengan akselerasi cache.

### Data Layout (Header & Footer)
- **URL**: `GET /public/layout`
- **Keterangan**: Mengembalikan konfigurasi `site_settings`, `contact_settings`, dan `footer_settings`.

### Daftar Program Donasi
- **URL**: `GET /programs` (Semua program aktif)
- **URL**: `GET /programs/featured` (Program bertanda unggulan saja)
- **URL**: `GET /programs/{slug}` (Detail program berdasarkan slug URL)

### Dokumentasi Galeri
- **URL**: `GET /galleries` (Semua dokumentasi kegiatan aktif)
- **URL**: `GET /galleries/{id}` (Detail dokumentasi tunggal)

### Pengiriman Pesan Kontak
- **URL**: `POST /contact-messages` atau `POST /contacts`
- **Rate Limit**: Maksimal 6 pengiriman per menit (`throttle:contact-submission`)
- **Payload Permintaan**:
  ```json
  {
    "name": "Ahmad Fauzi",
    "email": "ahmad@example.com",
    "phone": "+62812345678",
    "subject": "Pertanyaan Donasi Wakaf",
    "message": "Assalamu'alaikum, saya ingin menanyakan laporan penyaluran donasi wakaf."
  }
  ```
- **Respons Berhasil (`201 Created`)**:
  ```json
  {
    "message": "Pesan terkirim",
    "data": { "id": 1, "status": "unread", ... }
  }
  ```

---

## 3. Endpoint Manajemen CMS (Admin Only)
*Semua endpoint di bawah ini wajib menyertakan header `Authorization: Bearer <token>` dan pengguna wajib memiliki `role: 'admin'`.*

### Ringkasan Statistik Dashboard
- **URL**: `GET /admin/dashboard`
- **Respons Berhasil (`200 OK`)**:
  ```json
  {
    "programs_active": 3,
    "galleries_total": 2,
    "messages_total": 5,
    "messages_unread": 2,
    "latest_messages": [...]
  }
  ```

### Unggah Gambar Berkas (Image Upload)
- **URL**: `POST /admin/upload`
- **Content-Type**: `multipart/form-data`
- **Form Data**:
  - `image`: Berkas gambar (`jpg, jpeg, png, webp, svg` maks. 5MB).
  - `folder`: Subfolder tujuan (`logos`, `hero`, `programs`, `galleries`, `donation`, `about`, `qris`, `uploads`).
- **Respons Berhasil (`201 Created`)**:
  ```json
  {
    "message": "Upload berhasil",
    "bucket": "local-public",
    "path": "programs/3f7a18e2-...webp",
    "url": "http://127.0.0.1:8000/storage/programs/3f7a18e2-...webp"
  }
  ```
  *Jika kredensial Supabase diatur, upload akan diarahkan ke bucket Supabase Storage secara otomatis.*

### Pengaturan Tunggal (Singleton Settings)
- **Rute Tersedia**:
  - `GET /admin/site-settings` & `PUT /admin/site-settings`
  - `GET /admin/about` & `PUT /admin/about`
  - `GET /admin/donation-settings` & `PUT /admin/donation-settings`
  - `GET /admin/contact-settings` & `PUT /admin/contact-settings`
  - `GET /admin/footer-settings` & `PUT /admin/footer-settings`

### Manajemen Program Donasi (CRUD)
- `GET /admin/programs?page=1&per_page=10&search=qurban&status=active` (Mendukung paginasi & filter)
- `POST /admin/programs` (Tambah program baru)
- `GET /admin/programs/{id}` (Detail program)
- `PUT /admin/programs/{id}` (Perbarui data program)
- `DELETE /admin/programs/{id}` (Hapus program)

### Manajemen Galeri (CRUD)
- `GET /admin/galleries?page=1&per_page=10&search=santunan`
- `POST /admin/galleries`
- `GET /admin/galleries/{id}`
- `PUT /admin/galleries/{id}`
- `DELETE /admin/galleries/{id}`

### Manajemen Pesan Kontak
- `GET /admin/contact-messages?page=1&per_page=10&status=unread`
- `PUT /admin/contact-messages/{id}` (Ubah status: `read`, `archived`)
- `DELETE /admin/contact-messages/{id}` (Hapus pesan)
