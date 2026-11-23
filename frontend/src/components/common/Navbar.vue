<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import { usePublicLayoutStore } from '@/stores/publicLayout'

const authStore = useAuthStore()
const router = useRouter()
const layoutStore = usePublicLayoutStore()

const scrolled = ref(false)
const menuOpen = ref(false)
const siteName = computed(() => layoutStore.site?.site_name || 'Gerakan Sedekah Umat')
const logoUrl = computed(() => layoutStore.site?.logo || '/logo.svg')

const links = [
  { to: '/', label: 'Beranda' },
  { to: '/tentang', label: 'Tentang' },
  { to: '/program', label: 'Program' },
  { to: '/donasi', label: 'Donasi' },
  { to: '/galeri', label: 'Galeri' },
  { to: '/kontak', label: 'Kontak' },
]

function onScroll() {
  scrolled.value = window.scrollY > 12
}

const handleLogout = async () => {
  await authStore.logout()
  router.push('/')
  menuOpen.value = false
}

onMounted(async () => {
  window.addEventListener('scroll', onScroll, { passive: true })
  await layoutStore.load()
})
onUnmounted(() => window.removeEventListener('scroll', onScroll))
</script>

<template>
  <header class="navbar" :class="{ 'navbar--scrolled': scrolled }">
    <div class="container navbar__inner">
      <!-- Brand Lockup -->
      <router-link to="/" class="navbar__brand" @click="menuOpen = false">
        <div class="navbar__logo-box">
          <img :src="logoUrl" :alt="siteName" width="38" height="38" class="navbar__logo" @error="(e) => e.target.src = '/logo.svg'" />
        </div>
        <div class="navbar__brand-info">
          <span class="navbar__name">{{ siteName }}</span>
          <span class="navbar__tagline">LEMBAGA AMAL &amp; WAKAF</span>
        </div>
      </router-link>

      <!-- Desktop Nav Capsule -->
      <nav class="navbar__nav-capsule" aria-label="Navigasi Utama">
        <router-link
          v-for="link in links"
          :key="link.to"
          :to="link.to"
          class="navbar__link"
        >
          {{ link.label }}
        </router-link>
      </nav>

      <!-- Right Actions (CTA + Auth + Burger) -->
      <div class="navbar__actions">
        <router-link to="/donasi" class="navbar__cta-btn">
          <span class="navbar__cta-icon-wrap">
            <i class="fa-solid fa-heart"></i>
          </span>
          <span class="navbar__cta-text">Donasi Sekarang</span>
        </router-link>

        <template v-if="authStore.isAuthenticated">
          <router-link to="/admin" class="navbar__admin-badge" title="Masuk ke Dashboard Admin">
            <i class="fa-solid fa-gauge-high"></i>
            <span class="navbar__admin-text">Admin</span>
          </router-link>
          <button @click="handleLogout" class="navbar__logout-btn" title="Keluar">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
          </button>
        </template>

        <button
          class="navbar__burger"
          @click="menuOpen = !menuOpen"
          :aria-label="menuOpen ? 'Tutup menu' : 'Buka menu'"
          :class="{ 'navbar__burger--active': menuOpen }"
        >
          <span class="burger-icon" :class="{ 'burger-icon--open': menuOpen }">
            <span></span>
            <span></span>
            <span></span>
          </span>
        </button>
      </div>
    </div>

    <!-- Mobile Drawer -->
    <div class="navbar__mobile-drawer" :class="{ 'navbar__mobile-drawer--open': menuOpen }">
      <div class="navbar__mobile-header">
        <div class="navbar__mobile-brand">
          <div class="navbar__logo-box navbar__logo-box--small">
            <img :src="logoUrl" :alt="siteName" class="navbar__mobile-logo" @error="(e) => e.target.src = '/logo.svg'" />
          </div>
          <div>
            <strong>{{ siteName }}</strong>
            <small>Lembaga Amal &amp; Wakaf</small>
          </div>
        </div>
        <button class="navbar__mobile-close" @click="menuOpen = false" aria-label="Tutup menu">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <nav class="navbar__mobile-nav">
        <router-link
          v-for="link in links"
          :key="link.to"
          :to="link.to"
          class="navbar__mobile-link"
          @click="menuOpen = false"
        >
          <span>{{ link.label }}</span>
          <i class="fa-solid fa-chevron-right"></i>
        </router-link>
      </nav>

      <div class="navbar__mobile-footer">
        <router-link to="/donasi" class="navbar__cta-mobile" @click="menuOpen = false">
          <i class="fa-solid fa-heart"></i>
          <span>Donasi Sekarang</span>
        </router-link>

        <template v-if="authStore.isAuthenticated">
          <div class="navbar__mobile-auth">
            <router-link to="/admin" class="navbar__mobile-admin" @click="menuOpen = false">
              <i class="fa-solid fa-gauge-high"></i> Panel Admin
            </router-link>
            <button @click="handleLogout" class="navbar__mobile-logout">
              <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
            </button>
          </div>
        </template>
      </div>
    </div>

    <div class="navbar__overlay" :class="{ 'navbar__overlay--visible': menuOpen }" @click="menuOpen = false"></div>
  </header>
</template>

<style scoped>
.navbar {
  position: sticky;
  top: 0;
  z-index: 100;
  height: 80px;
  display: flex;
  align-items: center;
  background: rgba(250, 248, 245, 0.85);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border-bottom: 1px solid rgba(229, 223, 211, 0.6);
  transition: height 0.3s cubic-bezier(0.2, 0.8, 0.2, 1),
              background 0.3s ease,
              border-color 0.3s ease,
              box-shadow 0.3s ease;
}

.navbar--scrolled {
  height: 72px;
  background: rgba(255, 255, 255, 0.95);
  border-bottom-color: rgba(229, 223, 211, 0.9);
  box-shadow: 0 4px 24px rgba(17, 38, 31, 0.05);
}

.navbar__inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  gap: 20px;
}

/* Brand Identity */
.navbar__brand {
  display: flex;
  align-items: center;
  gap: 12px;
  text-decoration: none;
  z-index: 2;
  flex-shrink: 0;
}

.navbar__logo-box {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  background: #FFFFFF;
  border: 1.5px solid rgba(229, 223, 211, 0.85);
  box-shadow: 0 2px 8px rgba(17, 38, 31, 0.06);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 5px;
  transition: all 0.25s cubic-bezier(0.2, 0.8, 0.2, 1);
  flex-shrink: 0;
}

.navbar__brand:hover .navbar__logo-box {
  transform: scale(1.05);
  border-color: #0C5A3E;
  box-shadow: 0 4px 14px rgba(12, 90, 62, 0.15);
}

.navbar__logo {
  width: 100%;
  height: 100%;
  object-fit: contain;
}

.navbar__brand-info {
  display: flex;
  flex-direction: column;
  gap: 1px;
}

.navbar__name {
  font-family: var(--font-heading);
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--color-text);
  line-height: 1.15;
  letter-spacing: -0.015em;
  transition: color 0.2s ease;
}

.navbar__brand:hover .navbar__name {
  color: var(--color-secondary);
}

.navbar__tagline {
  font-family: var(--font-body);
  font-size: 0.62rem;
  font-weight: 700;
  letter-spacing: 0.08em;
  color: #788A82;
  text-transform: uppercase;
}

/* Desktop Navigation Capsule */
.navbar__nav-capsule {
  display: flex;
  align-items: center;
  gap: 2px;
  background: rgba(255, 255, 255, 0.78);
  border: 1px solid rgba(229, 223, 211, 0.8);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border-radius: 9999px;
  padding: 4px 6px;
  box-shadow: 0 2px 10px rgba(17, 38, 31, 0.03);
  transition: all 0.3s ease;
}

.navbar--scrolled .navbar__nav-capsule {
  background: rgba(255, 255, 255, 0.92);
  border-color: rgba(205, 195, 180, 0.6);
  box-shadow: 0 3px 14px rgba(17, 38, 31, 0.05);
}

.navbar__link {
  font-family: var(--font-body);
  font-size: 0.88rem;
  font-weight: 600;
  color: #4A5B53;
  padding: 7px 15px;
  border-radius: 9999px;
  transition: all 0.22s cubic-bezier(0.2, 0.8, 0.2, 1);
  text-decoration: none;
  white-space: nowrap;
}

.navbar__link:hover {
  color: #0C5A3E;
  background: rgba(12, 90, 62, 0.06);
}

.navbar__link.router-link-exact-active {
  color: #FFFFFF;
  background: #0C5A3E;
  font-weight: 600;
  box-shadow: 0 2px 10px rgba(12, 90, 62, 0.25);
}

/* Right Actions */
.navbar__actions {
  display: flex;
  align-items: center;
  gap: 12px;
  z-index: 2;
  flex-shrink: 0;
}

/* Premium CTA Button */
.navbar__cta-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: linear-gradient(135deg, #0C5A3E 0%, #07402B 100%);
  color: #FFFFFF;
  border: 1.5px solid rgba(229, 195, 120, 0.45);
  padding: 9px 20px;
  border-radius: 9999px;
  font-family: var(--font-body);
  font-size: 0.88rem;
  font-weight: 700;
  letter-spacing: 0.01em;
  box-shadow: 0 4px 16px rgba(12, 90, 62, 0.24);
  cursor: pointer;
  transition: all 0.25s cubic-bezier(0.2, 0.8, 0.2, 1);
  text-decoration: none;
}

.navbar__cta-icon-wrap {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: #E5C378;
  font-size: 0.88rem;
  transition: transform 0.25s ease;
}

.navbar__cta-btn:hover {
  transform: translateY(-1.5px);
  background: linear-gradient(135deg, #0F6D4B 0%, #094F36 100%);
  border-color: #E5C378;
  box-shadow: 0 6px 22px rgba(12, 90, 62, 0.32);
}

.navbar__cta-btn:hover .navbar__cta-icon-wrap {
  transform: scale(1.22);
}

/* Admin Access & Logout */
.navbar__admin-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: #F0FDF4;
  color: #0F8A5F;
  border: 1px solid #BBF7D0;
  padding: 7px 14px;
  border-radius: 9999px;
  font-size: 0.82rem;
  font-weight: 700;
  text-decoration: none;
  transition: all 0.2s ease;
}

.navbar__admin-badge:hover {
  background: #DCFCE7;
  border-color: #86EFAC;
}

.navbar__logout-btn {
  background: #FEF2F2;
  color: #DC2626;
  border: 1px solid #FEE2E2;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 0.88rem;
  cursor: pointer;
  transition: all 0.2s ease;
}

.navbar__logout-btn:hover {
  background: #FEE2E2;
  color: #B91C1C;
}

/* Mobile Hamburger Button */
.navbar__burger {
  display: none;
  width: 42px;
  height: 42px;
  align-items: center;
  justify-content: center;
  border-radius: 12px;
  background: rgba(255, 255, 255, 0.8);
  border: 1px solid rgba(229, 223, 211, 0.9);
  cursor: pointer;
  transition: all 0.2s ease;
}

.navbar__burger:hover {
  background: #FFFFFF;
  border-color: #0C5A3E;
}

.burger-icon {
  display: flex;
  flex-direction: column;
  gap: 4px;
  width: 18px;
  height: 14px;
  justify-content: center;
}

.burger-icon span {
  display: block;
  height: 2px;
  background: #11261F;
  border-radius: 2px;
  transition: all 0.3s ease;
}

.burger-icon--open span:nth-child(1) {
  transform: rotate(45deg) translate(4px, 4px);
}
.burger-icon--open span:nth-child(2) {
  opacity: 0;
  transform: translateX(-6px);
}
.burger-icon--open span:nth-child(3) {
  transform: rotate(-45deg) translate(4px, -4px);
}

/* Mobile Drawer */
.navbar__mobile-drawer {
  display: none;
}

.navbar__overlay {
  display: none;
}

@media (max-width: 960px) {
  .navbar__nav-capsule {
    display: none;
  }

  .navbar__cta-btn {
    display: none;
  }

  .navbar__burger {
    display: flex;
  }

  .navbar__mobile-drawer {
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 0;
    right: 0;
    width: min(320px, 84vw);
    height: 100vh;
    background: #FFFFFF;
    box-shadow: -8px 0 36px rgba(17, 38, 31, 0.12);
    z-index: 120;
    transform: translateX(100%);
    transition: transform 0.35s cubic-bezier(0.2, 0.8, 0.2, 1);
    padding: 24px;
    overflow-y: auto;
  }

  .navbar__mobile-drawer--open {
    transform: translateX(0);
  }

  .navbar__mobile-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 18px;
    border-bottom: 1px solid #F1F5F9;
    margin-bottom: 20px;
  }

  .navbar__mobile-brand {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .navbar__logo-box--small {
    width: 38px;
    height: 38px;
    border-radius: 10px;
  }

  .navbar__mobile-logo {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }

  .navbar__mobile-brand strong {
    display: block;
    font-size: 0.95rem;
    color: #0F172A;
  }

  .navbar__mobile-brand small {
    display: block;
    font-size: 0.72rem;
    color: #64748B;
  }

  .navbar__mobile-close {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #475569;
    cursor: pointer;
  }

  .navbar__mobile-nav {
    display: flex;
    flex-direction: column;
    gap: 6px;
    flex: 1;
  }

  .navbar__mobile-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 14px;
    border-radius: 10px;
    font-size: 0.95rem;
    font-weight: 600;
    color: #334155;
    text-decoration: none;
    transition: all 0.2s ease;
  }

  .navbar__mobile-link i {
    font-size: 0.75rem;
    color: #94A3B8;
  }

  .navbar__mobile-link:hover {
    background: #F8FAFC;
    color: #0C5A3E;
  }

  .navbar__mobile-link.router-link-exact-active {
    background: #F0FDF4;
    color: #0C5A3E;
    font-weight: 700;
    border-left: 3px solid #0C5A3E;
  }

  .navbar__mobile-footer {
    padding-top: 20px;
    border-top: 1px solid #F1F5F9;
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  .navbar__cta-mobile {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: linear-gradient(135deg, #0C5A3E, #07402B);
    color: #FFFFFF;
    border: 1.5px solid rgba(229, 195, 120, 0.45);
    padding: 12px;
    border-radius: 9999px;
    font-weight: 700;
    font-size: 0.92rem;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(12, 90, 62, 0.22);
  }

  .navbar__cta-mobile i {
    color: #E5C378;
  }

  .navbar__mobile-auth {
    display: flex;
    gap: 8px;
  }

  .navbar__mobile-admin {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    background: #F0FDF4;
    color: #0F8A5F;
    border: 1px solid #BBF7D0;
    border-radius: 8px;
    padding: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
  }

  .navbar__mobile-logout {
    background: #FEF2F2;
    color: #DC2626;
    border: 1px solid #FEE2E2;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
  }

  .navbar__overlay {
    display: block;
    position: fixed;
    inset: 0;
    background: rgba(17, 38, 31, 0.4);
    backdrop-filter: blur(4px);
    z-index: 110;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
  }

  .navbar__overlay--visible {
    opacity: 1;
    pointer-events: auto;
  }
}

@media (max-width: 480px) {
  .navbar__tagline {
    display: none;
  }
  .navbar__name {
    font-size: 1.05rem;
  }
}
</style>
