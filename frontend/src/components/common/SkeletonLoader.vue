<template>
  <div class="skeleton-loader">
    <div class="skeleton-item" :style="{ width: width, height: height }"></div>
  </div>
</template>

<script setup>
defineProps({
  width: { type: String, default: '100%' },
  height: { type: String, default: '20px' },
})
</script>

<style scoped>
.skeleton-loader {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.skeleton-item {
  background: linear-gradient(90deg, #e5dfd3 25%, #f3efe6 50%, #e5dfd3 75%);
  background-size: 200% 100%;
  animation: shimmer 2s infinite;
  border-radius: var(--radius-sm);
}

@keyframes shimmer {
  0% {
    background-position: 200% 0;
  }
  100% {
    background-position: -200% 0;
  }
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.8;
  }
}
</style>
