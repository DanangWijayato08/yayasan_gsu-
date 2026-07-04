<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import Navbar from './components/common/Navbar.vue'
import Footer from './components/common/Footer.vue'

const route = useRoute()
const isAdmin = computed(() => route.meta?.layout === 'admin')
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
