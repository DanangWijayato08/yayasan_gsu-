<script setup>
import Counter from './Counter.vue'
import ProgressBar from './ProgressBar.vue'

const stats = [
  { icon: 'fa-people-group', value: 15230, suffix: '+', label: 'Donatur Aktif' },
  { icon: 'fa-diagram-project', value: 24, suffix: '', label: 'Program Berjalan' },
  { icon: 'fa-hand-holding-heart', value: 8500, suffix: '+', label: 'Mustahik Terbantu' },
]
</script>

<template>
  <section class="stats">
    <div class="container stats__grid">
      <div v-for="(s, i) in stats" :key="s.label" class="stats__item" v-reveal:[i*100]="'up'">
        <div class="stats__icon"><i :class="'fa-solid ' + s.icon"></i></div>
        <strong class="stats__value"><Counter :target="s.value" :suffix="s.suffix" /></strong>
        <span class="stats__label">{{ s.label }}</span>
      </div>

      <div class="stats__progress" v-reveal:300="'up'">
        <div class="stats__progress-head">
          <span>Target Tercapai — Kampanye Bulan Ini</span>
          <strong>Rp850Jt / Rp1.2M</strong>
        </div>
        <ProgressBar :current="850" :target="1200" color="primary" />
      </div>
    </div>
  </section>
</template>

<style scoped>
.stats {
  padding: 56px 0;
  background: var(--color-secondary-dark);
  color: #fff;
  position: relative;
  margin-top: -1px;
}
.stats__grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 32px;
}
.stats__item {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 10px;
  padding: 18px;
}
.stats__icon {
  width: 46px; height: 46px;
  border-radius: 12px;
  background: rgba(255,255,255,0.12);
  display: flex; align-items: center; justify-content: center;
  color: var(--color-primary);
  font-size: 1.1rem;
}
.stats__value { font-size: 2rem; font-family: var(--font-heading); }
.stats__label { color: rgba(255,255,255,0.7); font-size: 0.9rem; }

.stats__progress {
  grid-column: 1 / -1;
  background: rgba(255,255,255,0.08);
  border-radius: var(--radius-md);
  padding: 22px 26px;
  margin-top: 8px;
}
.stats__progress-head {
  display: flex;
  justify-content: space-between;
  margin-bottom: 12px;
  font-size: 0.9rem;
  flex-wrap: wrap;
  gap: 8px;
}
.stats__progress :deep(.pbar__track) { background: rgba(255,255,255,0.18); }
.stats__progress :deep(.pbar__percent) { color: rgba(255,255,255,0.8); }

@media (max-width: 760px) {
  .stats__grid { grid-template-columns: 1fr; }
}
</style>
