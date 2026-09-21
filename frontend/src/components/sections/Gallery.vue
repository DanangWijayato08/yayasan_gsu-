<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { galleryService } from '@/services/api/client'

const props = defineProps({
  limit: { type: Number, default: 0 },
  initialImages: { type: Array, default: null },
  skipFetch: { type: Boolean, default: false },
})

const images = ref(normalize(props.initialImages || []))

function normalize(data) {
  const list = normalizeList(data)
  return list.map((item) => ({
    ...item,
    src: item.src || item.image,
    alt: item.alt || item.title || 'Galeri kegiatan',
  }))
}

function normalizeList(payload) {
  if (Array.isArray(payload)) return payload
  if (Array.isArray(payload?.data)) return payload.data
  if (Array.isArray(payload?.data?.data)) return payload.data.data
  if (Array.isArray(payload?.galleries)) return payload.galleries
  if (Array.isArray(payload?.gallery_preview)) return payload.gallery_preview
  return []
}

onMounted(async () => {
  if (props.initialImages || props.skipFetch) return
  try {
    const response = await galleryService.getAll()
    images.value = normalize(response)
  } catch {
    images.value = []
  }
})

watch(() => props.initialImages, (value) => {
  if (value) images.value = normalize(value)
})

const list = computed(() => {
  const source = normalizeList(images.value)
  return props.limit ? source.slice(0, props.limit) : source
})

const active = ref(null)

function open(img) { active.value = img }
function close() { active.value = null }
</script>

<template>
  <div class="gallery">
    <div class="gallery__grid">
      <button
        v-for="(img, i) in list"
        :key="img.src || i"
        class="gallery__item"
        :class="'gallery__item--' + ((i % 5) + 1)"
        @click="open(img)"
      >
        <img :src="img.src" :alt="img.alt" loading="lazy" />
        <div class="gallery__overlay">
          <div class="gallery__zoom"><i class="fa-solid fa-magnifying-glass-plus"></i></div>
        </div>
      </button>
    </div>

    <transition name="fade">
      <div v-if="active" class="lightbox" @click.self="close">
        <button class="lightbox__close" @click="close" aria-label="Tutup">
          <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="lightbox__content">
          <img :src="active.src" :alt="active.alt" />
          <div v-if="active.title || active.description" class="lightbox__info">
            <h3 v-if="active.title">{{ active.title }}</h3>
            <p v-if="active.description">{{ active.description }}</p>
          </div>
        </div>
        <button class="lightbox__nav lightbox__nav--prev" @click="close" aria-label="Tutup">
          <i class="fa-solid fa-chevron-left"></i>
        </button>
      </div>
    </transition>
  </div>
</template>

<style scoped>
.gallery__grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  grid-auto-rows: 160px;
  gap: 12px;
}

.gallery__item {
  position: relative;
  border-radius: var(--radius-sm);
  overflow: hidden;
  cursor: pointer;
  border: none;
  padding: 0;
  background: none;
}

.gallery__item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.gallery__item:hover img {
  transform: scale(1.1);
}

.gallery__item--1 { grid-row: span 2; grid-column: span 1; }
.gallery__item--4 { grid-row: span 2; }

.gallery__overlay {
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,0.3);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.gallery__item:hover .gallery__overlay {
  opacity: 1;
}

.gallery__zoom {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: rgba(255,255,255,0.2);
  backdrop-filter: blur(8px);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 1.1rem;
  transform: scale(0.8);
  transition: transform 0.3s ease;
}

.gallery__item:hover .gallery__zoom {
  transform: scale(1);
}

.lightbox {
  position: fixed;
  inset: 0;
  background: rgba(10,10,10,0.92);
  z-index: 200;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px;
  backdrop-filter: blur(8px);
}

.lightbox__content {
  position: relative;
  max-width: 85vw;
  max-height: 85vh;
}

.lightbox__content img {
  max-width: 85vw;
  max-height: 80vh;
  object-fit: contain;
  border-radius: var(--radius-md);
  box-shadow: 0 40px 80px rgba(0,0,0,0.4);
}

.lightbox__info {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 24px;
  background: linear-gradient(0deg, rgba(0,0,0,0.7) 0%, transparent);
  border-radius: 0 0 var(--radius-md) var(--radius-md);
  color: #fff;
}

.lightbox__info h3 { font-size: 1.1rem; margin-bottom: 4px; color: #fff; }
.lightbox__info p { font-size: 0.88rem; color: rgba(255,255,255,0.75); }

.lightbox__close {
  position: absolute;
  top: 24px;
  right: 28px;
  color: #fff;
  font-size: 1.6rem;
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: rgba(255,255,255,0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.2s ease;
}

.lightbox__close:hover {
  background: rgba(255,255,255,0.2);
}

.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

@media (max-width: 860px) {
  .gallery__grid {
    grid-template-columns: repeat(2, 1fr);
    grid-auto-rows: 140px;
  }
  .gallery__item--1,
  .gallery__item--4 {
    grid-row: span 1;
  }
}

@media (max-width: 520px) {
  .gallery__grid {
    grid-template-columns: repeat(2, 1fr);
    grid-auto-rows: 120px;
    gap: 8px;
  }
  .lightbox { padding: 16px; }
}
</style>
