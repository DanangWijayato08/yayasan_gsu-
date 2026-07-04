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
          <input
            id="email"
            v-model="form.email"
            type="email"
            placeholder="admin@sedekahumat.org"
            required
            :disabled="isLoading"
          />
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            placeholder="Masukkan password"
            required
            :disabled="isLoading"
          />
        </div>

        <button type="submit" class="login-btn" :disabled="isLoading">
          <span v-if="!isLoading">Login</span>
          <span v-else>
            <i class="fa-solid fa-spinner fa-spin"></i> Loading...
          </span>
        </button>
      </form>

      <div class="login-footer">
        <p>Demo Credentials:</p>
        <p class="demo-cred">📧 admin@sedekahumat.org</p>
        <p class="demo-cred">🔑 admin123456</p>
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

  console.log('Login attempt:', {
    email: form.value.email,
    password: '***'
  })

  const success = await authStore.login(form.value.email, form.value.password)

  if (success) {
    console.log('Login berhasil')
    router.push('/admin')
  } else {
    console.log('Login gagal:', authStore.error)
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
  background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
  padding: 20px;
}

.login-card {
  background: white;
  border-radius: 16px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  width: 100%;
  max-width: 400px;
  padding: 40px;
}

.login-header {
  text-align: center;
  margin-bottom: 40px;
}

.login-logo {
  width: 60px;
  height: 60px;
  margin: 0 auto 16px;
  border-radius: 12px;
  background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
  color: white;
}

.login-header h1 {
  font-size: 24px;
  margin: 0 0 8px;
  color: var(--color-text);
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
  background: #fee;
  color: #c33;
  padding: 12px;
  border-radius: 8px;
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
  font-weight: 600;
  color: var(--color-text);
  font-size: 14px;
}

.form-group input {
  padding: 12px;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  font-size: 14px;
  transition: border-color 0.2s;
}

.form-group input:focus {
  outline: none;
  border-color: var(--color-primary);
}

.form-group input:disabled {
  background: #f5f5f5;
  cursor: not-allowed;
}

.login-btn {
  padding: 12px;
  background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 700;
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
}

.login-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

.login-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.login-footer {
  margin-top: 30px;
  padding-top: 20px;
  border-top: 1px solid #e0e0e0;
  text-align: center;
  font-size: 13px;
  color: var(--color-text-muted);
}

.demo-cred {
  font-family: monospace;
  background: #f5f5f5;
  padding: 6px 12px;
  border-radius: 4px;
  margin: 4px 0;
}
</style>
