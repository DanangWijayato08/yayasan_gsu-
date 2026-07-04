<script setup>
import { ref } from 'vue'

const testimonials = [
  { name: 'Rina Amelia', role: 'Donatur Bulanan', avatar: 'https://i.pravatar.cc/80?img=47', rating: 5, quote: 'Setiap laporan program disampaikan dengan sangat transparan. Saya merasa donasi saya benar-benar sampai ke yang membutuhkan.' },
  { name: 'Ahmad Fauzi', role: 'Relawan', avatar: 'https://i.pravatar.cc/80?img=15', rating: 5, quote: 'Bergabung sebagai relawan di GSU membuka mata saya tentang betapa besarnya dampak kecil yang dilakukan bersama-sama.' },
  { name: 'Siti Nurhaliza', role: 'Donatur', avatar: 'https://i.pravatar.cc/80?img=48', rating: 4, quote: 'Proses donasi sangat mudah dan cepat, ditambah update program yang rutin membuat saya semakin percaya.' },
]

const index = ref(0)
function next() { index.value = (index.value + 1) % testimonials.length }
function prev() { index.value = (index.value - 1 + testimonials.length) % testimonials.length }
</script>

<template>
  <div class="testi">
    <div class="testi__track">
      <transition name="slide" mode="out-in">
        <div :key="index" class="testi__card">
          <div class="testi__stars">
            <i v-for="n in 5" :key="n" class="fa-solid fa-star" :class="{ 'testi__star--off': n > testimonials[index].rating }"></i>
          </div>
          <p class="testi__quote">&ldquo;{{ testimonials[index].quote }}&rdquo;</p>
          <div class="testi__person">
            <img :src="testimonials[index].avatar" :alt="testimonials[index].name" />
            <div>
              <strong>{{ testimonials[index].name }}</strong>
              <span>{{ testimonials[index].role }}</span>
            </div>
          </div>
        </div>
      </transition>
    </div>

    <div class="testi__controls">
      <button @click="prev" aria-label="Sebelumnya"><i class="fa-solid fa-arrow-left"></i></button>
      <div class="testi__dots">
        <span v-for="(t, i) in testimonials" :key="i" :class="{ 'testi__dot--active': i === index }" @click="index = i"></span>
      </div>
      <button @click="next" aria-label="Selanjutnya"><i class="fa-solid fa-arrow-right"></i></button>
    </div>
  </div>
</template>

<style scoped>
.testi { max-width: 640px; margin: 0 auto; text-align: center; }
.testi__card { padding: 8px; }
.testi__stars { color: var(--color-primary); font-size: 1rem; margin-bottom: 18px; }
.testi__star--off { color: var(--color-border); }
.testi__quote { font-size: 1.25rem; font-family: var(--font-heading); color: var(--color-text); line-height: 1.5; }
.testi__person { display: flex; align-items: center; justify-content: center; gap: 12px; margin-top: 28px; }
.testi__person img { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; }
.testi__person div { text-align: left; }
.testi__person strong { display: block; font-size: 0.95rem; }
.testi__person span { font-size: 0.82rem; color: var(--color-text-muted); }

.testi__controls { display: flex; align-items: center; justify-content: center; gap: 22px; margin-top: 36px; }
.testi__controls button {
  width: 42px; height: 42px;
  border-radius: 50%;
  border: 1.5px solid var(--color-border);
  color: var(--color-text);
  transition: all 0.2s ease;
}
.testi__controls button:hover { background: var(--color-secondary); border-color: var(--color-secondary); color: #fff; }
.testi__dots { display: flex; gap: 8px; }
.testi__dots span { width: 8px; height: 8px; border-radius: 50%; background: var(--color-border); cursor: pointer; transition: background 0.2s ease; }
.testi__dots .testi__dot--active { background: var(--color-primary); width: 22px; border-radius: 4px; }

.slide-enter-active, .slide-leave-active { transition: all 0.35s ease; }
.slide-enter-from { opacity: 0; transform: translateX(20px); }
.slide-leave-to { opacity: 0; transform: translateX(-20px); }
</style>
