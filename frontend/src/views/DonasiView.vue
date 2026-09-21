<script setup>
import DonationSection from '../components/sections/DonationSection.vue'
</script>

<template>
  <div>
    <section class="page-header page-header--center">
      <div class="container" v-reveal="'up'">
        <span class="eyebrow"><i class="fa-solid fa-hand-holding-heart"></i> Donasi Amanah</span>
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
  position: relative;
  padding: 64px 0 44px;
  background: var(--color-accent);
  border-bottom: 1px solid var(--color-border);
}

.page-header--center { text-align: center; }
.page-header--center .container { display: flex; flex-direction: column; align-items: center; }
.page-header .container { position: relative; z-index: 1; }
.page-header h1 { font-size: clamp(2.1rem, 4vw, 2.9rem); margin-top: 14px; font-weight: 650; }
.page-header p { margin-top: 14px; max-width: 580px; color: var(--color-text-muted); font-size: 1.05rem; line-height: 1.7; }
</style>
