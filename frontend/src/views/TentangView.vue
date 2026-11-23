<script setup>
import About from '../components/sections/About.vue'
import Stats from '../components/common/Stats.vue'
</script>

<template>
  <div>
    <section class="page-header">
      <div class="container">
        <span class="eyebrow"><i class="fa-solid fa-landmark"></i> Tentang Lembaga</span>
        <h1>Mengenal Gerakan Sedekah Umat</h1>
        <p>Yayasan yang lahir dari keyakinan bahwa kebaikan kolektif mampu membangun perubahan nyata dan berkelanjutan.</p>
      </div>
    </section>

    <About />
    <Stats />
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
