# System Architecture - GSU Website

## Overview

GSU Website adalah aplikasi full-stack untuk mengelola website dan admin dashboard Gerakan Sedekah Umat.

```
┌─────────────────┐                ┌─────────────────┐
│   Browser       │                │   Admin Panel   │
│  (Public Site)  │────────────────│   (Dashboard)   │
└────────┬────────┘                └────────┬────────┘
         │                                  │
         └──────────────┬───────────────────┘
                        │
                   HTTP/HTTPS
                        │
         ┌──────────────▼───────────────┐
         │   Nginx/Web Server           │
         │  (Reverse Proxy)             │
         └──────────────┬───────────────┘
                        │
         ┌──────────────▼───────────────────────┐
         │     Frontend Layer                   │
         │  ┌────────────────────────────────┐  │
         │  │  Vue 3 + Vite                  │  │
         │  │  - Pinia Store                 │  │
         │  │  - Vue Router                  │  │
         │  │  - Axios Client                │  │
         │  └────────────────┬───────────────┘  │
         └───────────────────┼──────────────────┘
                             │
                    API Calls (JSON)
                             │
         ┌───────────────────▼──────────────────┐
         │     Backend Layer                    │
         │  ┌────────────────────────────────┐  │
         │  │  Laravel 13 API                │  │
         │  │  - Sanctum Authentication      │  │
         │  │  - RESTful Controllers         │  │
         │  │  - Request Validation          │  │
         │  │  - CORS Middleware             │  │
         │  └────────────────┬───────────────┘  │
         └───────────────────┼──────────────────┘
                             │
                    Database Queries
                             │
         ┌───────────────────▼──────────────────┐
         │     Data Layer                       │
         │  ┌────────────────────────────────┐  │
         │  │  MySQL 8.0                     │  │
         │  │  - Users Table                 │  │
         │  │  - Programs Table              │  │
         │  │  - Galleries Table             │  │
         │  │  - Contacts Table              │  │
         │  │  - Personal Access Tokens      │  │
         │  └────────────────────────────────┘  │
         └────────────────────────────────────┘
```

## Architecture Layers

### 1. Presentation Layer (Frontend)

**Technology:** Vue 3 + Vite

**Components:**
- **Pages**: HomeView, ProgramView, GaleriView, KontakView, DonasiView, TentangView, AdminDashboardView
- **Components**: Organized in `common/` (reusable) dan `sections/` (page sections)
- **State Management**: Pinia store for auth management
- **HTTP Client**: Axios with automatic token injection

**Key Features:**
- Responsive design
- Client-side routing dengan Vue Router
- Real-time state management
- Auto token refresh

### 2. Business Logic Layer (Backend)

**Technology:** Laravel 13

**Controllers:**
```
App\Http\Controllers\Api\
├── AuthController        # Login, logout, me
├── ProgramController     # CRUD programs
├── GalleryController     # CRUD galleries
└── ContactController     # CRUD contacts
```

**Models:**
```
App\Models\
├── User                  # Admin users
├── Program              # Programs data
├── Gallery              # Gallery images
└── Contact              # Contact messages
```

**Services:**
- Authentication via Sanctum tokens
- Request validation
- Error handling
- CORS middleware

### 3. Data Access Layer (Database)

**Database:** MySQL 8.0

**Tables:**

```sql
users
├── id (PK)
├── name
├── email (UNIQUE)
├── password (hashed)
└── timestamps

programs
├── id (PK)
├── title
├── description
├── category
├── collected (integer)
├── target (integer)
├── image (URL)
├── active (boolean)
└── timestamps

galleries
├── id (PK)
├── title
├── description
├── image (URL)
├── category
├── active (boolean)
└── timestamps

contacts
├── id (PK)
├── name
├── email
├── phone
├── message
├── status (unread/read)
└── timestamps

personal_access_tokens
├── id (PK)
├── tokenable_type
├── tokenable_id
├── name
├── token (hashed)
├── abilities
└── last_used_at
```

## API Architecture

### REST Endpoints

**Public Endpoints:**
```
GET    /api/programs              # Get all programs
GET    /api/programs/{id}         # Get program detail
GET    /api/galleries             # Get all galleries
GET    /api/galleries/{id}        # Get gallery detail
POST   /api/contacts              # Submit contact form
```

**Authenticated Endpoints:**
```
POST   /api/login                 # Admin login
POST   /api/logout                # Admin logout
GET    /api/me                    # Get current user

POST   /api/programs              # Create program
PUT    /api/programs/{id}         # Update program
DELETE /api/programs/{id}         # Delete program

POST   /api/galleries             # Create gallery
PUT    /api/galleries/{id}        # Update gallery
DELETE /api/galleries/{id}        # Delete gallery

GET    /api/contacts              # Get all contacts
GET    /api/contacts/{id}         # Get contact detail
PUT    /api/contacts/{id}         # Update contact status
DELETE /api/contacts/{id}         # Delete contact
```

### Authentication Flow

```
1. User submits credentials
   └─→ POST /api/login (email, password)

2. Backend validates
   └─→ Hash password check
   └─→ Generate Sanctum token

3. Response dengan token
   └─→ Frontend stores in localStorage

4. Subsequent requests
   └─→ Header: Authorization: Bearer {token}
   └─→ Middleware verifies token
   └─→ Process request if valid

5. Token refresh
   └─→ Auto injected oleh Axios interceptor
```

## Security Architecture

### Authentication
- **Type**: Token-based (Sanctum)
- **Storage**: localStorage (client-side)
- **Transmission**: Bearer token in Authorization header
- **Expiration**: No expiration (configurable)

### Authorization
- **Admin Only**: Create/Update/Delete operations
- **Public Access**: Read operations (programs, galleries)
- **Form Submission**: Public (contact form)

### CORS
- **Allowed Origins**: localhost:5173, 127.0.0.1:5173
- **Allowed Methods**: GET, POST, PUT, DELETE, OPTIONS
- **Credentials**: Include

### Input Validation
- Client-side: Vue form validation
- Server-side: Laravel validation rules
- Sanitization: HTML escape, SQL injection prevention

## Deployment Architecture

### Development
```
Local Machine
├── Frontend (npm run dev)  → http://localhost:5173
├── Backend (artisan serve) → http://localhost:8000
└── MySQL (Laragon)         → localhost:3306
```

### Production (Docker)
```
Docker Container
├── Frontend (built SPA)  → Nginx:80
├── Backend (Laravel)     → PHP:9000
└── MySQL (Database)      → MySQL:3306

Docker Compose orchestrates all services
```

## File Organization

### Frontend Structure
```
frontend/
├── src/
│   ├── components/
│   │   ├── common/         # Reusable (Navbar, Footer, etc)
│   │   └── sections/       # Full-width sections
│   ├── pages/
│   ├── services/api/       # API client
│   ├── stores/             # Pinia state
│   ├── composables/        # Vue composables
│   ├── utils/              # Utilities
│   └── assets/             # Static files
└── vite.config.js
```

### Backend Structure
```
backend/
├── app/
│   ├── Models/
│   ├── Http/Controllers/
│   └── Services/
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   └── api/
├── config/
└── storage/
```

## Data Flow

### Create Program Flow
```
1. Admin Dashboard
   └─→ Submit form

2. Frontend (Vue)
   └─→ Validate input
   └─→ POST /api/programs

3. Backend (Laravel)
   └─→ Verify authentication
   └─→ Validate request data
   └─→ Create Program model
   └─→ Save to database
   └─→ Return response

4. Frontend
   └─→ Update Pinia store
   └─→ Show success message
   └─→ Refresh list
```

### Get Programs Flow
```
1. Frontend loads
   └─→ GET /api/programs

2. Backend
   └─→ Query database
   └─→ Filter active programs
   └─→ Return JSON

3. Frontend
   └─→ Render components
   └─→ Cache in store
```

## Performance Considerations

### Frontend
- Code splitting dengan dynamic imports
- Lazy loading components
- Caching dengan localStorage
- Minification & optimization via Vite

### Backend
- Database query optimization
- Eager loading dengan Eloquent relationships
- Caching layer (optional)
- Request rate limiting

### Database
- Proper indexing on foreign keys
- Soft deletes for data preservation
- Query optimization

## Scalability

### Current State
- Single server deployment
- Local database
- Suitable for 1000+ concurrent users

### Future Improvements
- Load balancing
- Database replication
- Caching layer (Redis)
- CDN for static assets
- Message queue (for async tasks)

---

**Architecture Version:** 1.0  
**Last Updated:** 2026-07-04
