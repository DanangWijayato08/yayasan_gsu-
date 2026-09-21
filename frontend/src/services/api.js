import axios from 'axios'

function getBaseApiUrl() {
  const envUrl = import.meta.env.VITE_API_URL
  // Bila diakses dari browser selain localhost/127.0.0.1 (misal HP via IP lokal 192.168.x.x),
  // gunakan relative path '/api' agar otomatis di-proxy oleh server Vite ke backend Laravel.
  if (typeof window !== 'undefined' && window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
    return '/api'
  }
  return envUrl || 'http://localhost:8000/api'
}

const API_URL = getBaseApiUrl()

const api = axios.create({
  baseURL: API_URL,
  headers: { 'Content-Type': 'application/json' },
})

api.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response && error.response.status === 401) {
      localStorage.removeItem('auth_token')
      if (
        typeof window !== 'undefined' &&
        window.location.pathname.startsWith('/admin') &&
        window.location.pathname !== '/admin/login'
      ) {
        window.location.href = '/admin/login'
      }
    }
    return Promise.reject(error)
  }
)

export default api
