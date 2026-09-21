import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { authService } from '../services/authService'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(localStorage.getItem('auth_token') || null)
  const isLoading = ref(false)
  const error = ref(null)

  const isAuthenticated = computed(() => !!token.value)

  async function login(email, password) {
    isLoading.value = true
    error.value = null
    try {
      const response = await authService.login(email, password)
      
      token.value = response.data.token
      user.value = response.data.user
      localStorage.setItem('auth_token', token.value)
      
      return true
    } catch (err) {
      if (err.response?.data?.message) {
        error.value = err.response.data.message
      } else if (err.response?.data?.errors) {
        const errors = err.response.data.errors
        error.value = Object.values(errors).flat().join(', ')
      } else if (err.response?.status === 401) {
        error.value = 'Email atau password salah'
      } else {
        error.value = err.message || 'Login gagal. Cek koneksi API.'
      }
      return false
    } finally {
      isLoading.value = false
    }
  }

  async function logout() {
    try {
      await authService.logout()
    } catch (err) {
      // Continue local logout even when the token is already expired server-side.
    } finally {
      token.value = null
      user.value = null
      localStorage.removeItem('auth_token')
    }
  }

  async function fetchCurrentUser() {
    if (!token.value) return false
    try {
      const response = await authService.me()
      user.value = response.data
      return true
    } catch (err) {
      token.value = null
      localStorage.removeItem('auth_token')
      return false
    }
  }

  return {
    user,
    token,
    isLoading,
    error,
    isAuthenticated,
    login,
    logout,
    fetchCurrentUser,
  }
})
