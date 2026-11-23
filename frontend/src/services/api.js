import axios from 'axios'

function getBaseApiUrl() {
  const envUrl = import.meta.env.VITE_API_URL
  if (typeof window !== 'undefined') {
    const host = window.location.hostname
    const isLocalHost = host === 'localhost' || host === '127.0.0.1'
    const isPrivateLan =
      /^192\.168\./.test(host) ||
      /^10\./.test(host) ||
      /^172\.(1[6-9]|2\d|3[0-1])\./.test(host) ||
      /^100\./.test(host)

    // Saat dijalankan lokal (vite dev / vite preview di localhost atau IP LAN),
    // gunakan relative path '/api' agar otomatis di-proxy ke backend Laravel lokal tanpa CORS/DNS error.
    if (isLocalHost || isPrivateLan) {
      return '/api'
    }
  }
  return envUrl || '/api'
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
