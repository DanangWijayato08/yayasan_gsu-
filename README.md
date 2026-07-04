# GSU Website - Gerakan Sedekah Umat

Platform digital transparan untuk gerakan sedekah umat dengan website responsif dan admin dashboard.

## 📁 Project Structure

```
GSU/
│
├── frontend/                    # Vue 3 + Vite + Pinia
│   ├── src/
│   │   ├── components/         # Reusable UI components
│   │   ├── pages/              # Page views
│   │   ├── services/api/       # API client
│   │   ├── stores/             # Pinia state management
│   │   ├── composables/        # Vue composables
│   │   ├── utils/              # Utilities & constants
│   │   ├── assets/             # Static files
│   │   ├── App.vue
│   │   └── main.js
│   ├── package.json
│   ├── vite.config.js
│   └── README.md
│
├── backend/                     # Laravel 13 + MySQL
│   ├── app/
│   │   ├── Models/            # Database models
│   │   └── Http/Controllers/  # API controllers
│   ├── database/
│   │   ├── migrations/        # Table definitions
│   │   └── seeders/           # Sample data
│   ├── routes/api/            # API routes
│   ├── config/                # Configuration
│   ├── .env
│   ├── composer.json
│   ├── artisan
│   ├── API_DOCS.md
│   └── README.md
│
├── database/                    # Database backups & schema
│   ├── backups/
│   ├── schema/
│   └── sql/
│
├── docs/                        # Project documentation
│   ├── ARCHITECTURE.md         # System design
│   ├── API.md                  # API reference
│   ├── SETUP.md                # Setup guide
│   ├── DEVELOPMENT.md          # Development guide
│   ├── DEPLOYMENT.md           # Deployment guide
│   └── CONTRIBUTING.md         # Contribution guidelines
│
├── ui-design/                   # Design assets
│   ├── mockups/
│   ├── wireframes/
│   ├── logos/
│   ├── color-palette/
│   └── fonts/
│
├── deployment/                  # Deployment configuration
│   ├── docker/
│   │   ├── Dockerfile.frontend
│   │   ├── Dockerfile.backend
│   │   └── docker-compose.yml
│   ├── nginx/
│   │   ├── nginx.conf
│   │   └── default.conf
│   └── scripts/
│       └── deploy.sh
│
├── .gitignore
├── README.md
├── LICENSE
└── CHANGELOG.md
```

## 🚀 Quick Start

### Prerequisites
- Node.js 16+ (Frontend)
- PHP 8.2+ (Backend)
- MySQL 8.0+ (Database)
- Composer (PHP dependency manager)

### Frontend Setup

```bash
cd frontend
npm install --legacy-peer-deps
npm run dev
# Frontend: http://localhost:5173
```

### Backend Setup

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
# Backend: http://localhost:8000
```

## 📋 Features

### Frontend
- ✅ Responsive website dengan Vue 3
- ✅ Home page dengan berbagai sections
- ✅ Program showcase
- ✅ Gallery
- ✅ Contact form
- ✅ Donation page
- ✅ Admin dashboard UI
- ✅ Pinia state management
- ✅ Axios API client

### Backend
- ✅ REST API dengan Laravel
- ✅ Authentication dengan Sanctum
- ✅ Program management
- ✅ Gallery management
- ✅ Contact form handling
- ✅ Database migrations
- ✅ Sample data seeders
- ✅ CORS configuration

## 🔑 Default Credentials

```
Email: admin@sedekahumat.org
Password: admin123456
```

## 📚 Documentation

- **Setup Guide:** `docs/SETUP.md`
- **Architecture:** `docs/ARCHITECTURE.md`
- **API Reference:** `docs/API.md` or `backend/API_DOCS.md`
- **Development:** `docs/DEVELOPMENT.md`
- **Deployment:** `docs/DEPLOYMENT.md`
- **Frontend README:** `frontend/README.md`
- **Backend README:** `backend/README.md`

## 🏗️ Technology Stack

| Layer | Technology |
|-------|-----------|
| Frontend | Vue 3, Vite, Pinia, Axios |
| Backend | Laravel 13, MySQL, Sanctum |
| Authentication | Token-based (Bearer) |
| Database | MySQL 8.0 |
| API | RESTful JSON API |
| DevOps | Docker, Nginx |

## 🔄 API Integration

Frontend connects to backend via:
- Base URL: `http://localhost:8000/api`
- Authentication: Bearer token (stored in localStorage)
- Automatic token injection on authenticated requests

## 📦 Database

**Name:** `gsu_api`
**Tables:**
- users
- programs
- galleries
- contacts
- personal_access_tokens

## 🐳 Docker Setup (Optional)

```bash
cd deployment
docker-compose up -d
```

## 📝 Git Workflow

```bash
# Clone repository
git clone <repo-url>
cd GSU

# Create feature branch
git checkout -b feature/feature-name

# Make changes and commit
git add .
git commit -m "feat: description"

# Push to remote
git push origin feature/feature-name

# Create Pull Request
```

## 📞 Support

- Frontend Issues: `frontend/README.md`
- Backend Issues: `backend/README.md`
- Documentation: `docs/`

## 📄 License

This project is licensed under the MIT License - see `LICENSE` file for details.

## 📅 Changelog

See `CHANGELOG.md` for version history and updates.

---

**Last Updated:** 2026-07-04  
**Status:** ✅ Production Ready
