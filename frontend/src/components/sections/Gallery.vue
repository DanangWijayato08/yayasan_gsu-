<script setup>
import { ref } from 'vue'

const props = defineProps({
  limit: { type: Number, default: 0 },
})

const images = [
  { src: 'https://images.unsplash.com/photo-1509099836639-18ba1795216d?q=80&w=600&auto=format&fit=crop', alt: 'Distribusi bantuan pangan' },
  { src: 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=600&auto=format&fit=crop', alt: 'Belajar bersama anak-anak' },
  { src: 'https://images.unsplash.com/photo-1593113598332-cd288d649433?q=80&w=600&auto=format&fit=crop', alt: 'Kegiatan sosial relawan' },
  { src: 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?q=80&w=600&auto=format&fit=crop', alt: 'Pemeriksaan kesehatan gratis' },
  { src: 'https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=600&auto=format&fit=crop', alt: 'Penyerahan beasiswa' },
  { src: 'https://images.unsplash.com/photo-1541480609187-5f381d95246f?q=80&w=600&auto=format&fit=crop', alt: 'Relawan berbagi kebaikan' },
  { src: 'https://images.unsplash.com/photo-1560252829-804f1aedf1be?q=80&w=600&auto=format&fit=crop', alt: 'Kunjungan panti asuhan' },
  { src: 'https://images.unsplash.com/photo-1615461066841-6116e61058f4?q=80&w=600&auto=format&fit=crop', alt: 'Momen kebersamaan' },
]

const list = props.limit ? images.slice(0, props.limit) : images
const active = ref(null)

function open(img) { active.value = img }
function close() { active.value = null }
</script>

<template>
  <div class="gallery">
    <div class="gallery__grid">
      <button
        v-for="(img, i) in list"
        :key="img.src"
        class="gallery__item"
        :class="'gallery__item--' + ((i % 5) + 1)"
        @click="open(img)"
      >
        <img :src="img.src" :alt="img.alt" loading="lazy" />
        <span class="gallery__zoom"><i class="fa-solid fa-magnifying-glass-plus"></i></span>
      </button>
    </div>

    <transition name="fade">
      <div v-if="active" class="lightbox" @click.self="close">
        <button class="lightbox__close" @click="close" aria-label="Tutup"><i class="fa-solid fa-xmark"></i></button>
        <img :src="active.src" :alt="active.alt" />
      </div>
    </transition>
  </div>
</template>

<style scoped>
.gallery__grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  grid-auto-rows: 160px;
  gap: 14px;
}
.gallery__item {
  position: relative;
  border-radius: var(--radius-sm);
  overflow: hidden;
  cursor: pointer;
}
.gallery__item img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
.gallery__item:hover img { transform: scale(1.08); }
.gallery__item--1 { grid-row: span 2; }
.gallery__item--4 { grid-row: span 2; grid-column: span 1; }

.gallery__zoom {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  background: rgba(0,0,0,0.25);
  opacity: 0;
  transition: opacity 0.3s ease;
  font-size: 1.1rem;
}
.gallery__item:hover .gallery__zoom { opacity: 1; }

.lightbox {
  position: fixed;
  inset: 0;
  background: rgba(10,10,10,0.9);
  z-index: 200;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px;
}
.lightbox img { max-width: 90vw; max-height: 85vh; border-radius: var(--radius-sm); object-fit: contain; }
.lightbox__close {
  position: absolute;
  top: 28px; right: 32px;
  color: #fff;
  font-size: 1.6rem;
}
.fade-enter-active, .fade-leave-active { transition: opacity 0.25s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

@media (max-width: 860px) {
  .gallery__grid { grid-template-columns: repeat(2, 1fr); grid-auto-rows: 140px; }
}
</style>
