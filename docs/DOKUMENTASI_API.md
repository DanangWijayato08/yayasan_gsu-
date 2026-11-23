# 📡 DOKUMENTASI API - GSU Website

## Base URL
```
http://127.0.0.1:8000/api
```

## Authentication
```
Header: Authorization: Bearer <token>
```

---

## Public Endpoints (Tanpa Auth)

### Homepage
```
GET /public/home
Response: Hero data, programs, gallery preview, settings
```

### Programs
```
GET /programs                    # Semua program aktif
GET /programs/featured           # Program unggulan
GET /programs/{slug}             # Detail by slug
```

### Gallery
```
GET /galleries                   # Semua galeri
GET /galleries/{id}              # Detail galeri
```

### Contact
```
POST /contact-messages
Body: {name, email, phone, subject, message}
Rate limit: 6x/menit
```

---

## Admin Endpoints (Memerlukan Auth + Admin Role)

### Authentication
```
POST /login
Body: {email, password}
Returns: {token, user}

POST /logout
Requires: Bearer token
```

### Dashboard
```
GET /admin/dashboard
Returns: {programs_count, galleries_count, messages}
```

### Programs Management
```
GET /admin/programs                 # List with pagination
POST /admin/programs                # Create new
PUT /admin/programs/{id}            # Update
DELETE /admin/programs/{id}         # Delete
```

### Gallery Management
```
GET /admin/galleries                # List
POST /admin/galleries               # Create
PUT /admin/galleries/{id}           # Update
DELETE /admin/galleries/{id}        # Delete
```

### Upload
```
POST /admin/upload
Content-Type: multipart/form-data
Fields: image (file), folder (string)
Max size: 5MB
Allowed: jpg, jpeg, png, webp, svg
```

### Settings
```
GET /admin/site-settings            # Get settings
PUT /admin/site-settings            # Update settings

GET /admin/about                    # Get about page
PUT /admin/about                    # Update

GET /admin/donation-settings        # Get donation
PUT /admin/donation-settings        # Update

GET /admin/contact-settings         # Get contact
PUT /admin/contact-settings         # Update

GET /admin/footer-settings          # Get footer
PUT /admin/footer-settings          # Update
```

### Message Management
```
GET /admin/contact-messages         # List messages
PUT /admin/contact-messages/{id}    # Change status
DELETE /admin/contact-messages/{id} # Delete
```

---

## Response Format

### Success (2xx)
```json
{
  "data": {...},
  "message": "Success"
}
```

### Error (4xx/5xx)
```json
{
  "message": "Error description",
  "errors": {...}
}
```

## Status Codes

| Code | Meaning |
|------|---------|
| 200 | OK - Success |
| 201 | Created - New resource |
| 400 | Bad Request - Invalid input |
| 401 | Unauthorized - No token |
| 403 | Forbidden - No permission |
| 404 | Not Found - Resource not exist |
| 422 | Validation Failed |
| 429 | Too Many Requests |
| 500 | Server Error |

## Rate Limits

```
Login: 5 attempts/minute
Contact: 6 messages/minute
API: 60 requests/minute
```

---

**Version**: 1.0.0
