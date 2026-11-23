# 📖 PANDUAN SETUP LOKAL - GSU Website

## Prasyarat
- Node.js 18+ LTS
- PHP 8.3+
- Composer 2.6+
- Docker Desktop
- Git

## Quick Start (5 Menit)

### 1. Database
```bash
docker-compose up -d
```

### 2. Backend
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan serve
```
✅ http://127.0.0.1:8000

### 3. Frontend
```bash
cd frontend
npm install
npm run dev
```
✅ http://localhost:5173

## Admin Login
- Email: `admin@sedekahumat.org`
- Password: `admin123456`

## Database Admin
- URL: http://localhost:8080
- Username: `postgres`
- Password: `your_local_password`

## Troubleshooting

| Masalah | Solusi |
|---------|--------|
| Port 8000 sudah dipakai | `php artisan serve --port=8001` |
| PostgreSQL error | `docker-compose restart` |
| Node modules error | `rm -r node_modules && npm install` |
| Database migrate error | Pastikan Docker running & migration file ada |

## Perintah Penting

```bash
# Backend
php artisan migrate           # Jalankan migrations
php artisan db:seed          # Seed data awal
php artisan test             # Run tests
php artisan tinker           # Interactive shell

# Frontend
npm run dev                   # Start dev server
npm run build                 # Build production
npm install                   # Install dependencies

# Docker
docker-compose up -d         # Start containers
docker-compose down          # Stop containers
docker ps                    # List containers
```

---

Untuk dokumentasi lengkap, lihat file lain di folder `/docs/`
