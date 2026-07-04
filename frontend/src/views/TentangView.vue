<script setup>
import About from '../components/sections/About.vue'
import Stats from '../components/common/Stats.vue'
</script>

<template>
  <div>
    <section class="page-header">
      <div class="container" v-reveal="'up'">
        <span class="eyebrow">Tentang Kami</span>
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
  padding: 64px 0 40px;
  background: var(--color-accent);
  text-align: left;
}
.page-header h1 { font-size: clamp(2rem, 4vw, 2.8rem); margin-top: 16px; }
.page-header p { margin-top: 16px; max-width: 560px; color: var(--color-text-muted); font-size: 1.05rem; }
</style>
