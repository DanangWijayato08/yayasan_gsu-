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
    <template v-if="!isAdmin">
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
</style>
