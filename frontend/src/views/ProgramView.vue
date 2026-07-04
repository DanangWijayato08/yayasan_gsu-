<script setup>
import ProgramSection from '../components/sections/ProgramSection.vue'
</script>

<template>
  <div>
    <section class="page-header">
      <div class="container" v-reveal="'up'">
        <span class="eyebrow">Program Kami</span>
        <h1>Bergabunglah dalam Misi Kami</h1>
        <p>Menerangi jalan kebaikan melalui inisiatif sosial, pendidikan, dan keagamaan yang berkelanjutan untuk memberdayakan masyarakat.</p>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <ProgramSection />
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
