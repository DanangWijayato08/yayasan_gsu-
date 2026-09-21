<script setup>
import { computed, onMounted, ref } from 'vue'
import Hero from '../components/sections/Hero.vue'
import Stats from '../components/common/Stats.vue'
import About from '../components/sections/About.vue'
import ProgramSection from '../components/sections/ProgramSection.vue'
import Gallery from '../components/sections/Gallery.vue'
import Testimonial from '../components/common/Testimonial.vue'
import ProgressBar from '../components/common/ProgressBar.vue'
import { publicService } from '@/services/publicService'

const donationCta = ref(null)
const homeData = ref(null)
const fallbackCta = {
  title: 'Bantuan Ramadan 2026',
  subtitle: 'Kampanye Khusus',
  description: 'Total target untuk kampanye khusus bulan ini.',
  button_primary_text: 'Berikan Dampak Sekarang',
  button_primary_link: '/donasi',
  extra_data: { current: 850000000, target: 1200000000 },
}

const cta = computed(() => ({ ...fallbackCta, ...(donationCta.value || {}) }))
const ctaData = computed(() => ({ ...fallbackCta.extra_data, ...(cta.value.extra_data || {}) }))
const currentMillion = computed(() => Math.round((Number(ctaData.value.current) || 0) / 1000000))
const targetMillion = computed(() => Math.round((Number(ctaData.value.target) || 1) / 1000000))
const featuredPrograms = computed(() => normalizeList(homeData.value?.featured_programs))
const galleryPreview = computed(() => normalizeList(homeData.value?.gallery_preview))

function normalizeList(payload) {
  if (Array.isArray(payload)) return payload
  if (Array.isArray(payload?.data)) return payload.data
  if (Array.isArray(payload?.data?.data)) return payload.data.data
  if (Array.isArray(payload?.programs)) return payload.programs
  if (Array.isArray(payload?.featured_programs)) return payload.featured_programs
  if (Array.isArray(payload?.galleries)) return payload.galleries
  if (Array.isArray(payload?.gallery_preview)) return payload.gallery_preview
  return []
}

function formatRupiah(value) {
  if (value >= 1000000000) return `Rp${(value / 1000000000).toLocaleString('id-ID', { minimumFractionDigits: 1 })}M`
  return `Rp${Math.round(value / 1000000).toLocaleString('id-ID')}Jt`
}

onMounted(async () => {
  try {
    const { data } = await publicService.home()
    homeData.value = data || null
    donationCta.value = data?.homepage_sections?.donation_cta || null
  } catch {
    donationCta.value = null
  }
})
</script>

<template>
  <div>
    <Hero :data="homeData?.homepage_sections?.hero" skip-fetch />
    <Stats :homepage="homeData?.homepage_sections" skip-fetch />
    <About :data="homeData?.about_page" skip-fetch />

    <section class="section">
      <div class="container">
        <div class="section-head" v-reveal="'up'">
          <span class="eyebrow">Program Unggulan</span>
          <h2>Area dampak langsung tempat kontribusi Anda mengubah kehidupan.</h2>
        </div>
        <ProgramSection :limit="3" :show-filters="false" :initial-programs="featuredPrograms" skip-fetch />
        <div class="section-cta">
          <router-link to="/program" class="btn btn-outline btn-lg">
            Lihat Semua Program <i class="fa-solid fa-arrow-right"></i>
          </router-link>
        </div>
      </div>
    </section>

    <section class="section section--muted">
      <div class="container">
        <div class="campaign-banner" v-reveal="'up'">
          <div class="campaign-banner__text">
            <span class="eyebrow eyebrow--light"><i class="fa-solid fa-bullhorn"></i> {{ cta.subtitle }}</span>
            <h2>{{ cta.title }}</h2>
            <p>{{ cta.description }}</p>
            <div class="campaign-banner__progress">
              <ProgressBar :current="currentMillion" :target="targetMillion" color="primary" />
              <div class="campaign-banner__figures">
                <strong>{{ formatRupiah(Number(ctaData.current) || 0) }}</strong>
                <span>dari {{ formatRupiah(Number(ctaData.target) || 0) }} terkumpul</span>
              </div>
            </div>
          </div>
          <router-link :to="cta.button_primary_link || '/donasi'" class="btn btn-primary btn-lg">
            <i class="fa-solid fa-heart"></i> {{ cta.button_primary_text || 'Donasi Sekarang' }}
          </router-link>
        </div>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <div class="section-head section-head--center" v-reveal="'up'">
          <span class="eyebrow"><i class="fa-solid fa-camera"></i> Dokumentasi Program</span>
          <h2>Cerita visual dari garis depan misi bersama kita.</h2>
        </div>
        <Gallery :limit="8" :initial-images="galleryPreview" skip-fetch />
        <div class="section-cta">
          <router-link to="/galeri" class="btn btn-outline btn-lg">Lihat Semua Dokumentasi</router-link>
        </div>
      </div>
    </section>

    <section class="section section--muted">
      <div class="container">
        <div class="section-head section-head--center" v-reveal="'up'">
          <span class="eyebrow"><i class="fa-solid fa-comment-dots"></i> Suara Donatur & Relawan</span>
          <h2>Kata mereka yang telah bergabung dalam gerakan ini.</h2>
        </div>
        <Testimonial />
      </div>
    </section>
  </div>
</template>

<style scoped>
.section-cta {
  display: flex;
  justify-content: center;
  margin-top: 40px;
}

.campaign-banner {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 48px;
  background: var(--color-secondary-dark);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: var(--radius-lg);
  padding: 48px;
  color: #fff;
}

.campaign-banner__text {
  max-width: 520px;
  position: relative;
  z-index: 1;
}

.eyebrow--light {
  background: rgba(255, 255, 255, 0.12) !important;
  border-color: rgba(255, 255, 255, 0.2) !important;
  color: #FFFFFF !important;
}

.campaign-banner__text h2 {
  color: #fff;
  margin-top: 12px;
  font-size: clamp(1.75rem, 3vw, 2.3rem);
}

.campaign-banner__text p {
  color: rgba(255, 255, 255, 0.8);
  margin-top: 10px;
  margin-bottom: 22px;
  font-size: 1.02rem;
}

.campaign-banner :deep(.pbar__track) {
  background: rgba(255, 255, 255, 0.18);
}

.campaign-banner :deep(.pbar__percent) {
  color: rgba(255, 255, 255, 0.85);
}

.campaign-banner__progress {
  max-width: 380px;
}

.campaign-banner__figures {
  margin-top: 12px;
  font-size: 0.9rem;
  color: rgba(255, 255, 255, 0.8);
  display: flex;
  align-items: baseline;
  gap: 8px;
}

.campaign-banner__figures strong {
  font-size: 1.3rem;
  font-family: var(--font-heading);
  color: var(--color-primary-light);
}

.campaign-banner > .btn {
  flex-shrink: 0;
}

@media (max-width: 860px) {
  .campaign-banner {
    flex-direction: column;
    align-items: flex-start;
    padding: 36px;
    gap: 24px;
  }

  .campaign-banner > .btn {
    width: 100%;
  }
}

@media (max-width: 520px) {
  .campaign-banner { padding: 28px 24px; }
}
</style>
