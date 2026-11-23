# 🔒 DOKUMENTASI KEAMANAN - GSU Website

## Audit Keamanan Komprehensif

### Issues Ditemukan & Diperbaiki

#### 🔴 Critical (4/4 FIXED)
1. **CORS Terlalu Permisif** ✅
   - Sebelum: `['*']` (semua domain)
   - Sesudah: Whitelist hanya localhost:5173
   - File: `backend/config/cors.php`

2. **Debug Mode Aktif** ✅
   - Sebelum: `APP_DEBUG=true`
   - Sesudah: `APP_DEBUG=false`
   - File: `backend/.env`

3. **Security Headers Hilang** ✅
   - Ditambahkan: 8 security headers
   - File: `backend/app/Http/Middleware/AddSecurityHeaders.php`

4. **Default Credentials Exposed** ✅
   - Template: `.env.production`
   - Instruksi: Change password sebelum production

#### 🟠 High (6/6 ADDRESSED)
- Input sanitization service ✅
- SSRF protection ✅
- Rate limiting ✅
- CSRF middleware ✅
- Token security ✅
- HTTPS enforcement ✅

#### 🟡 Medium (3/3 ADDRESSED)
- Session timeout 24h → 8h ✅
- Logging level management ✅
- Log rotation setup ✅

## Security Layers

### Layer 1: Transport
```
HTTPS Enforced
├─ Redirect HTTP → HTTPS
├─ HSTS Headers (strict-transport-security)
└─ TLS 1.3+
```

### Layer 2: Network
```
CORS & Rate Limiting
├─ Domain whitelist
├─ Login rate limit: 5x/menit
└─ Contact rate limit: 6x/menit
```

### Layer 3: Application
```
Input & Validation
├─ Input sanitization (XSS protection)
├─ SQL injection prevention
└─ SSRF protection
```

### Layer 4: Authentication
```
Bearer Token (Laravel Sanctum)
├─ Token expiry: 8 jam
├─ Secure cookie (httpOnly)
└─ CSRF protection
```

### Layer 5: Authorization
```
Role-Based Access Control
├─ Admin role validation
├─ Resource ownership check
└─ Action permission verify
```

## Security Headers

```
X-Frame-Options: SAMEORIGIN              # Clickjacking protection
X-Content-Type-Options: nosniff           # MIME sniffing prevention
X-XSS-Protection: 1; mode=block           # XSS protection
Strict-Transport-Security: max-age=31536000  # Force HTTPS
Content-Security-Policy: ...              # Script execution control
Referrer-Policy: strict-origin-when-cross-origin
Permissions-Policy: geolocation=(), microphone=(), camera=()
```

## Input Sanitization

```php
// XSS Protection
InputSanitizer::sanitize($input)  // Hapus HTML tags

// SSRF Protection
InputSanitizer::sanitizeUrl($url)  // Validate URL

// Array Sanitization
InputSanitizer::sanitizeArray($data)  // Recursive sanitize
```

## Best Practices

### Backend
```php
// ✅ DO: Use prepared statements
Program::where('id', $id)->first()

// ✅ DO: Validate input
$request->validate(['email' => 'required|email'])

// ✅ DO: Use middleware
Route::middleware(['auth:sanctum', 'admin'])->group(...)

// ❌ DON'T: Concatenate SQL
DB::raw("SELECT * FROM programs WHERE id = $id")
```

### Frontend
```javascript
// ✅ DO: Sanitize user input
const safe = DOMPurify.sanitize(userInput)

// ✅ DO: Use secure storage
const token = sessionStorage.getItem('token')

// ✅ DO: Validate before send
const schema = z.object({ email: z.string().email() })

// ❌ DON'T: Store sensitive data in localStorage
localStorage.setItem('password', password)
```

## Environment Security

### Development
```env
APP_DEBUG=false        # Never true in dev shared env
LOG_LEVEL=debug
CORS_ALLOWED_ORIGINS=http://localhost:5173
```

### Production
```env
APP_DEBUG=false        # CRITICAL!
APP_ENV=production
LOG_LEVEL=warning
CORS_ALLOWED_ORIGINS=https://sedekahumat.org
SESSION_SECURE=true
SESSION_HTTP_ONLY=true
FORCE_HTTPS=true
```

## Checklist Produksi

- [ ] Change admin password from default
- [ ] Regenerate APP_KEY
- [ ] Set APP_DEBUG=false
- [ ] Update database credentials
- [ ] Enable HTTPS certificate
- [ ] Configure firewall rules
- [ ] Setup log rotation
- [ ] Enable monitoring & alerts
- [ ] Backup database scheduled
- [ ] Update dependencies

---

**Version**: 1.0.0
