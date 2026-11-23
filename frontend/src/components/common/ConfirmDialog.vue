<template>
  <teleport to="body">
    <transition name="dialog-fade">
      <div v-if="isOpen" class="dialog-overlay" @click="handleBackdropClick">
        <div class="dialog-container" @click.stop>
          <div class="dialog-header">
            <h3>{{ title }}</h3>
            <button class="dialog-close" @click="cancel" aria-label="Close dialog">
              <i class="fa-solid fa-xmark"></i>
            </button>
          </div>

          <div class="dialog-body">
            <p>{{ message }}</p>
          </div>

          <div class="dialog-footer">
            <button class="btn-secondary" @click="cancel" :disabled="isLoading">
              {{ cancelText }}
            </button>
            <button 
              :class="['btn-primary', `btn-${type}`]" 
              @click="confirm" 
              :disabled="isLoading"
            >
              <i v-if="isLoading" class="fa-solid fa-spinner fa-spin"></i>
              {{ isLoading ? 'Memproses...' : confirmText }}
            </button>
          </div>
        </div>
      </div>
    </transition>
  </teleport>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  title: { type: String, required: true },
  message: { type: String, required: true },
  confirmText: { type: String, default: 'Hapus' },
  cancelText: { type: String, default: 'Batal' },
  type: { type: String, default: 'danger', validator: (v) => ['danger', 'warning', 'info'].includes(v) },
  closeOnBackdrop: { type: Boolean, default: true },
})

const emit = defineEmits(['confirm', 'cancel'])

const isOpen = ref(false)
const isLoading = ref(false)

const open = () => {
  isOpen.value = true
}

const close = () => {
  isOpen.value = false
  isLoading.value = false
}

const cancel = () => {
  emit('cancel')
  close()
}

const confirm = async () => {
  isLoading.value = true
  try {
    await emit('confirm')
  } finally {
    close()
  }
}

const handleBackdropClick = () => {
  if (props.closeOnBackdrop) {
    cancel()
  }
}

defineExpose({ open, close })
</script>

<style scoped>
.dialog-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(17, 38, 31, 0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  backdrop-filter: blur(2px);
}

.dialog-container {
  background: var(--color-surface);
  border-radius: var(--radius-md);
  box-shadow: var(--shadow-xl);
  max-width: 450px;
  width: 90vw;
  animation: slideUp 0.3s ease-out;
}

.dialog-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 24px;
  border-bottom: 1px solid var(--color-border);
}

.dialog-header h3 {
  margin: 0;
  font-size: 18px;
  font-weight: 600;
  color: var(--color-text);
}

.dialog-close {
  width: 36px;
  height: 36px;
  border-radius: var(--radius-sm);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--color-text-muted);
  background: transparent;
  transition: all 0.2s ease;
}

.dialog-close:hover {
  background: var(--color-accent);
  color: var(--color-text);
}

.dialog-body {
  padding: 24px;
  color: var(--color-text-muted);
  line-height: 1.6;
}

.dialog-body p {
  margin: 0;
}

.dialog-footer {
  display: flex;
  gap: 12px;
  padding: 20px 24px;
  border-top: 1px solid var(--color-border);
  justify-content: flex-end;
}

.btn-secondary,
.btn-primary {
  padding: 10px 20px;
  border-radius: var(--radius-sm);
  font-size: 14px;
  font-weight: 600;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  gap: 8px;
}

.btn-secondary {
  background: var(--color-accent);
  color: var(--color-text);
  border: 1px solid var(--color-border);
}

.btn-secondary:hover:not(:disabled) {
  background: var(--color-border);
}

.btn-primary {
  background: var(--color-secondary);
  color: white;
}

.btn-primary.btn-danger {
  background: var(--color-error);
}

.btn-primary.btn-warning {
  background: var(--color-warning);
}

.btn-primary:hover:not(:disabled) {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(12, 90, 62, 0.2);
}

.btn-primary.btn-danger:hover:not(:disabled) {
  box-shadow: 0 4px 12px rgba(185, 28, 28, 0.2);
}

button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

@keyframes slideUp {
  from {
    transform: translateY(20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

@keyframes dialogFadeEnter {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes dialogFadeLeave {
  from {
    opacity: 1;
  }
  to {
    opacity: 0;
  }
}

.dialog-fade-enter-active {
  animation: dialogFadeEnter 0.2s ease-out;
}

.dialog-fade-leave-active {
  animation: dialogFadeLeave 0.2s ease-out;
}
</style>
