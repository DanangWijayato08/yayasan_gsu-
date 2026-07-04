import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import './assets/main.css'

const app = createApp(App)

const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add('is-visible')
      observer.unobserve(entry.target)
    }
  })
}, { threshold: 0.15 })

app.directive('reveal', {
  mounted(el, binding) {
    el.setAttribute('data-reveal', binding.value || 'up')
    if (binding.arg) el.style.transitionDelay = `${binding.arg}ms`
    observer.observe(el)
  }
})

app.use(createPinia())
app.use(router)
app.mount('#app')
