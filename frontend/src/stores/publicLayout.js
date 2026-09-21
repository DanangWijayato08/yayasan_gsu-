import { defineStore } from 'pinia'
import { publicService } from '@/services/publicService'

export const usePublicLayoutStore = defineStore('publicLayout', {
  state: () => ({
    loaded: false,
    loading: false,
    site: null,
    contact: null,
    footer: null,
  }),
  actions: {
    async load(force = false) {
      if (!force && (this.loaded || this.loading)) return
      this.loading = true
      try {
        const { data } = await publicService.layout()
        this.site = data?.site_settings || null
        this.contact = data?.contact_settings || null
        this.footer = data?.footer_settings || null
        this.loaded = true
      } finally {
        this.loading = false
      }
    },
  },
})
