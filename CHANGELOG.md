# Changelog - GSU Website

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-07-04

### Added

#### Frontend
- ✅ Vue 3 + Vite project setup
- ✅ Responsive website with 7 main pages:
  - Home (Beranda)
  - About (Tentang)
  - Programs (Program)
  - Donation (Donasi)
  - Gallery (Galeri)
  - Contact (Kontak)
  - Admin Dashboard
- ✅ Component organization:
  - `components/common/` - Reusable components (Navbar, Footer, Cards)
  - `components/sections/` - Page sections (Hero, About, Programs, Gallery, etc)
- ✅ Pinia state management for authentication
- ✅ Axios API client with automatic token injection
- ✅ Vue Router for client-side navigation
- ✅ Scroll reveal animations with v-reveal directive
- ✅ Build configuration with Vite
- ✅ Built-in documentation (STRUCTURE.md)

#### Backend
- ✅ Laravel 13 API setup
- ✅ MySQL database integration
- ✅ Database migrations for core tables:
  - Users
  - Programs
  - Galleries
  - Contacts
  - Personal Access Tokens (Sanctum)
- ✅ RESTful API controllers:
  - AuthController (Login, Logout, Me)
  - ProgramController (CRUD operations)
  - GalleryController (CRUD operations)
  - ContactController (CRUD operations)
- ✅ Laravel Sanctum for token-based authentication
- ✅ Database seeders:
  - AdminSeeder (Default admin user)
  - ProgramSeeder (Sample programs)
- ✅ CORS configuration for frontend
- ✅ API documentation (API_DOCS.md)
- ✅ Input validation on all endpoints

#### Project Structure
- ✅ Reorganized into clean folder structure:
  - `frontend/` - Vue 3 + Vite
  - `backend/` - Laravel API
  - `database/` - Database backups & schema
  - `docs/` - Project documentation
  - `ui-design/` - Design assets
  - `deployment/` - Docker & Nginx configs
- ✅ Root level documentation:
  - README.md - Project overview
  - .gitignore - Git configuration
  - LICENSE - MIT License
  - CHANGELOG.md - This file

#### Documentation
- ✅ README.md - Complete project overview
- ✅ SETUP.md - Installation & setup guide
- ✅ ARCHITECTURE.md - System design & architecture
- ✅ .gitignore - Git ignore rules
- ✅ LICENSE - MIT License

### Changed

#### Frontend
- Removed News/Berita feature completely:
  - Deleted `News.vue` component
  - Deleted `BeritaView.vue` page
  - Removed `/berita` route
  - Removed berita link from Navbar
  - Removed berita link from Footer
  - Removed berita activity from admin dashboard
- Reorganized components structure for better maintainability
- Updated all component imports to reflect new folder structure

#### Backend
- Configured database to use MySQL instead of SQLite
- Set up Sanctum for token authentication
- Added proper CORS configuration

### Fixed

- ✅ Build errors from missing component imports
- ✅ CORS configuration for frontend-backend communication
- ✅ Component import paths after reorganization
- ✅ Berita feature references in navigation

### Deprecated

- News/Berita feature (removed in v1.0.0)

### Security

- ✅ Implemented token-based authentication
- ✅ Added input validation on all API endpoints
- ✅ Configured CORS with specific allowed origins
- ✅ Hashed password storage in database
- ✅ Environment variable protection (.env)

### Removed

- Berita (News) feature:
  - Removed route `/berita`
  - Removed `News.vue` component
  - Removed `BeritaView.vue` page
  - Removed menu items in Navbar, Footer, Admin Dashboard

## [0.1.0] - 2026-07-04 (Initial Setup)

### Added (Initial Setup)

#### Frontend
- Vue 3 project created with Vite
- Initial page structure
- Basic styling

#### Backend
- Laravel 13 project created
- Initial migrations
- Database setup

---

## Planned Features (Backlog)

### v1.1.0
- [ ] Admin dashboard functionality
- [ ] Program editing interface
- [ ] Gallery management UI
- [ ] Contact messages view
- [ ] Dashboard analytics

### v1.2.0
- [ ] File upload for images
- [ ] Image optimization
- [ ] Caching system
- [ ] Email notifications

### v2.0.0
- [ ] Docker deployment
- [ ] CI/CD pipeline
- [ ] Advanced analytics
- [ ] Multi-language support
- [ ] Mobile app (React Native)

---

## How to Contribute

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## Versioning

This project uses [Semantic Versioning](https://semver.org/):
- MAJOR version when you make incompatible API changes
- MINOR version when you add functionality in a backwards compatible manner
- PATCH version when you make backwards compatible bug fixes

---

## Support

For support, open an issue on GitHub or contact the team.

---

**Last Updated:** 2026-07-04  
**Version:** 1.0.0  
**Status:** ✅ Production Ready
