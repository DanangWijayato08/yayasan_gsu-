# GSU API Backend

Backend API untuk website Gerakan Sedekah Umat.

## Tech Stack

- Laravel 13
- MySQL (Laragon)
- Laravel Sanctum (Authentication)
- PHP 8.2+

## Quick Start

```bash
cd D:\GSU\gsu-api

# Setup
composer install
php artisan migrate
php artisan db:seed
php artisan serve
```

Server akan berjalan di `http://localhost:8000`

## Fitur

- ✅ Admin Authentication
- ✅ Program Management (CRUD)
- ✅ Gallery Management (CRUD)
- ✅ Contact Form Handling
- ✅ API Routes dengan Token Authentication
- ✅ CORS configured untuk frontend

## Default Admin

```
Email: admin@sedekahumat.org
Password: admin123456
```

## Dokumentasi

Lihat `API_DOCS.md` untuk dokumentasi lengkap endpoint API.

## Folder Structure

```
app/
  ├── Models/           # Database models
  ├── Http/Controllers/ # API controllers
  └── Services/         # Business logic

database/
  ├── migrations/       # Table definitions
  └── seeders/         # Sample data

routes/
  └── api/v1.php       # API routes

config/
  └── sanctum.php      # Auth config
```

## Development

```bash
# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Create new model with migration
php artisan make:model ModelName -m

# Create controller
php artisan make:controller Api/ControllerName --api
```

## Testing API

Gunakan Postman atau tools lain untuk test endpoints di `API_DOCS.md`

Login terlebih dahulu untuk mendapat token, kemudian gunakan token di header untuk request yang memerlukan auth.
