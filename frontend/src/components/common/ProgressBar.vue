<script setup>
import { computed } from 'vue'

const props = defineProps({
  current: { type: Number, required: true },
  target: { type: Number, required: true },
  color: { type: String, default: 'primary' }, // 'primary' | 'secondary'
})

const percent = computed(() => Math.min(100, Math.round((props.current / props.target) * 100)))
</script>

<template>
  <div class="pbar">
    <div class="pbar__track">
      <div
        class="pbar__fill"
        :class="`pbar__fill--${color}`"
        :style="{ width: percent + '%' }"
      ></div>
    </div>
    <span class="pbar__percent">{{ percent }}%</span>
  </div>
</template>

<style scoped>
.pbar { display: flex; align-items: center; gap: 10px; }
.pbar__track {
  flex: 1;
  height: 8px;
  background: var(--color-accent);
  border-radius: 999px;
  overflow: hidden;
}
.pbar__fill {
  height: 100%;
  border-radius: 999px;
  transition: width 1s cubic-bezier(0.22, 1, 0.36, 1);
}
.pbar__fill--primary { background: linear-gradient(90deg, var(--color-primary), var(--color-primary-light)); }
.pbar__fill--secondary { background: linear-gradient(90deg, var(--color-secondary-dark), var(--color-secondary)); }
.pbar__percent { font-size: 0.82rem; font-weight: 700; color: var(--color-text-muted); min-width: 36px; text-align: right; }
</style>
