import api from './api'

export function normalizeList(payload) {
  if (Array.isArray(payload)) return payload
  if (Array.isArray(payload?.data)) return payload.data
  if (Array.isArray(payload?.data?.data)) return payload.data.data
  if (Array.isArray(payload?.programs)) return payload.programs
  if (Array.isArray(payload?.featured_programs)) return payload.featured_programs
  return []
}

function normalizeListResponse(request) {
  return request.then((response) => ({
    ...response,
    data: normalizeList(response.data),
  }))
}

export const publicService = {
  home: () => api.get('/public/home'),
  layout: () => api.get('/public/layout'),
  siteSettings: () => api.get('/site-settings'),
  homepage: () => api.get('/homepage'),
  about: () => api.get('/about'),
  programs: () => normalizeListResponse(api.get('/programs')),
  featuredPrograms: () => normalizeListResponse(api.get('/programs/featured')),
  program: (slug) => api.get(`/programs/${slug}`),
  galleries: () => api.get('/galleries'),
  donationSettings: () => api.get('/donation-settings'),
  contactSettings: () => api.get('/contact-settings'),
  footer: () => api.get('/footer'),
  createContactMessage: (data) => api.post('/contact-messages', data),
}
