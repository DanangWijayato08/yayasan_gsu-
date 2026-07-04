# Setup Guide - GSU Website

Panduan lengkap setup project Gerakan Sedekah Umat.

## Prerequisites

### System Requirements
- Windows 10+ / macOS / Linux
- 4GB RAM minimum
- 10GB disk space

### Software Requirements

**Frontend:**
- Node.js 16+ (download: https://nodejs.org/)
- npm 8+ (comes with Node.js)

**Backend:**
- PHP 8.2+ (via Laragon recommended for Windows)
- MySQL 8.0+
- Composer (download: https://getcomposer.org/)

**Tools:**
- Git (download: https://git-scm.com/)
- Postman atau Insomnia (untuk test API)
- VS Code atau editor favorit

## Installation

### 1. Clone Repository

```bash
git clone <repository-url>
cd GSU
```

### 2. Frontend Setup

```bash
cd frontend

# Install dependencies
npm install --legacy-peer-deps

# Create environment file
cp .env.example .env (jika ada)

# Start development server
npm run dev
```

**Frontend akan berjalan di:** http://localhost:5173

### 3. Backend Setup

```bash
cd backend

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Setup database
php artisan migrate
php artisan db:seed

# Start development server
php artisan serve
```

**Backend akan berjalan di:** http://localhost:8000

## Database Setup

### Option 1: Laragon (Windows)

1. Buka Laragon
2. Klik "MySQL" untuk start MySQL
3. Database otomatis dibuat saat migration

### Option 2: Manual MySQL

```bash
# Login ke MySQL
mysql -u root -p

# Create database
CREATE DATABASE gsu_api CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Exit
EXIT;
```

## Verifikasi Setup

### Frontend
- Buka http://localhost:5173
- Seharusnya melihat website Gerakan Sedekah Umat
- Menu tidak ada "Berita" (sudah dihapus)

### Backend
- Buka http://localhost:8000/api/programs
- Seharusnya melihat JSON response dengan program list

### API Test

Login menggunakan Postman:

```
POST http://localhost:8000/api/login
Content-Type: application/json

{
  "email": "admin@sedekahumat.org",
  "password": "admin123456"
}
```

Response akan berisi token untuk authenticated requests.

## Environment Configuration

### Frontend (.env)

```
VITE_API_URL=http://localhost:8000/api
```

### Backend (.env)

```
APP_NAME="GSU API"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gsu_api
DB_USERNAME=root
DB_PASSWORD=

SANCTUM_STATEFUL_DOMAINS=localhost:5173,127.0.0.1:5173
```

## Running Both Services

### Terminal 1 - Frontend
```bash
cd frontend
npm run dev
```

### Terminal 2 - Backend
```bash
cd backend
php artisan serve
```

Sekarang kedua service berjalan:
- Frontend: http://localhost:5173
- Backend: http://localhost:8000

## Common Issues & Solutions

### Issue: "npm ERR! peer dep missing"
**Solution:**
```bash
npm install --legacy-peer-deps
```

### Issue: "MySQL connection refused"
**Solution:**
- Pastikan MySQL running di Laragon
- Cek kredensial di .env
- Buat database `gsu_api` jika belum ada

### Issue: "CORS error"
**Solution:**
- Pastikan backend .env memiliki SANCTUM_STATEFUL_DOMAINS yang benar
- Restart backend server

### Issue: "Port 5173 atau 8000 sudah digunakan"
**Solution:**
```bash
# Frontend (gunakan port lain)
npm run dev -- --port 5174

# Backend (gunakan port lain)
php artisan serve --port 8001
```

## Database Seeding

Reset database dengan data baru:

```bash
cd backend
php artisan migrate:fresh --seed
```

Data yang akan di-seed:
- 1 admin user
- 3 sample programs
- Database ready untuk development

## Build for Production

### Frontend
```bash
cd frontend
npm run build
```

Output di: `frontend/dist/`

### Backend
```bash
cd backend
php artisan config:cache
php artisan route:cache
php artisan optimize
```

## Next Steps

1. Baca `docs/ARCHITECTURE.md` untuk memahami struktur
2. Baca `docs/API.md` untuk dokumentasi API
3. Baca `backend/API_DOCS.md` untuk endpoint details
4. Baca `docs/DEVELOPMENT.md` untuk workflow development

## Support

- Frontend issue: lihat `frontend/README.md`
- Backend issue: lihat `backend/README.md`
- API issue: lihat `backend/API_DOCS.md`

---

**Happy Coding! 🚀**
