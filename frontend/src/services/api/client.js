import axios from 'axios'

const API_URL = 'http://localhost:8000/api'

const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
  },
})

api.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

export const authService = {
  login: (email, password) => api.post('/login', { email, password }),
  logout: () => api.post('/logout'),
  me: () => api.get('/me'),
}

export const programService = {
  getAll: () => api.get('/programs'),
  get: (id) => api.get(`/programs/${id}`),
  create: (data) => api.post('/programs', data),
  update: (id, data) => api.put(`/programs/${id}`, data),
  delete: (id) => api.delete(`/programs/${id}`),
}

export const galleryService = {
  getAll: () => api.get('/galleries'),
  get: (id) => api.get(`/galleries/${id}`),
  create: (data) => api.post('/galleries', data),
  update: (id, data) => api.put(`/galleries/${id}`, data),
  delete: (id) => api.delete(`/galleries/${id}`),
}

export const contactService = {
  create: (data) => api.post('/contacts', data),
  getAll: () => api.get('/contacts'),
  get: (id) => api.get(`/contacts/${id}`),
  update: (id, data) => api.put(`/contacts/${id}`, data),
  delete: (id) => api.delete(`/contacts/${id}`),
}

export default api
