<script setup>
import { computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import Navbar from './components/common/Navbar.vue'
import Footer from './components/common/Footer.vue'
import { usePublicLayoutStore } from '@/stores/publicLayout'
import { useAuthStore } from '@/stores/auth'

const route = useRoute()
const isAdmin = computed(() => route.meta?.layout === 'admin')
const layoutStore = usePublicLayoutStore()
const authStore = useAuthStore()
const isMaintenance = computed(
  () => !isAdmin.value && route.name !== 'admin-login' && Boolean(layoutStore.site?.maintenance_mode)
)

function upsertMeta(name, content) {
  if (!content) return
  let tag = document.querySelector(`meta[name="${name}"]`)
  if (!tag) {
    tag = document.createElement('meta')
    tag.setAttribute('name', name)
    document.head.appendChild(tag)
  }
  tag.setAttribute('content', content)
}

function setFavicon(href) {
  if (!href) return
  let link = document.querySelector('link[rel="icon"]')
  if (!link) {
    link = document.createElement('link')
    link.setAttribute('rel', 'icon')
    document.head.appendChild(link)
  }
  link.setAttribute('href', href)
}

onMounted(async () => {
  if (authStore.isAuthenticated) {
    await authStore.fetchCurrentUser()
  }

  try {
    await layoutStore.load()
    const data = layoutStore.site
    if (!data) return
    document.title = data.meta_title || data.site_name || document.title
    upsertMeta('description', data.meta_description)
    upsertMeta('keywords', data.meta_keywords)
    setFavicon(data.favicon)
  } catch {
    // Public metadata has safe router-level fallbacks.
  }
})
</script>

<template>
  <div id="app-shell">
    <section v-if="isMaintenance" class="maintenance-screen">
      <div class="maintenance-card">
        <span class="eyebrow"><i class="fa-solid fa-screwdriver-wrench"></i> Pemeliharaan Sistem</span>
        <h1>{{ layoutStore.site?.site_name || 'Gerakan Sedekah Umat' }} Sedang Dalam Pemeliharaan</h1>
        <p>
          Kami sedang melakukan peningkatan layanan agar pengalaman berbagi kebaikan Anda semakin nyaman.
          Silakan kembali beberapa saat lagi.
        </p>
        <a
          v-if="layoutStore.contact?.whatsapp"
          :href="'https://wa.me/' + String(layoutStore.contact.whatsapp).replace(/[^0-9]/g, '')"
          target="_blank"
          rel="noopener noreferrer"
          class="btn btn-primary"
        >
          <i class="fa-brands fa-whatsapp"></i> Hubungi Admin via WhatsApp
        </a>
      </div>
    </section>
    <template v-else-if="!isAdmin">
      <Navbar />
      <main>
        <router-view v-slot="{ Component }">
          <transition name="fade-page" mode="out-in">
            <component :is="Component" />
          </transition>
        </router-view>
      </main>
      <Footer />
    </template>
    <template v-else>
      <router-view />
    </template>
  </div>
</template>

<style>
.fade-page-enter-active, .fade-page-leave-active {
  transition: opacity 0.28s ease;
}
.fade-page-enter-from, .fade-page-leave-to {
  opacity: 0;
}
.maintenance-screen {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 24px;
  background: var(--color-accent);
  text-align: center;
}
.maintenance-card {
  max-width: 520px;
  background: var(--color-surface);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-md);
  padding: 40px 32px;
  box-shadow: var(--shadow-sm);
}
.maintenance-card h1 {
  margin-top: 16px;
  font-size: clamp(1.5rem, 3vw, 2rem);
}
.maintenance-card p {
  margin: 14px 0 24px;
  color: var(--color-text-muted);
  line-height: 1.7;
}
</style>
