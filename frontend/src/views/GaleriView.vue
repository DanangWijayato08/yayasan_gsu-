<script setup>
import Gallery from '../components/sections/Gallery.vue'
</script>

<template>
  <div>
    <section class="page-header">
      <div class="container" v-reveal="'up'">
        <span class="eyebrow">Galeri</span>
        <h1>Jejak Langkah Kebaikan</h1>
        <p>Transparansi dalam setiap pergerakan. Saksikan bagaimana kontribusi Anda menerangi jalan bagi mereka yang membutuhkan.</p>
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
  padding: 64px 0 40px;
  background: var(--color-accent);
}
.page-header h1 { font-size: clamp(2rem, 4vw, 2.8rem); margin-top: 16px; }
.page-header p { margin-top: 16px; max-width: 560px; color: var(--color-text-muted); font-size: 1.05rem; }
</style>
