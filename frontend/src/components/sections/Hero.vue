<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import Counter from '../common/Counter.vue'
import { publicService } from '@/services/publicService'

const props = defineProps({
  data: { type: Object, default: null },
  skipFetch: { type: Boolean, default: false },
})

const hero = ref(props.data)

const fallbackHero = {
  title: 'Gerakan Sedekah Umat',
  subtitle: 'Yayasan Amal Terpercaya',
  description: 'Menghubungkan kebaikan, memberdayakan masyarakat, dan membangun masa depan bersama.',
  image: 'https://images.unsplash.com/photo-1593113598332-cd288d649433?q=80&w=900&auto=format&fit=crop',
  button_primary_text: 'Donasi Sekarang',
  button_primary_link: '/donasi',
  button_secondary_text: 'Pelajari Program',
  button_secondary_link: '/program',
  extra_data: {
    donors: 15230,
    funds_distributed: 850000000,
    active_programs: 24,
  },
}

const content = computed(() => ({ ...fallbackHero, ...(hero.value || {}) }))
const stats = computed(() => ({ ...fallbackHero.extra_data, ...(content.value.extra_data || {}) }))
const fundsInMillion = computed(() => Math.round((Number(stats.value.funds_distributed) || 0) / 1000000))

onMounted(async () => {
  if (props.data || props.skipFetch) return
  try {
    const { data } = await publicService.homepage()
    hero.value = data?.hero || null
  } catch {
    hero.value = null
  }
})

watch(() => props.data, (value) => {
  if (value) hero.value = value
})
</script>

<template>
  <section class="hero">
    <div class="container hero__grid">
      <div class="hero__copy" v-reveal:0="'left'">
        <span class="eyebrow">
          <i class="fa-solid fa-shield-halved"></i> {{ content.subtitle || 'Yayasan Filantropi Terpercaya' }}
        </span>
        <h1 class="hero__title">{{ content.title }}</h1>
        <p class="hero__subtitle">
          {{ content.description }}
        </p>
        <div class="hero__actions">
          <router-link :to="content.button_primary_link || '/donasi'" class="btn btn-primary btn-lg">
            <i class="fa-solid fa-heart"></i> {{ content.button_primary_text }}
          </router-link>
          <router-link :to="content.button_secondary_link || '/program'" class="btn btn-outline btn-lg">
            {{ content.button_secondary_text }} <i class="fa-solid fa-arrow-right"></i>
          </router-link>
        </div>

        <div class="hero__trust">
          <div class="hero__trust-badges">
            <div class="hero__trust-pill">
              <i class="fa-solid fa-certificate"></i>
              <span>SK Resmi Kemenkumham</span>
            </div>
            <div class="hero__trust-pill">
              <i class="fa-solid fa-scale-balanced"></i>
              <span>Laporan Transparan</span>
            </div>
          </div>
          <p class="hero__trust-text">
            Menyalurkan amanah dari <strong>{{ Number(stats.donors || 0).toLocaleString('id-ID') }}+ donatur</strong> terverifikasi di seluruh Indonesia.
          </p>
        </div>
      </div>

      <div class="hero__visual" v-reveal:150="'right'">
        <div class="hero__image-frame">
          <img
            class="hero__image"
            :src="content.image"
            alt="Kegiatan sosial Gerakan Sedekah Umat"
          />
          <div class="hero__caption-card">
            <div class="hero__caption-header">
              <span class="hero__caption-dot"></span>
              <span class="hero__caption-tag">Dokumentasi Aksi Lapangan</span>
            </div>
            <p class="hero__caption-text">Penyaluran langsung kepada mustahik dengan pendampingan berkelanjutan.</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.hero {
  position: relative;
  padding: 56px 0 64px;
  background: var(--color-bg);
}

.hero__grid {
  display: grid;
  grid-template-columns: 1.05fr 0.95fr;
  gap: 56px;
  align-items: center;
}

.hero__title {
  font-size: clamp(2.35rem, 4.5vw, 3.4rem);
  line-height: 1.15;
  margin-top: 16px;
  color: var(--color-text);
  font-weight: 650;
  letter-spacing: -0.02em;
}

.hero__subtitle {
  margin-top: 20px;
  font-size: 1.12rem;
  color: var(--color-text-muted);
  max-width: 520px;
  line-height: 1.7;
}

.hero__actions {
  display: flex;
  gap: 14px;
  margin-top: 32px;
  flex-wrap: wrap;
}

.hero__trust {
  margin-top: 40px;
  padding-top: 28px;
  border-top: 1px solid var(--color-border);
}

.hero__trust-badges {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 12px;
}

.hero__trust-pill {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  font-size: 0.8rem;
  font-weight: 600;
  color: var(--color-secondary-dark);
  background: #FFFFFF;
  border: 1px solid var(--color-border);
  padding: 5px 12px;
  border-radius: var(--radius-sm);
  box-shadow: var(--shadow-xs);
}

.hero__trust-pill i {
  color: var(--color-primary);
  font-size: 0.82rem;
}

.hero__trust-text {
  font-size: 0.88rem;
  color: var(--color-text-muted);
  line-height: 1.5;
}

.hero__trust-text strong {
  color: var(--color-text);
}

.hero__visual {
  position: relative;
}

.hero__image-frame {
  position: relative;
  border-radius: var(--radius-lg);
  overflow: hidden;
  background: var(--color-surface);
  border: 1px solid var(--color-border);
  box-shadow: var(--shadow-md);
}

.hero__image {
  width: 100%;
  aspect-ratio: 4/4.2;
  object-fit: cover;
  display: block;
  transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

.hero__image-frame:hover .hero__image {
  transform: scale(1.02);
}

.hero__caption-card {
  position: absolute;
  bottom: 16px;
  left: 16px;
  right: 16px;
  background: rgba(255, 255, 255, 0.95);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-sm);
  padding: 14px 18px;
  box-shadow: var(--shadow-md);
}

.hero__caption-header {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 4px;
}

.hero__caption-dot {
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background: var(--color-success);
}

.hero__caption-tag {
  font-size: 0.76rem;
  font-weight: 700;
  letter-spacing: 0.05em;
  text-transform: uppercase;
  color: var(--color-secondary-dark);
}

.hero__caption-text {
  font-size: 0.85rem;
  color: var(--color-text-muted);
  line-height: 1.45;
  margin: 0;
}

@media (max-width: 960px) {
  .hero { padding: 36px 0 48px; }
  .hero__grid { grid-template-columns: 1fr; gap: 36px; }
  .hero__image { aspect-ratio: 16/10; }
}

@media (max-width: 520px) {
  .hero__title { font-size: 2rem; }
  .hero__actions { flex-direction: column; }
  .hero__actions .btn { width: 100%; }
}
</style>
