<script setup>
import Gallery from '../components/sections/Gallery.vue'
</script>

<template>
  <div>
    <section class="page-header">
      <div class="container">
        <span class="eyebrow"><i class="fa-solid fa-images"></i> Dokumentasi Aktivitas</span>
        <h1>Jejak Langkah Kebaikan</h1>
        <p>Transparansi dalam setiap pergerakan. Saksikan bagaimana amanah dan kontribusi Anda sampai kepada mereka yang berhak.</p>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <Gallery />
      </div>
    </section>
  </div>
</template>

<style scoped>
.page-header {
  position: relative;
  padding: 64px 0 44px;
  background: var(--color-accent);
  border-bottom: 1px solid var(--color-border);
}

.page-header .container { position: relative; z-index: 1; }

.page-header h1 {
  font-size: clamp(2.1rem, 4vw, 2.9rem);
  margin-top: 14px;
  font-weight: 650;
}

.page-header p {
  margin-top: 14px;
  max-width: 580px;
  color: var(--color-text-muted);
  font-size: 1.05rem;
  line-height: 1.7;
}
</style>
