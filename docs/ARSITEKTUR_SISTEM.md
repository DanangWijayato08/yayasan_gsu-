# 🏗️ ARSITEKTUR SISTEM - GSU Website

## Diagram Alur Umum

```
┌─────────────────────────────────────────────────────┐
│         PENGGUNA (Browser)                          │
│  ┌──────────────────┐    ┌──────────────────┐      │
│  │ Pengunjung Publik│    │ Administrator CMS│      │
│  └────────┬─────────┘    └────────┬─────────┘      │
└───────────┼──────────────────────┼─────────────────┘
            │                      │
            ▼                      ▼
┌─────────────────────────────────────────────────────┐
│  FRONTEND (Vue 3 + Vite SPA)                        │
│  - Rendering halaman                               │
│  - State management (Pinia)                         │
│  - HTTP client (Axios)                              │
│  - Auth interceptor & route guards                  │
└──────────────────┬──────────────────────────────────┘
                   │ REST API JSON
                   ▼
┌─────────────────────────────────────────────────────┐
│  SECURITY LAYER (Middleware)                       │
│  - CORS validation                                  │
│  - Rate limiting                                    │
│  - Authentication (Sanctum)                         │
│  - Authorization (RBAC)                             │
│  - Input sanitization                               │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│  BACKEND (Laravel 11 API)                           │
│  - Controllers (logika HTTP)                        │
│  - Services (business logic)                        │
│  - Models (data mapping)                            │
│  - Repositories (data access)                       │
└──────────────────┬──────────────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────────────┐
│  DATABASE (PostgreSQL 16)                           │
│  - Users, Programs, Gallery, Contacts              │
│  - Settings, Sessions, Tokens                       │
└─────────────────────────────────────────────────────┘
```

## Layer Arsitektur

### 1. FRONTEND LAYER (Presentasi)
**Tanggung Jawab**: Rendering UI dan interaksi pengguna

**Struktur**:
- `components/` - Reusable UI components
- `views/` - Page components (halaman)
- `stores/` - State management (Pinia)
- `router/` - Navigation & routing
- `api/` - API client services
- `styles/` - Global styles & tokens

**Flow Data**:
```
User Interaction 
  → Component Handler 
  → Pinia Store (state update) 
  → API Service Call 
  → HTTP Request
```

### 2. SECURITY LAYER (Perlindungan)
**Tanggung Jawab**: Validasi, autentikasi, dan otorisasi

**Middleware**:
- `AddSecurityHeaders` - Security headers (8 headers)
- `EnsureUserIsAdmin` - Role validation (RBAC)
- `VerifyCsrfToken` - CSRF protection
- Rate Limiter - Throttle requests

**Protection**:
- Input validation & sanitization
- XSS protection
- SSRF prevention
- SQL injection protection

### 3. BACKEND LAYER (Business Logic)
**Tanggung Jawab**: Proses bisnis dan logika aplikasi

**Komponen**:
```
Request 
  ↓
Route (API Endpoint)
  ↓
Middleware (Auth, Rate Limit)
  ↓
Controller (Request handler)
  ↓
Service (Business logic)
  ↓
Repository (Data access)
  ↓
Model (Data mapping)
  ↓
Database Query
  ↓
Response JSON
```

**Pattern**: 
- Controller: HTTP handlers
- Service: Business logic
- Repository: Data operations
- Model: Eloquent ORM

### 4. DATA LAYER (Penyimpanan)
**Database**: PostgreSQL 16 (Docker)

**Tabel Utama**:
- `users` - Admin & user accounts
- `programs` - Program donasi/wakaf
- `galleries` - Dokumentasi kegiatan
- `contacts` - Pesan dari formulir
- `settings` - Konfigurasi situs
- `sessions` - Session data
- `personal_access_tokens` - API tokens

## Alur Data

### Flow Publik (GET /api/public/home)
```
Browser Request
  ↓
Frontend (axios)
  ↓
AddSecurityHeaders Middleware
  ↓
ContentController@home
  ↓
Service (get cached data)
  ↓
Query DB (jika cache miss)
  ↓
Response JSON
  ↓
Frontend (Pinia store update)
  ↓
Component Re-render
```

### Flow Admin (PUT /api/admin/site-settings)
```
Admin Form Submit
  ↓
Frontend (API service)
  ↓
AddSecurityHeaders + Auth Middleware
  ↓
Rate Limiter check
  ↓
EnsureUserIsAdmin validation
  ↓
SingletonController@update
  ↓
Input Sanitizer service
  ↓
Validation rules
  ↓
Save to DB
  ↓
Cache invalidation
  ↓
Response + Toast notification
```

## Keamanan Multi-Layer

```
LAYER 1: Transport
└─ HTTPS enforced
└─ Security headers

LAYER 2: Network
└─ CORS whitelist
└─ Rate limiting
└─ Firewall rules

LAYER 3: Application
└─ Input validation
└─ Input sanitization
└─ Output encoding

LAYER 4: Authentication
└─ Bearer token (Sanctum)
└─ Token expiration (8h)
└─ Secure cookie (httpOnly)

LAYER 5: Authorization
└─ RBAC (admin role)
└─ Resource ownership
└─ Action permissions
```

## Performance Optimization

### Frontend
- Code splitting (lazy loading)
- Component reusability
- Pinia store caching
- Local storage optimization

### Backend
- Database query optimization
- Eager loading (prevent N+1)
- Cache layer (DB cache)
- Rate limiting (prevent abuse)

### Database
- Indexes on frequently queried columns
- Connection pooling
- Query optimization
- Regular maintenance

## Environment Configuration

### Development
```
APP_DEBUG=false        (disable stack traces)
APP_ENV=local
LOG_LEVEL=debug
CACHE=database
```

### Production
```
APP_DEBUG=false        (CRITICAL!)
APP_ENV=production
LOG_LEVEL=warning
CACHE=redis            (recommended)
SESSION_SECURE=true
SESSION_HTTP_ONLY=true
FORCE_HTTPS=true
```

---

**Version**: 1.0.0  
**Updated**: 2026-10-27
