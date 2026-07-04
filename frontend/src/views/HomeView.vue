<script setup>
import Hero from '../components/sections/Hero.vue'
import Stats from '../components/common/Stats.vue'
import About from '../components/sections/About.vue'
import ProgramSection from '../components/sections/ProgramSection.vue'
import Gallery from '../components/sections/Gallery.vue'
import Testimonial from '../components/common/Testimonial.vue'
import ProgressBar from '../components/common/ProgressBar.vue'
</script>

<template>
  <div>
    <Hero />
    <Stats />
    <About />

    <section class="section">
      <div class="container">
        <div class="section-head" v-reveal="'up'">
          <span class="eyebrow">Program Unggulan</span>
          <h2>Area dampak langsung tempat kontribusi Anda mengubah kehidupan.</h2>
        </div>
        <ProgramSection :limit="3" :show-filters="false" />
        <div class="section-cta">
          <router-link to="/program" class="btn btn-outline">Lihat Semua Program <i class="fa-solid fa-arrow-right"></i></router-link>
        </div>
      </div>
    </section>

    <section class="section section--muted">
      <div class="container campaign-banner" v-reveal="'up'">
        <div class="campaign-banner__text">
          <span class="eyebrow">Kampanye Khusus</span>
          <h2>Bantuan Ramadan 2026</h2>
          <p>Total target untuk kampanye khusus bulan ini.</p>
          <ProgressBar :current="850" :target="1200" color="primary" />
          <div class="campaign-banner__figures">
            <strong>Rp850Jt</strong> <span>/ Rp1.200Jt terkumpul</span>
          </div>
        </div>
        <router-link to="/donasi" class="btn btn-primary">Berikan Dampak Sekarang</router-link>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <div class="section-head section-head--center" v-reveal="'up'">
          <span class="eyebrow">Momen Harapan</span>
          <h2>Cerita visual dari garis depan misi bersama kita.</h2>
        </div>
        <Gallery :limit="8" />
        <div class="section-cta">
          <router-link to="/galeri" class="btn btn-outline">Temukan Galeri Lengkap</router-link>
        </div>
      </div>
    </section>

    <section class="section section--muted">
      <div class="container">
        <div class="section-head section-head--center" v-reveal="'up'">
          <span class="eyebrow">Testimoni</span>
          <h2>Kata mereka yang telah bergabung dalam gerakan ini.</h2>
        </div>
        <Testimonial />
      </div>
    </section>


  </div>
</template>

<style scoped>
.section-cta { display: flex; justify-content: center; margin-top: 44px; }

.campaign-banner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 40px;
  background: linear-gradient(120deg, var(--color-secondary-dark), var(--color-secondary));
  border-radius: var(--radius-lg);
  padding: 48px 52px;
  color: #fff;
}
.campaign-banner__text { max-width: 520px; }
.campaign-banner__text .eyebrow { background: rgba(255,255,255,0.15); color: #fff; }
.campaign-banner__text h2 { color: #fff; margin-top: 14px; font-size: 2rem; }
.campaign-banner__text p { color: rgba(255,255,255,0.8); margin-top: 10px; margin-bottom: 22px; }
.campaign-banner :deep(.pbar__track) { background: rgba(255,255,255,0.2); }
.campaign-banner :deep(.pbar__percent) { color: rgba(255,255,255,0.85); }
.campaign-banner__figures { margin-top: 12px; font-size: 0.9rem; color: rgba(255,255,255,0.85); }
.campaign-banner__figures strong { font-size: 1.3rem; font-family: var(--font-heading); margin-right: 6px; }

@media (max-width: 860px) {
  .campaign-banner { flex-direction: column; align-items: flex-start; padding: 36px; }
}
</style>
