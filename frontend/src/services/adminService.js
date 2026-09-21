import api from './api'

export const adminService = {
  dashboard: () => api.get('/admin/dashboard'),
  homepageSections: () => api.get('/admin/homepage-sections'),
  createHomepageSection: (data) => api.post('/admin/homepage-sections', data),
  saveHomepageSection: (id, data) => api.put(`/admin/homepage-sections/${id}`, data),
  deleteHomepageSection: (id) => api.delete(`/admin/homepage-sections/${id}`),
  getSetting: (type) => api.get(`/admin/${type}`),
  updateSetting: (type, data) => api.put(`/admin/${type}`, data),
  programs: (params = {}) => api.get('/admin/programs', { params }),
  createProgram: (data) => api.post('/admin/programs', data),
  updateProgram: (id, data) => api.put(`/admin/programs/${id}`, data),
  deleteProgram: (id) => api.delete(`/admin/programs/${id}`),
  galleries: (params = {}) => api.get('/admin/galleries', { params }),
  createGallery: (data) => api.post('/admin/galleries', data),
  updateGallery: (id, data) => api.put(`/admin/galleries/${id}`, data),
  deleteGallery: (id) => api.delete(`/admin/galleries/${id}`),
  messages: (params = {}) => api.get('/admin/contact-messages', { params }),
  updateMessage: (id, data) => api.put(`/admin/contact-messages/${id}`, data),
  deleteMessage: (id) => api.delete(`/admin/contact-messages/${id}`),
}
