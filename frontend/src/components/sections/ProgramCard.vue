<script setup>
import { computed } from 'vue'
import ProgressBar from '../common/ProgressBar.vue'

const props = defineProps({
  program: { type: Object, required: true },
})

function formatRupiah(value) {
  return 'Rp' + Number(value || 0).toLocaleString('id-ID')
}

const fallbackImage = 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=800&auto=format&fit=crop'

const catColors = {
  Pendidikan: { bg: '#EBF5FF', text: '#1E6BB8', dot: '#1E6BB8' },
  Sosial: { bg: '#FFF1F0', text: '#C0392B', dot: '#C0392B' },
  Ekonomi: { bg: '#F0FFF4', text: '#1B7A3D', dot: '#1B7A3D' },
  Kesehatan: { bg: '#FEF3E2', text: '#B8860B', dot: '#B8860B' },
}

const catStyle = computed(() => catColors[props.program.category] || { bg: '#F6F1E8', text: '#10221A', dot: '#10221A' })
</script>

<template>
  <article class="pcard card">
    <div class="pcard__media">
      <img
        :src="program.image || fallbackImage"
        :alt="program.title"
        loading="lazy"
        @error="$event.target.src = fallbackImage"
      />
      <div class="pcard__media-overlay"></div>
      <span
        class="pcard__category"
        :style="{ background: catStyle.bg, color: catStyle.text }"
      >
        <span class="pcard__cat-dot" :style="{ background: catStyle.dot }"></span>
        {{ program.category }}
      </span>
    </div>
    <div class="pcard__body">
      <h3>{{ program.title }}</h3>
      <p>{{ program.short_description || program.description }}</p>

      <div class="pcard__progress">
        <ProgressBar
          :current="program.collected_amount || program.collected"
          :target="program.target_amount || program.target"
          color="secondary"
        />
        <div class="pcard__amounts">
          <span>Terkumpul <strong>{{ formatRupiah(program.collected_amount || program.collected) }}</strong></span>
          <span>Target <strong>{{ formatRupiah(program.target_amount || program.target) }}</strong></span>
        </div>
      </div>

      <router-link
        :to="program.slug ? `/program/${program.slug}` : '/donasi'"
        class="btn btn-outline pcard__btn"
      >
        Lihat Detail <i class="fa-solid fa-arrow-right"></i>
      </router-link>
    </div>
  </article>
</template>

<style scoped>
.pcard {
  overflow: hidden;
  display: flex;
  flex-direction: column;
  height: 100%;
}

.pcard__media {
  position: relative;
  aspect-ratio: 4/3;
  overflow: hidden;
}

.pcard__media img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.pcard__media-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(0deg, rgba(0,0,0,0.15) 0%, transparent 50%);
  z-index: 1;
  opacity: 0;
  transition: opacity 0.4s ease;
}

.pcard:hover .pcard__media img { transform: scale(1.08); }
.pcard:hover .pcard__media-overlay { opacity: 1; }

.pcard__category {
  position: absolute;
  top: 14px;
  left: 14px;
  z-index: 2;
  font-size: 0.72rem;
  font-weight: 700;
  padding: 5px 10px;
  border-radius: var(--radius-xs);
  text-transform: uppercase;
  letter-spacing: 0.05em;
  display: flex;
  align-items: center;
  gap: 6px;
  box-shadow: var(--shadow-xs);
}

.pcard__cat-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
}

.pcard__body {
  padding: 22px;
  display: flex;
  flex-direction: column;
  gap: 14px;
  flex: 1;
}

.pcard__body h3 {
  font-family: var(--font-heading);
  font-size: 1.25rem;
  font-weight: 650;
  line-height: 1.25;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.pcard__body p {
  color: var(--color-text-muted);
  font-size: 0.9rem;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  line-height: 1.6;
}

.pcard__amounts {
  display: flex;
  justify-content: space-between;
  font-size: 0.78rem;
  color: var(--color-text-muted);
  margin-top: 8px;
}

.pcard__amounts strong {
  color: var(--color-text);
}

.pcard__btn {
  margin-top: auto;
  width: 100%;
}
</style>
