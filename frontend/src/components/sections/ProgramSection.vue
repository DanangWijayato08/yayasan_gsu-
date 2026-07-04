<script setup>
import { ref, computed } from 'vue'
import ProgramCard from './ProgramCard.vue'

const props = defineProps({
  limit: { type: Number, default: 0 },
  showFilters: { type: Boolean, default: true },
})

const filters = ['Semua', 'Pendidikan', 'Sosial', 'Ekonomi', 'Kesehatan']
const active = ref('Semua')

const programs = [
  { id: 1, title: 'Lumbung Pangan Masyarakat', category: 'Sosial', description: 'Menyediakan dukungan nutrisi esensial bagi lebih dari 500 keluarga setiap bulannya.', collected: 42, target: 60, image: 'https://images.unsplash.com/photo-1593113646773-028c64a8f1b8?q=80&w=700&auto=format&fit=crop' },
  { id: 2, title: 'Beasiswa Masa Depan Cerah', category: 'Pendidikan', description: 'Memberdayakan 50 anak yatim dengan cakupan akademik penuh hingga lulus.', collected: 38, target: 50, image: 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?q=80&w=700&auto=format&fit=crop' },
  { id: 3, title: 'Renovasi Masjid Sejahtera', category: 'Sosial', description: 'Merenovasi pusat komunitas untuk kenyamanan lebih dari 2.000 jemaah.', collected: 85, target: 85, image: 'https://images.unsplash.com/photo-1519817650390-64a93db51149?q=80&w=700&auto=format&fit=crop' },
  { id: 4, title: 'Pemeriksaan Kesehatan Gratis', category: 'Kesehatan', description: 'Unit medis keliling menyediakan pemeriksaan dan layanan kesehatan dasar.', collected: 20, target: 35, image: 'https://images.unsplash.com/photo-1584515933487-779824d29309?q=80&w=700&auto=format&fit=crop' },
  { id: 5, title: 'Proyek Perpustakaan Masyarakat', category: 'Pendidikan', description: 'Membangun perpustakaan digital dan fisik untuk menumbuhkan budaya literasi.', collected: 18, target: 40, image: 'https://images.unsplash.com/photo-1521587760476-6c12a4b040da?q=80&w=700&auto=format&fit=crop' },
  { id: 6, title: 'Modal Usaha Mikro Mandiri', category: 'Ekonomi', description: 'Bantuan modal bergulir bagi pelaku UMKM perempuan kepala keluarga.', collected: 27, target: 45, image: 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?q=80&w=700&auto=format&fit=crop' },
]

const filtered = computed(() => {
  const list = active.value === 'Semua' ? programs : programs.filter(p => p.category === active.value)
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
  gap: 10px;
  margin-bottom: 36px;
  flex-wrap: wrap;
}
.psection__filter {
  padding: 10px 20px;
  border-radius: 999px;
  border: 1.5px solid var(--color-border);
  font-size: 0.88rem;
  font-weight: 600;
  color: var(--color-text-muted);
  transition: all 0.2s ease;
}
.psection__filter:hover { border-color: var(--color-secondary); color: var(--color-secondary); }
.psection__filter--active {
  background: var(--color-secondary);
  border-color: var(--color-secondary);
  color: #fff;
}
.psection__grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 28px;
}
.psection__empty { text-align: center; color: var(--color-text-muted); padding: 40px 0; }

@media (max-width: 960px) {
  .psection__grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 640px) {
  .psection__grid { grid-template-columns: 1fr; }
}
</style>
