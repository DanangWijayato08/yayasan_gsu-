<script setup>
import { computed, ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import { usePublicLayoutStore } from '@/stores/publicLayout'

const authStore = useAuthStore()
const router = useRouter()
const layoutStore = usePublicLayoutStore()
const menuOpen = ref(false)

const siteName = computed(() => layoutStore.site?.site_name || 'Yayasan Sedekah Umat')
const logoUrl = computed(() => layoutStore.site?.logo || null)

onMounted(async () => {
  await layoutStore.load()
})

defineProps({
  title: { type: String, required: true },
  subtitle: { type: String, default: '' },
})

const nav = [
  { to: '/admin', icon: 'fa-chart-pie', label: 'Dashboard' },
  { to: '/admin/homepage', icon: 'fa-layer-group', label: 'Homepage' },
  { to: '/admin/about', icon: 'fa-landmark', label: 'Tentang' },
  { to: '/admin/programs', icon: 'fa-hand-holding-heart', label: 'Program' },
  { to: '/admin/galleries', icon: 'fa-images', label: 'Galeri' },
  { to: '/admin/donation-settings', icon: 'fa-coins', label: 'Donasi' },
  { to: '/admin/messages', icon: 'fa-envelope-open-text', label: 'Pesan' },
  { to: '/admin/contact-settings', icon: 'fa-address-book', label: 'Kontak' },
  { to: '/admin/site-settings', icon: 'fa-sliders', label: 'Website' },
  { to: '/admin/footer', icon: 'fa-shoe-prints', label: 'Footer' },
]

async function logout() {
  await authStore.logout()
  router.push('/admin/login')
}
</script>

<template>
  <div class="ashell" :class="{ 'ashell--open': menuOpen }">
    <aside class="ashell__side">
      <router-link to="/" class="ashell__brand">
        <div class="ashell__brand-icon">
          <img v-if="logoUrl" :src="logoUrl" alt="Logo" class="ashell__brand-logo" />
          <i v-else class="fa-solid fa-shield-halved"></i>
        </div>
        <div class="ashell__brand-text">
          <strong>GSU CMS</strong>
          <span>{{ siteName }}</span>
        </div>
      </router-link>

      <div class="ashell__section-label">Navigasi Utama</div>
      <nav class="ashell__nav">
        <router-link
          v-for="item in nav"
          :key="item.to"
          :to="item.to"
          class="ashell__link"
          :class="{ 'ashell__link--exact': item.to === '/admin' }"
          @click="menuOpen = false"
        >
          <i :class="'fa-solid ' + item.icon"></i>
          <span>{{ item.label }}</span>
        </router-link>
      </nav>

      <div class="ashell__foot">
        <router-link to="/" class="ashell__ghost">
          <i class="fa-solid fa-arrow-up-right-from-square"></i>
          <span>Lihat Website</span>
        </router-link>
        <button class="ashell__logout" @click="logout">
          <i class="fa-solid fa-right-from-bracket"></i>
          <span>Keluar (Logout)</span>
        </button>
      </div>
    </aside>

    <main class="ashell__main">
      <header class="ashell__top">
        <button class="ashell__menu" type="button" @click="menuOpen = true" aria-label="Buka menu admin">
          <i class="fa-solid fa-bars"></i>
        </button>
        <div class="ashell__header-info">
          <span class="ashell__eyebrow"><i class="fa-solid fa-circle-check"></i> Panel Administrasi</span>
          <h1>{{ title }}</h1>
          <p v-if="subtitle" class="ashell__subtitle">{{ subtitle }}</p>
        </div>
        <div class="ashell__user">
          <div class="ashell__user-info">
            <span class="ashell__user-name">{{ authStore.user?.name || 'Administrator' }}</span>
            <small class="ashell__user-role">Super Admin</small>
          </div>
          <div class="ashell__avatar">
            {{ (authStore.user?.name || 'A').charAt(0).toUpperCase() }}
          </div>
        </div>
      </header>

      <div class="ashell__content">
        <slot />
      </div>
    </main>

    <button v-if="menuOpen" class="ashell__backdrop" type="button" aria-label="Tutup menu admin" @click="menuOpen = false"></button>
  </div>
</template>

<style scoped>
.ashell {
  min-height: 100vh;
  display: flex;
  background: #F8FAF9;
  color: #1E293B;
  position: relative;
}

.ashell__side {
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  width: 270px;
  height: 100vh;
  padding: 24px 18px;
  background: #08241A;
  color: rgba(255, 255, 255, 0.72);
  display: flex;
  flex-direction: column;
  border-right: 1px solid rgba(255, 255, 255, 0.08);
  box-shadow: 2px 0 12px rgba(0, 0, 0, 0.08);
  z-index: 50;
  overflow: hidden;
}

.ashell__brand {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 8px 10px 20px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  text-decoration: none;
}

.ashell__brand-icon {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  display: grid;
  place-items: center;
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.14);
  color: #E5C378;
  font-size: 1.15rem;
  overflow: hidden;
}

.ashell__brand-logo {
  width: 100%;
  height: 100%;
  object-fit: contain;
  padding: 3px;
  background: #ffffff;
}

.ashell__brand-text strong {
  display: block;
  color: #FFFFFF;
  font-size: 1.05rem;
  font-weight: 700;
  letter-spacing: -0.01em;
}

.ashell__brand-text span {
  display: block;
  font-size: 0.72rem;
  color: rgba(255, 255, 255, 0.5);
  letter-spacing: 0.02em;
}

.ashell__section-label {
  font-size: 0.68rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: rgba(255, 255, 255, 0.4);
  margin: 20px 8px 10px;
}

.ashell__nav {
  display: flex;
  flex-direction: column;
  gap: 3px;
  flex: 1;
  overflow-y: auto;
  padding-right: 4px;
}

.ashell__nav::-webkit-scrollbar {
  width: 4px;
}

.ashell__nav::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.15);
  border-radius: 4px;
}

.ashell__link,
.ashell__ghost,
.ashell__logout {
  display: flex;
  align-items: center;
  gap: 12px;
  border-radius: 9px;
  padding: 10px 14px;
  font-size: 0.88rem;
  color: rgba(255, 255, 255, 0.72);
  border: 0;
  background: transparent;
  text-align: left;
  text-decoration: none;
  transition: all 0.2s ease;
  font-weight: 500;
  position: relative;
}

.ashell__link:hover,
.ashell__ghost:hover {
  background: rgba(255, 255, 255, 0.08);
  color: #FFFFFF;
}

/* Elegant high-trust active state: no yellow box */
.ashell__link.router-link-active:not(.ashell__link--exact),
.ashell__link--exact.router-link-exact-active {
  background: rgba(255, 255, 255, 0.12);
  color: #FFFFFF;
  font-weight: 600;
  border-left: 3.5px solid #E5C378;
  border-radius: 0 9px 9px 0;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.ashell__link.router-link-active:not(.ashell__link--exact) i,
.ashell__link--exact.router-link-exact-active i {
  color: #E5C378;
}

.ashell__link i {
  width: 18px;
  text-align: center;
  font-size: 0.95rem;
}

.ashell__foot {
  margin-top: 16px;
  padding-top: 16px;
  border-top: 1px solid rgba(255, 255, 255, 0.08);
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.ashell__ghost {
  font-size: 0.84rem;
  color: rgba(255, 255, 255, 0.6);
}

.ashell__logout {
  cursor: pointer;
  color: #FCA5A5;
  font-size: 0.84rem;
}

.ashell__logout:hover {
  background: rgba(239, 68, 68, 0.15);
  color: #FECACA;
}

.ashell__main {
  margin-left: 270px;
  width: calc(100% - 270px);
  min-width: 0;
  min-height: 100vh;
  padding: 32px clamp(24px, 4vw, 56px);
}

.ashell__top {
  display: flex;
  justify-content: space-between;
  gap: 24px;
  align-items: center;
  margin-bottom: 28px;
  padding-bottom: 20px;
  border-bottom: 1px solid #E2E8F0;
}

.ashell__header-info {
  flex: 1;
  min-width: 0;
}

.ashell__eyebrow {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  color: #0F8A5F;
  font-weight: 700;
  font-size: 0.72rem;
  text-transform: uppercase;
  letter-spacing: 0.06em;
  margin-bottom: 4px;
  background: rgba(15, 138, 95, 0.08);
  padding: 3px 9px;
  border-radius: 9999px;
}

.ashell__top h1 {
  font-size: clamp(1.6rem, 2.8vw, 2.1rem);
  font-weight: 700;
  color: #0F172A;
  margin: 4px 0 3px;
  letter-spacing: -0.025em;
  font-family: var(--font-heading);
}

.ashell__subtitle {
  color: #64748B;
  font-size: 0.92rem;
  margin: 0;
  line-height: 1.5;
}

.ashell__user {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 6px 8px 6px 16px;
  border-radius: 9999px;
  background: #FFFFFF;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  border: 1px solid #E2E8F0;
  flex-shrink: 0;
}

.ashell__user-info {
  display: flex;
  flex-direction: column;
  text-align: right;
}

.ashell__user-name {
  font-weight: 600;
  font-size: 0.88rem;
  color: #0F172A;
  line-height: 1.2;
}

.ashell__user-role {
  font-size: 0.7rem;
  color: #0F8A5F;
  font-weight: 700;
}

.ashell__avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: linear-gradient(135deg, #0F8A5F, #0A5A3E);
  color: #FFFFFF;
  display: grid;
  place-items: center;
  font-weight: 700;
  font-size: 0.92rem;
  box-shadow: 0 2px 6px rgba(15, 138, 95, 0.25);
}

.ashell__menu {
  display: none;
  width: 42px;
  height: 42px;
  border-radius: 10px;
  background: #FFFFFF;
  color: #0F172A;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
  place-items: center;
  border: 1px solid #E2E8F0;
  cursor: pointer;
}

.ashell__backdrop {
  display: none;
}

@media (max-width: 960px) {
  .ashell {
    display: block;
  }

  .ashell__side {
    position: fixed;
    inset: 0 auto 0 0;
    width: min(82vw, 300px);
    height: 100vh;
    z-index: 50;
    transform: translateX(-105%);
    transition: transform 0.3s cubic-bezier(0.22, 1, 0.36, 1);
  }

  .ashell--open .ashell__side {
    transform: translateX(0);
  }

  .ashell__backdrop {
    display: block;
    position: fixed;
    inset: 0;
    z-index: 40;
    background: rgba(10, 20, 15, 0.5);
    backdrop-filter: blur(4px);
  }

  .ashell__menu {
    display: grid;
  }

  .ashell__main {
    margin-left: 0;
    width: 100%;
    padding: 24px 18px 40px;
  }
}

@media (max-width: 600px) {
  .ashell__user-info {
    display: none;
  }

  .ashell__user {
    padding: 4px;
    border-radius: 50%;
  }
}
</style>
