<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import ProgramCard from './ProgramCard.vue'
import { programService } from '@/services/api/client'

const props = defineProps({
  limit: { type: Number, default: 0 },
  showFilters: { type: Boolean, default: true },
  initialPrograms: { type: Array, default: null },
  skipFetch: { type: Boolean, default: false },
})

const filters = ['Semua', 'Pendidikan', 'Sosial', 'Ekonomi', 'Kesehatan']
const active = ref('Semua')
const programs = ref(normalizeList(props.initialPrograms))

function normalizeList(payload) {
  if (Array.isArray(payload)) return payload
  if (Array.isArray(payload?.data)) return payload.data
  if (Array.isArray(payload?.data?.data)) return payload.data.data
  if (Array.isArray(payload?.programs)) return payload.programs
  if (Array.isArray(payload?.featured_programs)) return payload.featured_programs
  return []
}

onMounted(async () => {
  if (props.initialPrograms || props.skipFetch) return
  try {
    const response = await programService.getAll()
    programs.value = normalizeList(response)
  } catch {
    programs.value = []
  }
})

watch(() => props.initialPrograms, (value) => {
  programs.value = normalizeList(value)
})

const filtered = computed(() => {
  const source = normalizeList(programs.value)
  const list = active.value === 'Semua' ? source : source.filter(p => p?.category === active.value)
  return props.limit ? list.slice(0, props.limit) : list
})
</script>

<template>
  <div class="psection">
    <div v-if="showFilters" class="psection__filters">
      <button
        v-for="f in filters"
        :key="f"
        class="psection__filter"
        :class="{ 'psection__filter--active': active === f }"
        @click="active = f"
      >
        {{ f }}
      </button>
    </div>

    <div class="psection__grid">
      <div v-for="(p, i) in filtered" :key="p.id" v-reveal:[i*80]="'up'">
        <ProgramCard :program="p" />
      </div>
    </div>

    <p v-if="!filtered.length" class="psection__empty">Belum ada program pada kategori ini.</p>
  </div>
</template>

<style scoped>
.psection__filters {
  display: flex;
  gap: 8px;
  margin-bottom: 36px;
  flex-wrap: wrap;
}

.psection__filter {
  padding: 10px 22px;
  border-radius: var(--radius-full);
  border: 1.5px solid var(--color-border);
  font-size: 0.88rem;
  font-weight: 600;
  color: var(--color-text-muted);
  transition: all 0.25s ease;
  background: rgba(255,255,255,0.6);
}

.psection__filter:hover {
  border-color: var(--color-secondary);
  color: var(--color-secondary);
  background: rgba(15, 138, 95, 0.04);
}

.psection__filter--active {
  background: var(--color-secondary);
  border-color: var(--color-secondary);
  color: #fff;
  box-shadow: var(--shadow-green);
}

.psection__grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
}

.psection__empty {
  text-align: center;
  color: var(--color-text-muted);
  padding: 60px 0;
}

@media (max-width: 960px) {
  .psection__grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 640px) {
  .psection__grid { grid-template-columns: 1fr; }
  .psection__filters { gap: 6px; }
  .psection__filter { font-size: 0.82rem; padding: 8px 16px; }
}
</style>
