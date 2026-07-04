# Gerakan Sedekah Umat (GSU) — Website Resmi

Website yayasan dibangun dengan **Vue 3 (Composition API) + Vite + Vue Router**, tanpa Tailwind (CSS native murni).

## Menjalankan Project

```bash
npm install
npm run dev
```

Buka `http://localhost:5173`.

Build untuk produksi:

```bash
npm run build
npm run preview
```

## Struktur Project

```
src/
  assets/main.css        # Design tokens (warna, font, spacing) + utilitas global
  components/
    Navbar.vue            # Navbar sticky + blur on scroll + menu mobile
    Footer.vue
    Hero.vue               # Hero 2 kolom + floating stat cards
    Stats.vue              # Counter animasi + progress bar
    Counter.vue            # Reusable animated number counter
    ProgressBar.vue        # Reusable progress bar
    About.vue              # Visi/Misi/Nilai + timeline
    ProgramCard.vue        # Card program (reusable)
    ProgramSection.vue     # Grid program + filter kategori
    Gallery.vue            # Grid masonry + lightbox
    Testimonial.vue        # Slider testimoni
    News.vue               # Grid berita
    DonationSection.vue    # Form donasi (nominal, data diri, metode bayar)
    Contact.vue            # Info kontak + peta + form
  views/
    HomeView.vue
    TentangView.vue
    ProgramView.vue
    DonasiView.vue
    GaleriView.vue
    BeritaView.vue
    KontakView.vue
    AdminDashboardView.vue  # Dashboard admin (route: /admin)
  router/index.js
  App.vue
  main.js                  # Berisi directive global v-reveal (animasi scroll)
```

## Palet Warna

| Token | Hex | Kegunaan |
|---|---|---|
| `--color-primary` | `#C8A415` | Gold — CTA, aksen utama |
| `--color-secondary` | `#2E7D32` | Hijau — aksen sekunder |
| `--color-accent` | `#F5F5F5` | Background section abu terang |
| `--color-text` | `#222222` | Teks utama |
| `--color-bg` | `#FFFFFF` | Background utama |

Semua token warna & spacing didefinisikan di `src/assets/main.css` — ubah di satu tempat untuk memengaruhi seluruh situs.

## Mengganti Gambar

Semua gambar saat ini memakai placeholder dari Unsplash/Pravatar (dimuat via URL, tidak perlu didownload). Untuk mengganti:

1. Simpan gambar Anda di `src/assets/images/` (buat folder ini).
2. Import atau ganti langsung path `src="..."` pada komponen terkait (`Hero.vue`, `ProgramSection.vue`, `Gallery.vue`, `News.vue`, dll).

## Animasi Scroll (`v-reveal`)

Directive global custom di `main.js`. Pemakaian:

```html
<div v-reveal="'up'">...</div>       <!-- fade up -->
<div v-reveal="'left'">...</div>     <!-- fade dari kiri -->
<div v-reveal:150="'right'">...</div> <!-- fade dari kanan, delay 150ms -->
```

## Catatan

- Form donasi & kontak saat ini adalah simulasi front-end (belum terhubung ke backend/payment gateway). Untuk produksi, sambungkan ke API pembayaran (Midtrans/Xendit untuk QRIS & VA) dan backend penyimpanan data donatur.
- Dashboard admin (`/admin`) adalah tampilan statis dengan data contoh — perlu diintegrasikan dengan backend untuk data real-time.
- Font: **Fraunces** (heading) + **Plus Jakarta Sans** (body), dimuat dari Google Fonts. Ikon: **Font Awesome 6** via CDN.
