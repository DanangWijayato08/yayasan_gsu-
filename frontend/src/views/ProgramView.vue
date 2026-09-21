<script setup>
import ProgramSection from '../components/sections/ProgramSection.vue'
</script>

<template>
  <div>
    <section class="page-header">
      <div class="container" v-reveal="'up'">
        <span class="eyebrow"><i class="fa-solid fa-hand-holding-hand"></i> Inisiatif & Program</span>
        <h1>Program Kebaikan & Pemberdayaan</h1>
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
