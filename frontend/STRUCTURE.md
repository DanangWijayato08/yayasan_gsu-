# Struktur Project GSU Website

## Organisasi Direktori

```
src/
├── assets/              # Aset statis (CSS, images, icons)
│   ├── main.css        # Global stylesheet
│   ├── images/         # Gambar project
│   └── icons/          # Icon assets
├── components/         # Vue components
│   ├── common/         # Reusable components (Navbar, Footer, Cards, dll)
│   └── sections/       # Section components (Hero, About, Programs, dll)
├── composables/        # Vue composables (reusable logic)
├── services/           # API services dan external integrations
├── stores/             # State management (jika diperlukan)
├── styles/             # Global styles dan variables
├── utils/              # Utility functions dan constants
├── views/              # Page components
├── router/             # Vue Router configuration
├── App.vue             # Root component
└── main.js             # Entry point
```

## Naming Conventions

- **Components**: PascalCase (e.g., `Hero.vue`, `ProgramCard.vue`)
- **Views**: PascalCase dengan suffix `View` (e.g., `HomeView.vue`, `ProgramView.vue`)
- **Functions/Constants**: camelCase (e.g., `useScrollReveal`, `SITE_NAME`)
- **Files**: kebab-case atau PascalCase sesuai jenisnya

## Component Categories

### common/
Komponen yang dapat digunakan di berbagai tempat:
- Navbar.vue
- Footer.vue
- Stats.vue
- Counter.vue
- ProgressBar.vue
- ProgramCard.vue
- Testimonial.vue

### sections/
Komponen full-width section untuk halaman:
- Hero.vue
- About.vue
- ProgramSection.vue
- Gallery.vue
- DonationSection.vue
- Contact.vue

## Routes

- `/` - Home
- `/tentang` - Tentang Yayasan
- `/program` - Program
- `/donasi` - Donasi
- `/galeri` - Galeri
- `/kontak` - Kontak
- `/admin` - Admin Dashboard

## Removed Features

- **Berita (News)** - Fitur berita telah dihapus dari:
  - Routes (router/index.js)
  - Components (News.vue)
  - Views (BeritaView.vue)
  - Admin Menu

## Development Guidelines

1. Gunakan `<script setup>` syntax untuk components baru
2. Gunakan CSS scoped untuk styling components
3. Gunakan directive `v-reveal` untuk animasi scroll
4. Import dari paths relatif yang konsisten
5. Hindari circular dependencies
