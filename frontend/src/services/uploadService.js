import api from './api'

export const uploadService = {
  image: (file, folder = 'uploads') => {
    const formData = new FormData()
    formData.append('image', file)
    formData.append('folder', folder)
    return api.post('/admin/upload', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
  },
}
