<script setup>
import Contact from '../components/sections/Contact.vue'
</script>

<template>
  <div>
    <section class="page-header">
      <div class="container" v-reveal="'up'">
        <span class="eyebrow">Kontak</span>
        <h1>Hubungi Kami</h1>
        <p>Ada pertanyaan seputar donasi, program, atau ingin menjadi relawan? Kami siap membantu.</p>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <Contact />
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
