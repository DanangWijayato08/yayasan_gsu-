<script setup>
import DonationSection from '../components/sections/DonationSection.vue'
</script>

<template>
  <div>
    <section class="page-header page-header--center">
      <div class="container" v-reveal="'up'">
        <span class="eyebrow">Donasi</span>
        <h1>Setiap Rupiah Membawa Harapan</h1>
        <p>Pilih nominal, lengkapi data, dan pilih metode pembayaran — donasi Anda akan tersalurkan dengan transparan.</p>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <DonationSection />
      </div>
    </section>
  </div>
</template>

<style scoped>
.page-header {
  padding: 64px 0 40px;
  background: var(--color-accent);
}
.page-header--center { text-align: center; }
.page-header--center .container { display: flex; flex-direction: column; align-items: center; }
.page-header h1 { font-size: clamp(2rem, 4vw, 2.8rem); margin-top: 16px; }
.page-header p { margin-top: 16px; max-width: 560px; color: var(--color-text-muted); font-size: 1.05rem; }
</style>
