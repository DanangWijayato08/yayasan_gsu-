<script setup>
import { computed, onMounted, ref } from 'vue'
import AdminShell from '@/components/admin/AdminShell.vue'
import { adminService } from '@/services/adminService'

const loading = ref(true)
const error = ref('')
const dashboard = ref(null)

const cards = computed(() => [
  { label: 'Program Aktif', value: dashboard.value?.programs_active ?? 0, icon: 'fa-diagram-project', color: '#0F8A5F' },
  { label: 'Total Galeri', value: dashboard.value?.galleries_total ?? 0, icon: 'fa-images', color: '#2563EB' },
  { label: 'Pesan Masuk', value: dashboard.value?.messages_total ?? 0, icon: 'fa-envelope', color: '#D97706' },
  { label: 'Belum Dibaca', value: dashboard.value?.messages_unread ?? 0, icon: 'fa-bell', color: '#DC2626' },
])

onMounted(async () => {
  try {
    dashboard.value = (await adminService.dashboard()).data
  } catch (err) {
    error.value = 'Dashboard belum bisa dimuat.'
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <AdminShell title="Dashboard" subtitle="Ringkasan aktivitas website, statistik konten, dan interaksi pengunjung.">
    <!-- Skeleton Loading -->
    <section v-if="loading" class="dash__cards">
      <article v-for="i in 4" :key="i" class="dash__card dash__card--skeleton">
        <span class="skel-icon"></span>
        <span class="skel-label"></span>
        <span class="skel-value"></span>
      </article>
    </section>

    <!-- Error State -->
    <div v-else-if="error" class="dash__alert-error">
      <i class="fa-solid fa-triangle-exclamation"></i>
      <span>{{ error }}</span>
    </div>

    <!-- Content -->
    <template v-else>
      <!-- Stats Cards -->
      <section class="dash__cards">
        <article
          v-for="card in cards"
          :key="card.label"
          class="dash__card"
          :style="{ '--accent-color': card.color }"
        >
          <div class="dash__card-header">
            <div class="dash__card-icon" :style="{ background: card.color + '12', color: card.color, borderColor: card.color + '25' }">
              <i :class="'fa-solid ' + card.icon"></i>
            </div>
            <span class="dash__card-badge" :style="{ background: card.color + '0E', color: card.color }">
              Statistik
            </span>
          </div>
          <div class="dash__card-body">
            <span class="dash__card-label">{{ card.label }}</span>
            <strong class="dash__card-value">{{ card.value }}</strong>
          </div>
        </article>
      </section>

      <!-- Recent Messages Panel -->
      <section class="dash__panel">
        <div class="dash__panel-head">
          <div class="dash__panel-title-group">
            <div class="dash__panel-icon">
              <i class="fa-solid fa-inbox"></i>
            </div>
            <div>
              <h2 class="dash__panel-title">Pesan Masuk Terbaru</h2>
              <p class="dash__panel-sub">Daftar pertanyaan dan saran terbaru dari formulir kontak publik.</p>
            </div>
          </div>
          <router-link to="/admin/messages" class="dash__panel-more">
            <span>Lihat Semua Pesan</span>
            <i class="fa-solid fa-arrow-right"></i>
          </router-link>
        </div>

        <div v-if="!dashboard?.latest_messages?.length" class="dash__empty">
          <div class="dash__empty-icon">
            <i class="fa-regular fa-folder-open"></i>
          </div>
          <p class="dash__empty-text">Belum ada pesan masuk saat ini.</p>
        </div>

        <ul v-else class="dash__messages">
          <li v-for="message in dashboard.latest_messages" :key="message.id" class="dash__msg-item">
            <div class="dash__msg-avatar">
              {{ (message.name || 'U').charAt(0).toUpperCase() }}
            </div>
            <div class="dash__msg-content">
              <div class="dash__msg-head">
                <div class="dash__msg-sender">
                  <strong class="dash__msg-name">{{ message.name || 'Pengunjung' }}</strong>
                  <span v-if="message.subject" class="dash__msg-subject">&bull; {{ message.subject }}</span>
                </div>
                <span
                  class="dash__msg-status"
                  :class="'dash__msg-status--' + (message.status || 'unread')"
                >
                  <span class="status-dot"></span>
                  {{ message.status === 'read' ? 'Sudah Dibaca' : 'Belum Dibaca' }}
                </span>
              </div>
              <p class="dash__msg-body">{{ message.message }}</p>
              <div class="dash__msg-meta">
                <span><i class="fa-regular fa-envelope"></i> {{ message.email }}</span>
                <span v-if="message.created_at"><i class="fa-regular fa-clock"></i> {{ message.created_at }}</span>
              </div>
            </div>
          </li>
        </ul>
      </section>
    </template>
  </AdminShell>
</template>

<style scoped>
.dash__cards {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 20px;
}

.dash__card {
  background: #ffffff;
  border: 1px solid rgba(8, 36, 26, 0.08);
  border-radius: 18px;
  box-shadow: 0 4px 20px -2px rgba(8, 36, 26, 0.04);
  padding: 22px;
  display: flex;
  flex-direction: column;
  gap: 16px;
  position: relative;
  overflow: hidden;
  transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}

.dash__card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: var(--accent-color, #0F8A5F);
  opacity: 0.85;
}

.dash__card:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 28px -4px rgba(8, 36, 26, 0.08);
  border-color: rgba(15, 138, 95, 0.2);
}

.dash__card-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.dash__card-icon {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  border: 1px solid transparent;
  display: grid;
  place-items: center;
  font-size: 1.15rem;
}

.dash__card-badge {
  font-size: 0.72rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  padding: 4px 8px;
  border-radius: 6px;
}

.dash__card-body {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.dash__card-label {
  color: #64748B;
  font-size: 0.82rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.04em;
}

.dash__card-value {
  font-size: 2.25rem;
  font-weight: 800;
  line-height: 1.1;
  color: #08241A;
  letter-spacing: -0.03em;
  font-family: var(--font-heading);
}

.dash__panel {
  margin-top: 28px;
  background: #ffffff;
  border: 1px solid rgba(8, 36, 26, 0.08);
  border-radius: 20px;
  box-shadow: 0 4px 24px -2px rgba(8, 36, 26, 0.04);
  padding: 28px;
}

.dash__panel-head {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-bottom: 20px;
  border-bottom: 1px solid #F1F5F9;
  margin-bottom: 20px;
  flex-wrap: wrap;
  gap: 16px;
}

.dash__panel-title-group {
  display: flex;
  align-items: center;
  gap: 14px;
}

.dash__panel-icon {
  width: 42px;
  height: 42px;
  border-radius: 12px;
  background: rgba(15, 138, 95, 0.08);
  color: #0F8A5F;
  display: grid;
  place-items: center;
  font-size: 1.1rem;
}

.dash__panel-title {
  font-size: 1.2rem;
  font-weight: 700;
  color: #08241A;
  margin: 0;
  font-family: var(--font-heading);
}

.dash__panel-sub {
  font-size: 0.84rem;
  color: #64748B;
  margin: 2px 0 0 0;
}

.dash__panel-more {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  color: #0F8A5F;
  font-weight: 700;
  font-size: 0.85rem;
  padding: 8px 14px;
  border-radius: 8px;
  background: rgba(15, 138, 95, 0.06);
  border: 1px solid rgba(15, 138, 95, 0.15);
  text-decoration: none;
  transition: all 0.2s ease;
}

.dash__panel-more:hover {
  background: #0F8A5F;
  color: #ffffff;
}

.dash__empty {
  padding: 48px 24px;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
}

.dash__empty-icon {
  width: 56px;
  height: 56px;
  border-radius: 50%;
  background: #F8FAFC;
  display: grid;
  place-items: center;
  font-size: 1.5rem;
  color: #94A3B8;
}

.dash__empty-text {
  color: #64748B;
  font-size: 0.92rem;
  margin: 0;
}

.dash__messages {
  display: grid;
  gap: 14px;
  list-style: none;
  padding: 0;
  margin: 0;
}

.dash__msg-item {
  display: flex;
  gap: 16px;
  padding: 18px 20px;
  border: 1px solid #EDF2EE;
  border-radius: 14px;
  background: #FAFCFA;
  transition: all 0.2s ease;
}

.dash__msg-item:hover {
  background: #ffffff;
  border-color: rgba(15, 138, 95, 0.25);
  box-shadow: 0 4px 16px rgba(8, 36, 26, 0.05);
}

.dash__msg-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: linear-gradient(135deg, #0F8A5F, #08241A);
  color: #ffffff;
  font-weight: 700;
  font-size: 0.95rem;
  display: grid;
  place-items: center;
  flex-shrink: 0;
}

.dash__msg-content {
  flex: 1;
  min-width: 0;
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.dash__msg-head {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
}

.dash__msg-sender {
  display: flex;
  align-items: center;
  gap: 8px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.dash__msg-name {
  font-size: 0.95rem;
  color: #08241A;
}

.dash__msg-subject {
  font-size: 0.88rem;
  color: #64748B;
  font-weight: 500;
}

.dash__msg-status {
  font-size: 0.72rem;
  font-weight: 700;
  letter-spacing: 0.03em;
  padding: 4px 10px;
  border-radius: 9999px;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  flex-shrink: 0;
}

.status-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: currentColor;
}

.dash__msg-status--unread {
  background: #FEF2F2;
  color: #DC2626;
  border: 1px solid #FECACA;
}

.dash__msg-status--read {
  background: #ECFDF5;
  color: #059669;
  border: 1px solid #A7F3D0;
}

.dash__msg-body {
  color: #475569;
  margin: 0;
  font-size: 0.88rem;
  line-height: 1.5;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.dash__msg-meta {
  display: flex;
  align-items: center;
  gap: 16px;
  color: #94A3B8;
  font-size: 0.78rem;
  margin-top: 4px;
}

.dash__msg-meta span {
  display: inline-flex;
  align-items: center;
  gap: 5px;
}

.dash__alert-error {
  padding: 16px 20px;
  background: #FEF2F2;
  border: 1px solid #FCA5A5;
  border-radius: 12px;
  color: #991B1B;
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 600;
}

/* Skeletons */
.dash__card--skeleton {
  gap: 14px;
}

.dash__card--skeleton span {
  display: block;
  border-radius: 8px;
  background: #F1F5F9;
  position: relative;
  overflow: hidden;
}

.dash__card--skeleton .skel-icon {
  width: 44px;
  height: 44px;
  border-radius: 12px;
}

.dash__card--skeleton .skel-label {
  width: 60%;
  height: 16px;
}

.dash__card--skeleton .skel-value {
  width: 40%;
  height: 32px;
}

.dash__card--skeleton span::after {
  content: '';
  position: absolute;
  inset: 0;
  transform: translateX(-100%);
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.7), transparent);
  animation: shimmer 1.3s infinite;
}

@keyframes shimmer {
  to { transform: translateX(100%); }
}

@media (max-width: 1024px) {
  .dash__cards { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 640px) {
  .dash__cards { grid-template-columns: 1fr; }
  .dash__msg-item { flex-direction: column; }
  .dash__msg-avatar { display: none; }
}
</style>
