import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const routes = [
  { path: '/', name: 'home', component: () => import('../views/HomeView.vue'), meta: { title: 'Beranda' } },
  { path: '/tentang', name: 'tentang', component: () => import('../views/TentangView.vue'), meta: { title: 'Tentang Yayasan' } },
  { path: '/program', name: 'program', component: () => import('../views/ProgramView.vue'), meta: { title: 'Program' } },
  { path: '/program/:slug', name: 'program-detail', component: () => import('../views/ProgramDetailView.vue'), meta: { title: 'Detail Program' } },
  { path: '/donasi', name: 'donasi', component: () => import('../views/DonasiView.vue'), meta: { title: 'Donasi' } },
  { path: '/galeri', name: 'galeri', component: () => import('../views/GaleriView.vue'), meta: { title: 'Galeri' } },
  { path: '/kontak', name: 'kontak', component: () => import('../views/KontakView.vue'), meta: { title: 'Kontak' } },
  { path: '/admin/login', name: 'admin-login', component: () => import('../views/LoginPage.vue'), meta: { title: 'Login Admin' } },
  { path: '/admin', name: 'admin', component: () => import('../views/admin/DashboardView.vue'), meta: { title: 'Dashboard Admin', layout: 'admin', requiresAuth: true } },
  { path: '/admin/homepage', name: 'admin-homepage', component: () => import('../views/admin/CmsView.vue'), meta: { title: 'CMS Homepage', layout: 'admin', requiresAuth: true, cms: 'homepage' } },
  { path: '/admin/about', name: 'admin-about', component: () => import('../views/admin/CmsView.vue'), meta: { title: 'CMS Tentang', layout: 'admin', requiresAuth: true, cms: 'about' } },
  { path: '/admin/programs', name: 'admin-programs', component: () => import('../views/admin/CmsView.vue'), meta: { title: 'CMS Program', layout: 'admin', requiresAuth: true, cms: 'programs' } },
  { path: '/admin/galleries', name: 'admin-galleries', component: () => import('../views/admin/CmsView.vue'), meta: { title: 'CMS Galeri', layout: 'admin', requiresAuth: true, cms: 'galleries' } },
  { path: '/admin/donation-settings', name: 'admin-donation-settings', component: () => import('../views/admin/CmsView.vue'), meta: { title: 'CMS Donasi', layout: 'admin', requiresAuth: true, cms: 'donation' } },
  { path: '/admin/messages', name: 'admin-messages', component: () => import('../views/admin/CmsView.vue'), meta: { title: 'Pesan Kontak', layout: 'admin', requiresAuth: true, cms: 'messages' } },
  { path: '/admin/contact-settings', name: 'admin-contact-settings', component: () => import('../views/admin/CmsView.vue'), meta: { title: 'CMS Kontak', layout: 'admin', requiresAuth: true, cms: 'contact' } },
  { path: '/admin/site-settings', name: 'admin-site-settings', component: () => import('../views/admin/CmsView.vue'), meta: { title: 'CMS Website', layout: 'admin', requiresAuth: true, cms: 'site' } },
  { path: '/admin/footer', name: 'admin-footer', component: () => import('../views/admin/CmsView.vue'), meta: { title: 'CMS Footer', layout: 'admin', requiresAuth: true, cms: 'footer' } },
  { path: '/:pathMatch(.*)*', name: 'not-found', component: () => import('../views/NotFoundView.vue'), meta: { title: 'Halaman Tidak Ditemukan' } },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) return savedPosition
    return { top: 0 }
  },
})

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()

  if (to.meta.requiresAuth) {
    if (!authStore.isAuthenticated) {
      return next('/admin/login')
    }

    if (!authStore.user) {
      const success = await authStore.fetchCurrentUser()
      if (!success) {
        return next('/admin/login')
      }
    }

    if (authStore.user?.role !== 'admin') {
      await authStore.logout()
      return next('/admin/login')
    }

    next()
  } else if (to.name === 'admin-login') {
    if (authStore.isAuthenticated) {
      if (!authStore.user) {
        await authStore.fetchCurrentUser()
      }
      if (authStore.user?.role === 'admin') {
        return next('/admin')
      }
    }
    next()
  } else {
    next()
  }
})

router.afterEach((to) => {
  document.title = to.meta?.title ? `${to.meta.title} - Gerakan Sedekah Umat` : 'Gerakan Sedekah Umat'
})

export default router
