# Struktur Direktori Frontend

Berikut adalah struktur organisasi folder pada aplikasi antarmuka web Yayasan GSU:

```text
frontend/src/
├── assets/        # Token desain global (main.css)
├── components/    # Komponen UI modular
│   ├── admin/     # Kerangka antarmuka panel CMS (AdminShell.vue)
│   ├── common/    # Komponen umum (Navbar, Footer, Stats, ProgressBar, Testimonial, Counter)
│   └── sections/  # Seksi konten (Hero, About, ProgramSection, Gallery, DonationSection, Contact)
├── composables/   # Reusable Vue composables (useScrollReveal)
├── router/        # Konfigurasi rute Vue Router & navigation guards
├── services/      # Abstraksi klien Axios & integrasi API backend
├── stores/        # State management Pinia (auth store)
├── utils/         # Konstanta dan helper utilitas
├── views/         # Seluruh komponen halaman (views publik dan panel admin)
│   ├── admin/     # DashboardView.vue & CmsView.vue
│   └── *.vue      # HomeView, DonasiView, GaleriView, KontakView, LoginPage, dll.
├── App.vue        # Komponen root utama
└── main.js        # File inisialisasi aplikasi
```

### Prinsip Arsitektur:
1. **Pemisahan Peran yang Jelas**: Seluruh halaman berstatus view diletakkan di dalam folder `src/views/`.
2. **Koneksi Terisolasi**: Frontend hanya berkomunikasi dengan backend melalui RESTful API di `VITE_API_URL` tanpa pernah mengakses database langsung.
