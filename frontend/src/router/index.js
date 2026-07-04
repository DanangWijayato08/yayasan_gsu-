import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  { path: '/', name: 'home', component: () => import('../views/HomeView.vue'), meta: { title: 'Beranda' } },
  { path: '/tentang', name: 'tentang', component: () => import('../views/TentangView.vue'), meta: { title: 'Tentang Yayasan' } },
  { path: '/program', name: 'program', component: () => import('../views/ProgramView.vue'), meta: { title: 'Program' } },
  { path: '/donasi', name: 'donasi', component: () => import('../views/DonasiView.vue'), meta: { title: 'Donasi' } },
  { path: '/galeri', name: 'galeri', component: () => import('../views/GaleriView.vue'), meta: { title: 'Galeri' } },
  { path: '/kontak', name: 'kontak', component: () => import('../views/KontakView.vue'), meta: { title: 'Kontak' } },
  { path: '/login', name: 'login', component: () => import('../pages/LoginPage.vue'), meta: { title: 'Login Admin' } },
  { path: '/admin', name: 'admin', component: () => import('../views/AdminDashboardView.vue'), meta: { title: 'Dashboard Admin', layout: 'admin', requiresAuth: true } },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) return savedPosition
    return { top: 0 }
  }
})

router.beforeEach(async (to, from, next) => {
  if (to.meta.requiresAuth) {
    const { useAuthStore } = await import('@/stores/auth')
    const authStore = useAuthStore()
    
    if (authStore.isAuthenticated) {
      next()
    } else {
      next('/login')
    }
  } else {
    next()
  }
})

router.afterEach((to) => {
  document.title = to.meta?.title ? `${to.meta.title} · Gerakan Sedekah Umat` : 'Gerakan Sedekah Umat'
})

export default router
