<template>
  <div class="login-container">
    <div class="login-card">
      <h1>Gerakan Sedekah Umat</h1>
      <p>Admin Dashboard</p>
      
      <form @submit.prevent="handleLogin">
        <div v-if="error" class="error-msg">{{ error }}</div>
        
        <div class="form-group">
          <label>Email</label>
          <input v-model="email" type="email" placeholder="Masukkan email admin" autocomplete="email" required />
        </div>
        
        <div class="form-group">
          <label>Password</label>
          <input v-model="password" type="password" placeholder="Masukkan password" autocomplete="current-password" required />
        </div>
        
        <button type="submit" :disabled="loading">
          {{ loading ? 'Loading...' : 'Login' }}
        </button>
      </form>
      
      <p><router-link to="/">← Back</router-link></p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()
const email = ref('')
const password = ref('')
const loading = ref(false)
const error = ref(null)

const handleLogin = async () => {
  loading.value = true
  error.value = null
  const success = await authStore.login(email.value, password.value)
  if (success) {
    router.push('/admin')
  } else {
    error.value = authStore.error || 'Login failed'
  }
  loading.value = false
}
</script>

<style scoped>
.login-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, var(--color-bg) 0%, var(--color-accent) 100%);
  padding: 20px;
}

.login-card {
  background: var(--color-surface);
  border: 1px solid var(--color-border);
  border-radius: 12px;
  box-shadow: var(--shadow-lg);
  width: 100%;
  max-width: 400px;
  padding: 40px;
  text-align: center;
}

h1 {
  margin: 0 0 10px;
  font-size: 24px;
}

p {
  margin: 0 0 30px;
  color: var(--color-text-muted);
}

.form-group {
  margin-bottom: 16px;
  text-align: left;
}

label {
  display: block;
  margin-bottom: 6px;
  font-weight: 600;
  font-size: 13px;
}

input {
  width: 100%;
  padding: 12px;
  border: 1px solid var(--color-border);
  border-radius: 8px;
  font-size: 14px;
}

input:focus {
  outline: none;
  border-color: var(--color-secondary);
}

button {
  width: 100%;
  padding: 12px;
  background: var(--color-secondary);
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
}

button:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(12, 90, 62, 0.3);
}

button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.error-msg {
  background: rgba(185, 28, 28, 0.1);
  color: var(--color-error);
  padding: 10px;
  border-radius: 8px;
  margin-bottom: 16px;
  font-size: 14px;
}

a {
  color: var(--color-secondary);
  text-decoration: none;
  font-weight: 600;
}

a:hover {
  text-decoration: underline;
}
</style>

