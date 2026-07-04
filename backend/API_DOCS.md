# GSU API - Dokumentasi

Backend API untuk Gerakan Sedekah Umat menggunakan Laravel 13 dan MySQL.

## Setup Awal

### Prerequisites
- PHP 8.2+
- MySQL 8.0+
- Composer

### Installation

```bash
cd D:\GSU\gsu-api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### Running Server

```bash
php artisan serve
# API akan berjalan di http://localhost:8000
```

## Database

Database: `gsu_api`
Credentials: root (no password) via Laragon

### Tables
- `users` - Admin users
- `programs` - Program kegiatan
- `galleries` - Galeri foto
- `contacts` - Pesan kontak dari website
- `personal_access_tokens` - Token autentikasi

## API Endpoints

### Authentication

**Login**
```
POST /api/login
Content-Type: application/json

{
  "email": "admin@sedekahumat.org",
  "password": "admin123456"
}

Response: 200
{
  "token": "...",
  "user": { ... }
}
```

**Logout** (requires token)
```
POST /api/logout
Authorization: Bearer {token}
```

**Get Current User** (requires token)
```
GET /api/me
Authorization: Bearer {token}
```

### Programs (Public Read, Auth Write)

**Get All Programs**
```
GET /api/programs
```

**Get Program Detail**
```
GET /api/programs/{id}
```

**Create Program** (requires token)
```
POST /api/programs
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Program Name",
  "description": "Description",
  "category": "Pendidikan",
  "collected": 0,
  "target": 50000000,
  "image": "https://...",
  "active": true
}
```

**Update Program** (requires token)
```
PUT /api/programs/{id}
Authorization: Bearer {token}
Content-Type: application/json

{ same fields as create }
```

**Delete Program** (requires token)
```
DELETE /api/programs/{id}
Authorization: Bearer {token}
```

### Galleries (Public Read, Auth Write)

**Get All Galleries**
```
GET /api/galleries
```

**Get Gallery Detail**
```
GET /api/galleries/{id}
```

**Create Gallery** (requires token)
```
POST /api/galleries
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Gallery Title",
  "description": "Description",
  "image": "https://...",
  "category": "Kegiatan",
  "active": true
}
```

**Update Gallery** (requires token)
```
PUT /api/galleries/{id}
Authorization: Bearer {token}
```

**Delete Gallery** (requires token)
```
DELETE /api/galleries/{id}
Authorization: Bearer {token}
```

### Contacts (Public Write, Auth Read)

**Create Contact** (public)
```
POST /api/contacts
Content-Type: application/json

{
  "name": "Nama",
  "email": "email@example.com",
  "phone": "081234567890",
  "message": "Pesan"
}

Response: 201
{
  "message": "Pesan terkirim",
  "data": { ... }
}
```

**Get All Contacts** (requires token)
```
GET /api/contacts
Authorization: Bearer {token}
```

**Get Contact Detail** (requires token)
```
GET /api/contacts/{id}
Authorization: Bearer {token}
```

**Update Contact Status** (requires token)
```
PUT /api/contacts/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "status": "read"
}
```

**Delete Contact** (requires token)
```
DELETE /api/contacts/{id}
Authorization: Bearer {token}
```

## Struktur Project

```
app/
├── Models/
│   ├── User.php
│   ├── Program.php
│   ├── Gallery.php
│   └── Contact.php
└── Http/
    └── Controllers/
        └── Api/
            ├── AuthController.php
            ├── ProgramController.php
            ├── GalleryController.php
            └── ContactController.php

database/
├── migrations/
└── seeders/
    ├── AdminSeeder.php
    └── ProgramSeeder.php

routes/
└── api/
    └── v1.php

config/
└── sanctum.php
```

## Credentials

**Admin Default:**
- Email: `admin@sedekahumat.org`
- Password: `admin123456`

## Notes

- API menggunakan Laravel Sanctum untuk authentication
- Token harus dikirim di header: `Authorization: Bearer {token}`
- CORS sudah dikonfigurasi untuk localhost:5173 (frontend Vite)
- Semua response menggunakan JSON format
