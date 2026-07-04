<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

const scrolled = ref(false)
const menuOpen = ref(false)

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

onMounted(() => window.addEventListener('scroll', onScroll))
onUnmounted(() => window.removeEventListener('scroll', onScroll))
</script>

<template>
  <header class="navbar" :class="{ 'navbar--scrolled': scrolled }">
    <div class="container navbar__inner">
      <router-link to="/" class="navbar__brand" @click="menuOpen = false">
        <span class="navbar__mark"><i class="fa-solid fa-hand-holding-heart"></i></span>
        <span class="navbar__name">Gerakan<strong>Sedekah</strong>Umat</span>
      </router-link>

      <nav class="navbar__links" :class="{ 'navbar__links--open': menuOpen }">
        <router-link
          v-for="link in links"
          :key="link.to"
          :to="link.to"
          class="navbar__link"
          @click="menuOpen = false"
        >
          {{ link.label }}
        </router-link>
        <router-link to="/donasi" class="btn btn-primary navbar__cta-mobile" @click="menuOpen = false">
          Donasi Sekarang
        </router-link>
      </nav>

      <div class="navbar__actions">
        <router-link to="/donasi" class="btn btn-primary navbar__cta">Donasi Sekarang</router-link>
        
        <template v-if="authStore.isAuthenticated">
          <router-link to="/admin" class="navbar__admin-link">
            <i class="fa-solid fa-gauge"></i> Admin
          </router-link>
          <button @click="handleLogout" class="navbar__logout-btn">
            <i class="fa-solid fa-sign-out-alt"></i> Logout
          </button>
        </template>
        <template v-else>
          <router-link to="/login" class="navbar__login-link">
            <i class="fa-solid fa-sign-in-alt"></i> Admin Login
          </router-link>
        </template>

        <button class="navbar__burger" @click="menuOpen = !menuOpen" aria-label="Buka menu">
          <i :class="menuOpen ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'"></i>
        </button>
      </div>
    </div>
  </header>
</template>

<style scoped>
.navbar {
  position: sticky;
  top: 0;
  z-index: 100;
  background: rgba(255, 255, 255, 0.6);
  transition: background 0.3s ease, box-shadow 0.3s ease, backdrop-filter 0.3s ease;
  height: var(--header-h);
  display: flex;
  align-items: center;
}
.navbar--scrolled {
  background: rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(14px) saturate(160%);
  box-shadow: 0 1px 0 var(--color-border), 0 12px 24px rgba(0,0,0,0.04);
}

.navbar__inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
}

.navbar__brand {
  display: flex;
  align-items: center;
  gap: 10px;
  font-family: var(--font-heading);
  font-size: 1.15rem;
}
.navbar__mark {
  width: 38px;
  height: 38px;
  border-radius: 10px;
  background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.95rem;
}
.navbar__name strong { color: var(--color-secondary); font-weight: 700; }

.navbar__links {
  display: flex;
  align-items: center;
  gap: 32px;
}
.navbar__link {
  font-size: 0.94rem;
  font-weight: 500;
  color: var(--color-text);
  position: relative;
  padding: 6px 0;
}
.navbar__link::after {
  content: '';
  position: absolute;
  left: 0; bottom: 0;
  width: 0; height: 2px;
  background: var(--color-primary);
  transition: width 0.25s ease;
}
.navbar__link:hover::after,
.navbar__link.router-link-exact-active::after {
  width: 100%;
}

.navbar__actions { display: flex; align-items: center; gap: 16px; }
.navbar__burger {
  display: none;
  font-size: 1.4rem;
  color: var(--color-text);
}
.navbar__cta-mobile { display: none; }

.navbar__login-link,
.navbar__admin-link,
.navbar__logout-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.9rem;
  font-weight: 600;
  padding: 8px 12px;
  border-radius: 8px;
  transition: all 0.2s ease;
  border: none;
  cursor: pointer;
}

.navbar__login-link {
  color: var(--color-secondary);
  background: rgba(169, 137, 15, 0.1);
}

.navbar__login-link:hover {
  background: rgba(169, 137, 15, 0.2);
}

.navbar__admin-link {
  color: var(--color-primary);
  background: rgba(255, 193, 7, 0.1);
  text-decoration: none;
}

.navbar__admin-link:hover {
  background: rgba(255, 193, 7, 0.2);
}

.navbar__logout-btn {
  color: #e74c3c;
  background: rgba(231, 76, 60, 0.1);
}

.navbar__logout-btn:hover {
  background: rgba(231, 76, 60, 0.2);
}

@media (max-width: 960px) {
  .navbar__links {
    position: fixed;
    top: var(--header-h);
    left: 0; right: 0;
    background: #fff;
    flex-direction: column;
    align-items: flex-start;
    gap: 4px;
    padding: 16px 24px 28px;
    transform: translateY(-12px);
    opacity: 0;
    pointer-events: none;
    transition: all 0.25s ease;
    box-shadow: var(--shadow-md);
  }
  .navbar__links--open {
    transform: translateY(0);
    opacity: 1;
    pointer-events: auto;
  }
  .navbar__link { padding: 10px 0; width: 100%; }
  .navbar__cta { display: none; }
  .navbar__cta-mobile { display: inline-flex; margin-top: 10px; }
  .navbar__burger { display: block; }
  
  .navbar__login-link,
  .navbar__admin-link,
  .navbar__logout-btn {
    width: 100%;
    justify-content: flex-start;
  }
}
</style>
