import api from '../api'
import { authService } from '../authService'
import { publicService } from '../publicService'
import { adminService } from '../adminService'
import { uploadService } from '../uploadService'

export { authService, publicService, adminService, uploadService }

export const programService = {
  getAll: publicService.programs,
  getFeatured: publicService.featuredPrograms,
  get: publicService.program,
  adminAll: adminService.programs,
  create: adminService.createProgram,
  update: adminService.updateProgram,
  delete: adminService.deleteProgram,
}

export const galleryService = {
  getAll: publicService.galleries,
  adminAll: adminService.galleries,
  create: adminService.createGallery,
  update: adminService.updateGallery,
  delete: adminService.deleteGallery,
}

export const contactService = {
  create: publicService.createContactMessage,
  getAll: adminService.messages,
  update: adminService.updateMessage,
  delete: adminService.deleteMessage,
}

export default api
