<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { publicService } from '@/services/publicService'

const props = defineProps({
  data: { type: Object, default: null },
  skipFetch: { type: Boolean, default: false },
})

const about = ref(props.data)

const fallback = {
  title: 'Membangun warisan kasih sayang melalui aksi kolektif.',
  description: 'Yayasan Gerakan Sedekah Umat hadir untuk mengelola amanah kebaikan secara profesional, transparan, dan berdampak bagi masyarakat.',
  vision: 'Menjadi gerakan sosial Islam yang terpercaya dalam menebar manfaat dan pemberdayaan umat.',
  mission: ['Menghimpun sedekah secara amanah.', 'Menyalurkan bantuan tepat sasaran.', 'Membangun program pemberdayaan berkelanjutan.'],
  values: ['Amanah', 'Transparan', 'Humanis', 'Berkelanjutan'],
  image: 'https://images.unsplash.com/photo-1509099836639-18ba1795216d?q=80&w=900&auto=format&fit=crop',
  legal_info: 'Informasi legalitas dapat diperbarui melalui dashboard admin.',
}

const content = computed(() => ({ ...fallback, ...(about.value || {}) }))
const missions = computed(() => Array.isArray(content.value.mission) ? content.value.mission : fallback.mission)
const values = computed(() => Array.isArray(content.value.values) ? content.value.values : fallback.values)

onMounted(async () => {
  if (props.data || props.skipFetch) return
  try {
    const { data } = await publicService.about()
    about.value = data || null
  } catch {
    about.value = null
  }
})

watch(() => props.data, (value) => {
  if (value) about.value = value
})
</script>

<template>
  <section class="about section">
    <div class="container about__grid">
      <div class="about__media" v-reveal="'left'">
        <div class="about__image-wrapper">
          <div class="about__image-tag">
            <i class="fa-solid fa-landmark"></i> Profil Yayasan
          </div>
          <img :src="content.image" alt="Kegiatan Yayasan Gerakan Sedekah Umat" />
        </div>
        <div class="about__badge">
          <span>Nilai Kelembagaan</span>
          <strong>Amanah & Profesional</strong>
        </div>
      </div>

      <div class="about__content" v-reveal:150="'right'">
        <span class="eyebrow"><i class="fa-solid fa-users"></i> Tentang Kami</span>
        <h2>{{ content.title }}</h2>
        <p class="about__lead">{{ content.description }}</p>

        <div class="about__pillars">
          <div class="about__pillar about__pillar--wide">
            <h3><i class="fa-solid fa-eye"></i> Visi Lembaga</h3>
            <p>{{ content.vision }}</p>
          </div>
          <div class="about__pillar">
            <h3><i class="fa-solid fa-bullseye"></i> Misi Utama</h3>
            <ul>
              <li v-for="item in missions" :key="item">
                <i class="fa-solid fa-check-circle"></i>
                <span>{{ item }}</span>
              </li>
            </ul>
          </div>
          <div class="about__pillar">
            <h3><i class="fa-solid fa-heart"></i> Nilai Pokok</h3>
            <div class="about__chips">
              <span v-for="item in values" :key="item">{{ item }}</span>
            </div>
          </div>
        </div>

        <p v-if="content.legal_info" class="about__legal">
          <i class="fa-solid fa-scale-balanced"></i>
          {{ content.legal_info }}
        </p>
      </div>
    </div>
  </section>
</template>

<style scoped>
.about__grid {
  display: grid;
  grid-template-columns: 0.9fr 1.1fr;
  gap: 64px;
  align-items: start;
}

.about__media { position: relative; }

.about__image-wrapper {
  position: relative;
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: var(--shadow-md);
  border: 1px solid var(--color-border);
}

.about__image-tag {
  position: absolute;
  top: 18px;
  left: 18px;
  z-index: 2;
  background: rgba(255, 255, 255, 0.95);
  padding: 6px 14px;
  border-radius: var(--radius-sm);
  font-size: 0.76rem;
  font-weight: 700;
  color: var(--color-secondary-dark);
  border: 1px solid var(--color-border);
  box-shadow: var(--shadow-xs);
  display: flex;
  align-items: center;
  gap: 6px;
}

.about__image-wrapper img {
  width: 100%;
  aspect-ratio: 3/3.6;
  object-fit: cover;
  display: block;
  transition: transform 0.6s ease;
}

.about__image-wrapper:hover img {
  transform: scale(1.02);
}

.about__badge {
  position: absolute;
  bottom: -16px;
  right: -16px;
  background: var(--color-surface);
  border: 1px solid var(--color-border);
  color: var(--color-text);
  padding: 16px 20px;
  border-radius: var(--radius-sm);
  text-align: left;
  box-shadow: var(--shadow-md);
  z-index: 3;
}
.about__badge span { display: block; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.06em; color: var(--color-text-muted); font-weight: 600; margin-bottom: 2px; }
.about__badge strong { font-size: 1.15rem; font-family: var(--font-heading); color: var(--color-secondary-dark); }

.about__content h2 {
  font-size: clamp(1.65rem, 3vw, 2.2rem);
  margin-top: 16px;
}

.about__lead {
  margin-top: 18px;
  color: var(--color-text-muted);
  font-size: 1.02rem;
  line-height: 1.8;
}

.about__pillars {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 16px;
  margin-top: 36px;
}

.about__pillar {
  background: var(--color-accent);
  border-radius: var(--radius-sm);
  padding: 22px;
  transition: transform 0.3s ease;
}

.about__pillar:hover {
  transform: translateY(-2px);
}

.about__pillar--wide { grid-column: 1 / -1; }

.about__pillar h3 {
  font-family: var(--font-body);
  font-size: 0.92rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 8px;
  color: var(--color-secondary-dark);
  margin-bottom: 12px;
}

.about__pillar p {
  font-size: 0.9rem;
  color: var(--color-text-muted);
  line-height: 1.7;
}

.about__pillar ul {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.about__pillar li {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  font-size: 0.88rem;
  color: var(--color-text-muted);
}

.about__pillar li i {
  color: var(--color-secondary);
  margin-top: 3px;
  flex-shrink: 0;
}

.about__chips {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-top: 4px;
}

.about__chips span {
  border-radius: var(--radius-full);
  background: #fff;
  border: 1px solid var(--color-border);
  color: var(--color-secondary-dark);
  padding: 8px 14px;
  font-size: 0.84rem;
  font-weight: 700;
  transition: all 0.25s ease;
}

.about__chips span:hover {
  background: var(--color-primary);
  border-color: var(--color-primary);
  color: #2a2205;
  transform: translateY(-2px);
}

.about__legal {
  margin-top: 26px;
  padding: 16px 20px;
  border-left: 4px solid var(--color-primary);
  color: var(--color-text-muted);
  background: rgba(214, 168, 79, 0.06);
  border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
  font-size: 0.9rem;
  display: flex;
  gap: 10px;
  align-items: flex-start;
}

.about__legal i {
  color: var(--color-primary);
  margin-top: 3px;
  flex-shrink: 0;
}

@media (max-width: 900px) {
  .about__grid { grid-template-columns: 1fr; }
  .about__pillars { grid-template-columns: 1fr; }
  .about__media { max-width: 420px; margin: 0 auto; }
}
</style>
