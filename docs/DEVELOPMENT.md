# Development Guide - GSU Website

Panduan untuk developers yang ingin berkontribusi pada project GSU Website.

## Development Environment Setup

### Recommended Tools

- **Editor:** VS Code (https://code.visualstudio.com/)
- **Terminal:** PowerShell, Git Bash, atau WSL (Windows)
- **API Testing:** Postman (https://www.postman.com/) atau Insomnia
- **Version Control:** Git
- **Database Tool:** DBeaver atau MySQL Workbench

### VS Code Extensions (Recommended)

```
Frontend:
- Vetur
- ES7+ React/Redux/React-Native snippets
- Prettier - Code formatter
- ESLint
- Thunder Client (untuk API testing)

Backend:
- Laravel Extension Pack
- PHP Intelephense
- PHP DocBlocker
- Thunder Client
```

## Folder Structure & Conventions

### Frontend (`frontend/`)

```
src/
├── components/
│   ├── common/
│   │   ├── Navbar.vue           # Navigation component
│   │   ├── Footer.vue           # Footer component
│   │   ├── Counter.vue          # Statistics counter
│   │   ├── Stats.vue            # Statistics section
│   │   ├── ProgressBar.vue      # Progress visualization
│   │   └── Testimonial.vue      # Testimonial carousel
│   └── sections/
│       ├── Hero.vue             # Hero section
│       ├── About.vue            # About section
│       ├── ProgramSection.vue   # Programs showcase
│       ├── ProgramCard.vue      # Individual program card
│       ├── Gallery.vue          # Gallery section
│       ├── DonationSection.vue  # Donation form
│       └── Contact.vue          # Contact form
├── pages/
│   ├── admin/                   # Admin pages (to implement)
│   ├── AdminDashboardView.vue
│   ├── HomeView.vue
│   ├── ProgramView.vue
│   ├── GaleriView.vue
│   ├── KontakView.vue
│   ├── DonasiView.vue
│   └── TentangView.vue
├── services/
│   └── api/
│       └── client.js            # Axios instance & API calls
├── stores/
│   └── auth.js                  # Pinia auth store
├── composables/
│   └── useScrollReveal.js       # Scroll animation composable
├── utils/
│   └── constants.js             # App constants
├── assets/
│   ├── main.css                 # Global styles
│   ├── images/                  # Image files
│   └── icons/                   # Icon files
├── App.vue                      # Root component
└── main.js                      # Entry point
```

### Backend (`backend/`)

```
app/
├── Models/
│   ├── User.php
│   ├── Program.php
│   ├── Gallery.php
│   └── Contact.php
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       ├── AuthController.php
│   │       ├── ProgramController.php
│   │       ├── GalleryController.php
│   │       └── ContactController.php
│   ├── Requests/                # Form requests (if needed)
│   └── Resources/               # API resources (if needed)
└── Services/                    # Business logic (if needed)

database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php
│   ├── 2026_07_04_044406_create_programs_table.php
│   ├── 2026_07_04_044407_create_galleries_table.php
│   ├── 2026_07_04_044407_create_contacts_table.php
│   └── 2026_07_04_044757_create_personal_access_tokens_table.php
└── seeders/
    ├── DatabaseSeeder.php
    ├── AdminSeeder.php
    └── ProgramSeeder.php

routes/
├── api/
│   └── v1.php                   # All API routes
└── web.php

config/
├── sanctum.php
├── database.php
└── app.php
```

## Naming Conventions

### Frontend (Vue/JavaScript)

```javascript
// Components: PascalCase
<template>
  <div class="component-name">
    <ProgramCard :program="program" />
  </div>
</template>

// CSS Classes: kebab-case
<style scoped>
  .hero__section { }
  .hero__title { }
  .hero__content { }
</style>

// Variables/Functions: camelCase
const currentUser = ref(null)
const fetchPrograms = async () => { }

// Constants: UPPER_CASE
const MAX_PROGRAMS = 10
const API_URL = 'http://localhost:8000/api'

// Props: camelCase
<script setup>
defineProps({
  programId: Number,
  isActive: Boolean,
})
</script>
```

### Backend (Laravel/PHP)

```php
// Classes: PascalCase
class ProgramController extends Controller { }

// Methods: camelCase
public function getAllPrograms() { }
public function createProgram() { }

// Variables: camelCase
$programList = [];
$userId = 1;

// Constants: UPPER_CASE
const MAX_PROGRAMS = 10;
```

## Git Workflow

### Branching Strategy

```bash
# Feature development
git checkout -b feature/feature-name

# Bug fixes
git checkout -b fix/bug-name

# Hotfix for production
git checkout -b hotfix/issue-name

# Release branch
git checkout -b release/v1.1.0
```

### Commit Messages

```
Format: <type>(<scope>): <subject>

Types:
- feat: A new feature
- fix: A bug fix
- docs: Documentation only changes
- style: Changes that don't affect code meaning
- refactor: Code change without feature/fix
- perf: Code change that improves performance
- test: Adding missing or correcting tests

Examples:
feat(auth): add login functionality
fix(api): correct program listing endpoint
docs(setup): update installation guide
refactor(components): reorganize program card
```

### Pull Request Process

```bash
1. Create feature branch
   git checkout -b feature/my-feature

2. Make changes and commit
   git add .
   git commit -m "feat(scope): description"

3. Push to remote
   git push origin feature/my-feature

4. Create Pull Request on GitHub
   - Add description
   - Link related issues
   - Request review

5. Code review & merge
   - Address feedback
   - Squash commits if needed
   - Merge to main
```

## Development Workflow

### Frontend Development

```bash
# Start dev server
cd frontend
npm run dev

# Build for production
npm run build

# Preview production build
npm run preview

# Lint and format
npm run lint
npm run format
```

### Backend Development

```bash
# Start dev server
cd backend
php artisan serve

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Clear cache
php artisan cache:clear
php artisan config:cache

# Run tests (if implemented)
php artisan test
```

## Database Development

### Creating New Migration

```bash
cd backend

# Create migration
php artisan make:migration create_table_name

# Modify migration file, then run
php artisan migrate

# Rollback
php artisan migrate:rollback
```

### Creating New Seeder

```bash
cd backend

# Create seeder
php artisan make:seeder NameSeeder

# Run seeder
php artisan db:seed --class=NameSeeder
```

## API Development

### Adding New Endpoint

1. **Create Controller**
   ```bash
   php artisan make:controller Api/NameController --api
   ```

2. **Add Routes** in `routes/api/v1.php`
   ```php
   Route::apiResource('names', NameController::class);
   ```

3. **Implement Methods**
   ```php
   public function index() { }
   public function store(Request $request) { }
   public function show($id) { }
   public function update(Request $request, $id) { }
   public function destroy($id) { }
   ```

4. **Test with Postman/Insomnia**

## Frontend Development

### Adding New Page

1. **Create Vue Component** in `src/pages/`
   ```vue
   <template>
     <div class="page">
       <!-- content -->
     </div>
   </template>

   <script setup>
   import { ref } from 'vue'
   </script>

   <style scoped>
   </style>
   ```

2. **Add Route** in `src/router/index.js`
   ```javascript
   {
     path: '/new-page',
     name: 'newPage',
     component: () => import('../pages/NewPageView.vue'),
     meta: { title: 'New Page' }
   }
   ```

3. **Add Navigation Link** in Navbar component

### Adding New Component

1. **Create Vue Component** in `src/components/common/` atau `src/components/sections/`

2. **Import and Use** in page/component
   ```vue
   <script setup>
   import MyComponent from '@/components/common/MyComponent.vue'
   </script>
   ```

## Testing API

### Using Postman

1. **Login**
   ```
   POST http://localhost:8000/api/login
   Body: { "email": "admin@sedekahumat.org", "password": "admin123456" }
   ```

2. **Copy token** dari response

3. **Set Authorization Header**
   - Type: Bearer Token
   - Token: (paste token dari step 2)

4. **Test endpoints**
   ```
   GET http://localhost:8000/api/programs
   POST http://localhost:8000/api/programs
   PUT http://localhost:8000/api/programs/1
   DELETE http://localhost:8000/api/programs/1
   ```

## Debugging

### Frontend

```javascript
// Console logging
console.log('value:', value)
console.error('error:', error)

// Vue DevTools (browser extension)
// Inspect components, state, routes

// Network tab
// Monitor API calls and responses

// VS Code Debugger
// Add breakpoints and step through code
```

### Backend

```php
// Log messages
\Log::info('Message:', $data);
\Log::error('Error:', $error);

// Dump and die
dd($data);

// Output
dump($data);

// Laravel Debugbar
// Install: composer require barryvdh/laravel-debugbar
```

## Common Tasks

### Adding New Database Table

```bash
# 1. Create migration
php artisan make:migration create_table_name

# 2. Edit migration file with fields

# 3. Run migration
php artisan migrate

# 4. Create Model
php artisan make:model TableName

# 5. Add Model properties and relationships
```

### Adding API Endpoint

```bash
# 1. Create controller
php artisan make:controller Api/NameController --api

# 2. Add methods (index, store, show, update, destroy)

# 3. Add routes in routes/api/v1.php

# 4. Test with Postman
```

### Adding Frontend Page

```bash
# 1. Create .vue file in src/pages/

# 2. Add route in src/router/index.js

# 3. Add navigation link in Navbar

# 4. Build and test
```

## Performance Tips

### Frontend
- Use lazy loading for images
- Code split with dynamic imports
- Cache API responses
- Minimize component re-renders
- Use computed properties effectively

### Backend
- Use eager loading (Eloquent with())
- Add database indexes
- Cache query results
- Implement pagination
- Use query optimization

## Security Best Practices

- Never commit .env files
- Always validate user input
- Use parameterized queries
- Sanitize HTML output
- Implement CSRF protection
- Use HTTPS in production
- Keep dependencies updated

## Resources

- Vue 3 Docs: https://vuejs.org/
- Laravel Docs: https://laravel.com/docs/
- Pinia Docs: https://pinia.vuejs.org/
- Axios Docs: https://axios-http.com/
- Sanctum Docs: https://laravel.com/docs/sanctum

---

**Happy Coding! 🚀**
