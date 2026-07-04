<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
  target: { type: Number, required: true },
  duration: { type: Number, default: 1600 },
  prefix: { type: String, default: '' },
  suffix: { type: String, default: '' },
})

const display = ref(0)
const el = ref(null)

function animate() {
  const start = performance.now()
  function tick(now) {
    const progress = Math.min((now - start) / props.duration, 1)
    const eased = 1 - Math.pow(1 - progress, 3)
    display.value = Math.floor(eased * props.target)
    if (progress < 1) requestAnimationFrame(tick)
    else display.value = props.target
  }
  requestAnimationFrame(tick)
}

onMounted(() => {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        animate()
        observer.disconnect()
      }
    })
  }, { threshold: 0.4 })
  if (el.value) observer.observe(el.value)
})
</script>

<template>
  <span ref="el">{{ prefix }}{{ display.toLocaleString('id-ID') }}{{ suffix }}</span>
</template>
