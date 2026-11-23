<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import ProgressBar from '@/components/common/ProgressBar.vue'
import { programService } from '@/services/api/client'

const route = useRoute()
const program = ref(null)
const loading = ref(true)
const error = ref('')

const fallbackImage = 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=800&auto=format&fit=crop'

const currentAmount = computed(() => Number(program.value?.collected_amount ?? program.value?.collected ?? 0))
const targetAmount = computed(() => Number(program.value?.target_amount ?? program.value?.target ?? 0))

const percentage = computed(() => {
  if (!targetAmount.value || targetAmount.value <= 0) return 0
  return Math.min(100, Math.round((currentAmount.value / targetAmount.value) * 100))
})

function formatRupiah(value) {
  return 'Rp' + Number(value || 0).toLocaleString('id-ID')
}

onMounted(async () => {
  try {
    const response = await programService.get(route.params.slug)
    program.value = response.data
  } catch (err) {
    error.value = 'Program tidak ditemukan atau sedang tidak aktif.'
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div>
    <section class="page-header">
      <div class="container">
        <span class="eyebrow"><i class="fa-solid fa-layer-group"></i> Detail Program</span>
        <h1>{{ program?.title || 'Program' }}</h1>
        <p>{{ program?.short_description || program?.description || 'Memuat detail program...' }}</p>
      </div>
    </section>

    <section class="section">
      <div class="container detail">
        <p v-if="loading" class="detail__state">
          <i class="fa-solid fa-spinner fa-spin"></i> Memuat program...
        </p>
        <p v-else-if="error" class="detail__state detail__state--error">
          <i class="fa-solid fa-exclamation-triangle"></i> {{ error }}
        </p>

        <article v-else class="detail__grid">
          <div class="detail__image-wrapper">
            <img
              class="detail__image"
              :src="program.image || fallbackImage"
              :alt="program.title"
              @error="(e) => e.target.src = fallbackImage"
            />
          </div>
          <div class="detail__body">
            <span class="eyebrow">{{ program.category }}</span>
            <h2>{{ program.title }}</h2>
            <p>{{ program.description }}</p>

            <div class="detail__progress">
              <div class="detail__progress-header">
                <span>Capai target donasi</span>
                <small>{{ percentage }}% tercapai</small>
              </div>
              <ProgressBar :current="currentAmount" :target="targetAmount" color="secondary" />
              <div class="detail__figures">
                <div>
                  <span>Terkumpul</span>
                  <strong>{{ formatRupiah(currentAmount) }}</strong>
                </div>
                <div>
                  <span>Target</span>
                  <strong>{{ formatRupiah(targetAmount) }}</strong>
                </div>
              </div>
            </div>

            <div class="detail__actions">
              <router-link :to="{ path: '/donasi', query: { program: program.title } }" class="btn btn-primary btn-lg">
                <i class="fa-solid fa-heart"></i> Donasi Program Ini
              </router-link>
              <router-link to="/program" class="btn btn-outline">
                <i class="fa-solid fa-arrow-left"></i> Kembali
              </router-link>
            </div>
          </div>
        </article>
      </div>
    </section>
  </div>
</template>

<style scoped>
.page-header {
  position: relative;
  padding: 64px 0 44px;
  background: var(--color-accent);
  border-bottom: 1px solid var(--color-border);
}

.page-header .container { position: relative; z-index: 1; }

.page-header h1 {
  font-size: clamp(2.1rem, 4vw, 2.9rem);
  margin-top: 14px;
  font-weight: 650;
}

.page-header p {
  margin-top: 16px;
  max-width: 680px;
  color: var(--color-text-muted);
  font-size: 1.05rem;
  line-height: 1.7;
}

.detail__grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 48px;
  align-items: start;
}

.detail__image-wrapper {
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: var(--shadow-lg);
}

.detail__image {
  width: 100%;
  aspect-ratio: 4/3;
  object-fit: cover;
  display: block;
  transition: transform 0.5s ease;
}

.detail__image-wrapper:hover .detail__image {
  transform: scale(1.03);
}

.detail__body {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.detail__body h2 {
  font-size: clamp(1.5rem, 3vw, 2rem);
}

.detail__body p {
  color: var(--color-text-muted);
  line-height: 1.8;
  font-size: 0.98rem;
}

.detail__progress {
  background: var(--color-accent);
  border-radius: var(--radius-sm);
  padding: 22px;
}

.detail__progress-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
  font-size: 0.9rem;
  color: var(--color-text-muted);
}

.detail__progress-header small {
  font-weight: 700;
  color: var(--color-secondary-dark);
}

.detail__figures {
  display: flex;
  justify-content: space-between;
  gap: 16px;
  margin-top: 12px;
}

.detail__figures div {
  display: flex;
  flex-direction: column;
}

.detail__figures span {
  color: var(--color-text-muted);
  font-size: 0.82rem;
}

.detail__figures strong {
  color: var(--color-text);
  font-size: 1.1rem;
  margin-top: 2px;
}

.detail__actions {
  display: flex;
  gap: 14px;
  margin-top: 8px;
  flex-wrap: wrap;
}

.detail__state {
  text-align: center;
  color: var(--color-text-muted);
  padding: 60px 0;
  font-size: 1.05rem;
}

.detail__state i {
  margin-right: 8px;
}

.detail__state--error {
  color: var(--color-error);
}

@media (max-width: 860px) {
  .detail__grid {
    grid-template-columns: 1fr;
  }

  .detail__actions .btn {
    width: 100%;
  }
}
</style>
