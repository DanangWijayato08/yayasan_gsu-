<script setup>
import { computed, ref, onMounted } from 'vue'

const props = defineProps({
  current: { type: [Number, String], default: 0 },
  target: { type: [Number, String], default: 0 },
  color: { type: String, default: 'primary' },
  showLabel: { type: Boolean, default: true },
})

const numericCurrent = computed(() => Number(props.current) || 0)
const numericTarget = computed(() => Number(props.target) || 0)

const percent = computed(() => {
  if (!numericTarget.value || numericTarget.value <= 0) return 0
  return Math.min(100, Math.max(0, Math.round((numericCurrent.value / numericTarget.value) * 100)))
})
const visible = ref(false)
const barRef = ref(null)

onMounted(() => {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        setTimeout(() => { visible.value = true }, 200)
        observer.disconnect()
      }
    })
  }, { threshold: 0.2 })
  if (barRef.value) observer.observe(barRef.value)
})
</script>

<template>
  <div ref="barRef" class="pbar">
    <div class="pbar__track">
      <div
        class="pbar__fill"
        :class="`pbar__fill--${color}`"
        :style="{ width: visible ? percent + '%' : '0%' }"
      ></div>
    </div>
    <span v-if="showLabel" class="pbar__percent">{{ percent }}%</span>
  </div>
</template>

<style scoped>
.pbar { display: flex; align-items: center; gap: 12px; }
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
  transition: width 1.4s cubic-bezier(0.22, 1, 0.36, 1);
  position: relative;
}
.pbar__fill::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
  animation: shimmer 2s infinite;
}
.pbar__fill--primary {
  background: linear-gradient(90deg, var(--color-primary), var(--color-primary-light));
}
.pbar__fill--secondary {
  background: linear-gradient(90deg, var(--color-secondary-dark), var(--color-secondary));
}
.pbar__percent { font-size: 0.82rem; font-weight: 700; color: var(--color-text-muted); min-width: 38px; text-align: right; }

@keyframes shimmer {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(100%); }
}
</style>
