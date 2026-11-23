<template>
  <teleport to="body">
    <transition-group name="toast-list" tag="div" class="toast-container">
      <div
        v-for="toast in toasts"
        :key="toast.id"
        :class="['toast', `toast-${toast.type}`]"
        role="alert"
      >
        <div class="toast-content">
          <i :class="getIcon(toast.type)"></i>
          <div class="toast-text">
            <p class="toast-title">{{ toast.title }}</p>
            <p v-if="toast.message" class="toast-message">{{ toast.message }}</p>
          </div>
        </div>
        <button class="toast-close" @click="remove(toast.id)" aria-label="Close notification">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>
    </transition-group>
  </teleport>
</template>

<script setup>
import { ref } from 'vue'

const toasts = ref([])
let nextId = 0

const getIcon = (type) => {
  const icons = {
    success: 'fa-solid fa-circle-check',
    error: 'fa-solid fa-circle-xmark',
    warning: 'fa-solid fa-triangle-exclamation',
    info: 'fa-solid fa-circle-info',
  }
  return icons[type] || icons.info
}

const add = (title, message = '', type = 'info', duration = 5000) => {
  const id = nextId++
  const toast = { id, title, message, type }
  toasts.value.push(toast)

  if (duration > 0) {
    setTimeout(() => remove(id), duration)
  }

  return id
}

const remove = (id) => {
  const index = toasts.value.findIndex((t) => t.id === id)
  if (index > -1) {
    toasts.value.splice(index, 1)
  }
}

const clear = () => {
  toasts.value = []
}

defineExpose({ add, remove, clear })
</script>

<style scoped>
.toast-container {
  position: fixed;
  top: 20px;
  right: 20px;
  z-index: 9999;
  display: flex;
  flex-direction: column;
  gap: 12px;
  max-width: 420px;
  pointer-events: none;
}

.toast {
  background: var(--color-surface);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-md);
  padding: 14px 16px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  box-shadow: var(--shadow-md);
  pointer-events: auto;
  animation: slideIn 0.3s ease-out;
}

.toast-content {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  flex: 1;
  min-width: 0;
}

.toast-content i {
  font-size: 18px;
  flex-shrink: 0;
  margin-top: 2px;
}

.toast-text {
  flex: 1;
  min-width: 0;
}

.toast-title {
  margin: 0;
  font-weight: 600;
  font-size: 14px;
  line-height: 1.4;
}

.toast-message {
  margin: 4px 0 0;
  font-size: 13px;
  opacity: 0.8;
  line-height: 1.4;
  word-break: break-word;
}

.toast-close {
  flex-shrink: 0;
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: transparent;
  border: none;
  color: var(--color-text-muted);
  cursor: pointer;
  transition: all 0.2s ease;
  font-size: 14px;
}

.toast-close:hover {
  color: var(--color-text);
  background: var(--color-accent);
  border-radius: var(--radius-xs);
}

.toast-success {
  border-color: var(--color-success);
  background: linear-gradient(135deg, rgba(12, 90, 62, 0.08), rgba(12, 90, 62, 0.04));
}

.toast-success .toast-content i {
  color: var(--color-success);
}

.toast-error {
  border-color: var(--color-error);
  background: linear-gradient(135deg, rgba(185, 28, 28, 0.08), rgba(185, 28, 28, 0.04));
}

.toast-error .toast-content i {
  color: var(--color-error);
}

.toast-warning {
  border-color: var(--color-warning);
  background: linear-gradient(135deg, rgba(217, 119, 6, 0.08), rgba(217, 119, 6, 0.04));
}

.toast-warning .toast-content i {
  color: var(--color-warning);
}

.toast-info {
  border-color: var(--color-secondary);
  background: linear-gradient(135deg, rgba(12, 90, 62, 0.08), rgba(12, 90, 62, 0.04));
}

.toast-info .toast-content i {
  color: var(--color-secondary);
}

@keyframes slideIn {
  from {
    transform: translateX(400px);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

.toast-list-enter-active,
.toast-list-leave-active {
  transition: all 0.3s ease;
}

.toast-list-leave-to {
  transform: translateX(400px);
  opacity: 0;
}

@media (max-width: 600px) {
  .toast-container {
    left: 12px;
    right: 12px;
    max-width: none;
  }

  .toast {
    padding: 12px 14px;
  }
}
</style>
