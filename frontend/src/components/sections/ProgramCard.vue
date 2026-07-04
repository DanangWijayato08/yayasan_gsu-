<script setup>
import ProgressBar from '../common/ProgressBar.vue'

defineProps({
  program: { type: Object, required: true },
})
</script>

<template>
  <article class="pcard card">
    <div class="pcard__media">
      <img :src="program.image" :alt="program.title" loading="lazy" />
      <span class="pcard__category">{{ program.category }}</span>
    </div>
    <div class="pcard__body">
      <h3>{{ program.title }}</h3>
      <p>{{ program.description }}</p>

      <div class="pcard__progress">
        <ProgressBar :current="program.collected" :target="program.target" color="secondary" />
        <div class="pcard__amounts">
          <span>Terkumpul <strong>Rp{{ program.collected.toLocaleString('id-ID') }}Jt</strong></span>
          <span>Target <strong>Rp{{ program.target.toLocaleString('id-ID') }}Jt</strong></span>
        </div>
      </div>

      <router-link to="/donasi" class="btn btn-outline pcard__btn">
        Lihat Detail <i class="fa-solid fa-arrow-right"></i>
      </router-link>
    </div>
  </article>
</template>

<style scoped>
.pcard { overflow: hidden; display: flex; flex-direction: column; }
.pcard__media { position: relative; aspect-ratio: 4/3; overflow: hidden; }
.pcard__media img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
.pcard:hover .pcard__media img { transform: scale(1.06); }
.pcard__category {
  position: absolute;
  top: 14px; left: 14px;
  background: rgba(255,255,255,0.92);
  color: var(--color-secondary-dark);
  font-size: 0.72rem;
  font-weight: 700;
  padding: 6px 12px;
  border-radius: 999px;
  text-transform: uppercase;
  letter-spacing: 0.03em;
}
.pcard__body { padding: 22px; display: flex; flex-direction: column; gap: 14px; flex: 1; }
.pcard__body h3 { font-size: 1.1rem; }
.pcard__body p {
  color: var(--color-text-muted);
  font-size: 0.9rem;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.pcard__amounts {
  display: flex;
  justify-content: space-between;
  font-size: 0.78rem;
  color: var(--color-text-muted);
  margin-top: 8px;
}
.pcard__amounts strong { color: var(--color-text); }
.pcard__btn { margin-top: auto; width: 100%; }
</style>
