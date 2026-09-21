<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import Counter from './Counter.vue'
import ProgressBar from './ProgressBar.vue'
import { publicService } from '@/services/publicService'

const props = defineProps({
  homepage: { type: Object, default: null },
  skipFetch: { type: Boolean, default: false },
})

const homepage = ref(props.homepage || {})

const heroStats = computed(() => homepage.value?.hero?.extra_data || {})
const campaign = computed(() => homepage.value?.donation_cta?.extra_data || {})

const stats = computed(() => [
  { icon: 'fa-people-group', value: Number(heroStats.value.donors) || 15230, suffix: '+', label: 'Donatur Aktif' },
  { icon: 'fa-diagram-project', value: Number(heroStats.value.active_programs) || 24, suffix: '', label: 'Program Berjalan' },
  { icon: 'fa-hand-holding-heart', value: Number(heroStats.value.beneficiaries) || 8500, suffix: '+', label: 'Penerima Manfaat' },
])

const campaignCurrent = computed(() => Number(campaign.value.current) || Number(heroStats.value.funds_distributed) || 850000000)
const campaignTarget = computed(() => Number(campaign.value.target) || 1200000000)
const progressCurrent = computed(() => Math.round(campaignCurrent.value / 1000000))
const progressTarget = computed(() => Math.round(campaignTarget.value / 1000000))

function formatRupiah(value) {
  if (value >= 1000000000) return `Rp${(value / 1000000000).toLocaleString('id-ID', { minimumFractionDigits: 1 })}M`
  return `Rp${Math.round(value / 1000000).toLocaleString('id-ID')}Jt`
}

onMounted(async () => {
  if (props.homepage || props.skipFetch) return
  try {
    const { data } = await publicService.homepage()
    homepage.value = data || {}
  } catch {
    homepage.value = {}
  }
})

watch(() => props.homepage, (value) => {
  if (value) homepage.value = value
})
</script>

<template>
  <section class="stats">
    <div class="container">
      <div class="stats__grid">
        <div v-for="(s, i) in stats" :key="s.label" class="stats__item" v-reveal:[i*100]="'up'">
          <div class="stats__icon"><i :class="'fa-solid ' + s.icon"></i></div>
          <strong class="stats__value"><Counter :target="s.value" :suffix="s.suffix" /></strong>
          <span class="stats__label">{{ s.label }}</span>
        </div>
      </div>

      <div class="stats__progress" v-reveal:300="'up'">
        <div class="stats__progress-head">
          <span>Target Tercapai</span>
          <strong>{{ formatRupiah(campaignCurrent) }} / {{ formatRupiah(campaignTarget) }}</strong>
        </div>
        <ProgressBar :current="progressCurrent" :target="progressTarget" color="primary" />
      </div>
    </div>
  </section>
</template>

<style scoped>
.stats {
  padding: 44px 0 52px;
  background: var(--color-secondary-dark);
  color: #fff;
  position: relative;
  border-top: 1px solid rgba(255, 255, 255, 0.08);
  border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}

.stats__grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
}

.stats__item {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  padding: 20px 16px;
  text-align: center;
  position: relative;
}

.stats__item + .stats__item::before {
  content: '';
  position: absolute;
  left: 0;
  top: 15%;
  height: 70%;
  width: 1px;
  background: rgba(255, 255, 255, 0.12);
}

.stats__icon {
  width: 44px;
  height: 44px;
  border-radius: var(--radius-sm);
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.12);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--color-primary-light);
  font-size: 1.05rem;
  margin-bottom: 6px;
}

.stats__value {
  font-size: 2.3rem;
  font-family: var(--font-heading);
  letter-spacing: -0.02em;
  color: #FFFFFF;
  line-height: 1;
}

.stats__label {
  color: rgba(255, 255, 255, 0.75);
  font-size: 0.88rem;
  font-weight: 500;
}

.stats__progress {
  margin-top: 24px;
  background: rgba(0, 0, 0, 0.18);
  border-radius: var(--radius-sm);
  padding: 20px 24px;
  border: 1px solid rgba(255, 255, 255, 0.08);
}

.stats__progress-head {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
  font-size: 0.88rem;
  flex-wrap: wrap;
  gap: 8px;
  color: rgba(255, 255, 255, 0.8);
}

.stats__progress-head strong {
  color: var(--color-primary-light);
  font-weight: 600;
}

.stats__progress :deep(.pbar__track) { background: rgba(255, 255, 255, 0.15); }
.stats__progress :deep(.pbar__percent) { color: rgba(255, 255, 255, 0.8); }

@media (max-width: 760px) {
  .stats__grid { grid-template-columns: 1fr; gap: 0; }
  .stats__item + .stats__item::before { display: none; }
  .stats__item { padding: 18px 16px; }
  .stats__item + .stats__item { border-top: 1px solid rgba(255, 255, 255, 0.08); }
}
</style>
