<template>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <div class="login-logo">
          <i class="fa-solid fa-hand-holding-heart"></i>
        </div>
        <h1>Gerakan Sedekah Umat</h1>
        <p>Admin Dashboard</p>
      </div>

      <form @submit.prevent="handleLogin" class="login-form">
        <div v-if="error" class="login-error">
          <i class="fa-solid fa-exclamation-circle"></i>
          {{ error }}
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <div class="form-input-wrapper">
            <i class="fa-solid fa-envelope"></i>
            <input
              id="email"
              v-model="form.email"
              type="email"
              placeholder="admin@sedekahumat.org"
              required
              :disabled="isLoading"
            />
          </div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="form-input-wrapper">
            <i class="fa-solid fa-lock"></i>
            <input
              id="password"
              v-model="form.password"
              type="password"
              placeholder="Masukkan password"
              required
              :disabled="isLoading"
            />
          </div>
        </div>

        <button type="submit" class="login-btn" :disabled="isLoading">
          <span v-if="!isLoading">Masuk ke Dashboard <i class="fa-solid fa-arrow-right"></i></span>
          <span v-else><i class="fa-solid fa-spinner fa-spin"></i> Memproses...</span>
        </button>
      </form>

      <div class="login-footer">
        <p>Kembali ke <router-link to="/">halaman utama</router-link></p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const form = ref({
  email: '',
  password: '',
})

const isLoading = ref(false)
const error = ref(null)

const handleLogin = async () => {
  if (!form.value.email || !form.value.password) {
    error.value = 'Email dan password harus diisi'
    return
  }

  if (!form.value.email.includes('@')) {
    error.value = 'Email harus valid'
    return
  }

  if (form.value.password.length < 6) {
    error.value = 'Password minimal 6 karakter'
    return
  }

  isLoading.value = true
  error.value = null

  const success = await authStore.login(form.value.email, form.value.password)

  if (success) {
    router.push('/admin')
  } else {
    error.value = authStore.error || 'Login gagal'
  }

  isLoading.value = false
}
</script>

<style scoped>
.login-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--color-bg);
  padding: clamp(20px, 5vw, 40px);
  position: relative;
}

.login-card {
  background: var(--color-surface);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-md);
  box-shadow: var(--shadow-lg);
  width: 100%;
  max-width: 420px;
  padding: clamp(32px, 5vw, 40px);
  position: relative;
  z-index: 1;
}

.login-header {
  text-align: center;
  margin-bottom: 32px;
}

.login-logo {
  width: 54px;
  height: 54px;
  margin: 0 auto 16px;
  border-radius: var(--radius-sm);
  background: var(--color-secondary);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 22px;
  color: white;
  box-shadow: var(--shadow-sm);
}

.login-header h1 {
  font-size: clamp(22px, 4vw, 28px);
  margin: 0 0 6px;
  color: var(--color-text);
  letter-spacing: -0.03em;
}

.login-header p {
  color: var(--color-text-muted);
  margin: 0;
  font-size: 14px;
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.login-error {
  background: #fff1f2;
  color: var(--color-error);
  padding: 14px 16px;
  border: 1px solid #fecdd3;
  border-radius: 16px;
  font-size: 14px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-group label {
  font-weight: 700;
  color: var(--color-text);
  font-size: 13px;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.form-input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.form-input-wrapper i {
  position: absolute;
  left: 16px;
  color: var(--color-text-muted);
  font-size: 14px;
  z-index: 1;
}

.form-input-wrapper input {
  width: 100%;
  padding: 14px 16px 14px 44px;
  border: 1.5px solid #d9e6de;
  border-radius: 16px;
  font-size: 14px;
  transition: all 0.25s ease;
  background: #fbfdfb;
  color: var(--color-text);
}

.form-input-wrapper input:focus {
  outline: none;
  border-color: var(--color-secondary);
  box-shadow: 0 0 0 4px rgba(15,138,95,0.1);
  background: #fff;
}

.form-input-wrapper input:disabled {
  background: #f5f5f5;
  cursor: not-allowed;
  opacity: 0.7;
}

.login-btn {
  padding: 16px;
  background: linear-gradient(135deg, var(--color-secondary), #0a6849);
  color: white;
  border: none;
  border-radius: 16px;
  font-size: 15px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.login-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 16px 34px rgba(15, 138, 95, 0.25);
}

.login-btn:active:not(:disabled) {
  transform: translateY(0);
}

.login-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.login-footer {
  margin-top: 28px;
  padding-top: 20px;
  border-top: 1px solid #e0e0e0;
  text-align: center;
  font-size: 13px;
  color: var(--color-text-muted);
}

.login-footer a {
  color: var(--color-secondary);
  font-weight: 600;
}

.login-footer a:hover {
  text-decoration: underline;
}
</style>
