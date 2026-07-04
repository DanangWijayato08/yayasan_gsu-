<script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'

const router = useRouter()
const authStore = useAuthStore()

const activeMenu = ref('Dashboard')

const menu = [
  { icon: 'fa-gauge', label: 'Dashboard' },
  { icon: 'fa-diagram-project', label: 'Program' },
  { icon: 'fa-images', label: 'Galeri' },
  { icon: 'fa-envelope', label: 'Pesan Kontak' },
  { icon: 'fa-chart-line', label: 'Laporan' },
  { icon: 'fa-gear', label: 'Pengaturan' },
]

const stats = [
  { label: 'Total Donasi', value: 'Rp850.000.000', icon: 'fa-hand-holding-dollar', trend: '+12%', color: 'gold' },
  { label: 'Program Aktif', value: '24', icon: 'fa-diagram-project', trend: 'Sesuai Target', color: 'green' },
  { label: 'Donatur', value: '15.230', icon: 'fa-users', trend: '+8 Baru', color: 'gold' },
]

const activity = [
  { title: 'Donasi Baru Diterima', desc: 'Kontribusi Rp5.000.000 masuk ke program "Dana Pendidikan Anak Yatim".', time: '2 menit lalu' },
  { title: 'Relawan Baru Mendaftar', desc: 'Ahmad Sulaiman mendaftar untuk Distribusi Bantuan Ramadan.', time: '2 jam lalu' },
]

const monthlyTarget = ref(72)

const handleMenuClick = (label) => {
  activeMenu.value = label
  console.log('Menu clicked:', label)
}

const handleLogout = async () => {
  await authStore.logout()
  router.push('/')
}
</script>

<template>
  <div class="admin">
    <aside class="admin__sidebar">
      <div class="admin__brand">
        <span class="admin__mark"><i class="fa-solid fa-hand-holding-heart"></i></span>
        <span>Gerakan Sedekah Umat</span>
      </div>

      <p class="admin__group">Manajemen Inti</p>
      <nav class="admin__nav">
        <button 
          v-for="m in menu" 
          :key="m.label" 
          @click="handleMenuClick(m.label)"
          class="admin__link" 
          :class="{ 'admin__link--active': activeMenu === m.label }"
        >
          <i :class="'fa-solid ' + m.icon"></i> {{ m.label }}
        </button>
      </nav>

      <router-link to="/" class="admin__exit"><i class="fa-solid fa-right-from-bracket"></i> Kembali ke Situs</router-link>
      <button @click="handleLogout" class="admin__logout"><i class="fa-solid fa-sign-out-alt"></i> Logout</button>
    </aside>

    <div class="admin__main">
      <header class="admin__topbar">
        <div>
          <h1>Pusat Kendali Operasional</h1>
          <p>Selamat datang kembali, {{ authStore.user?.name }}. Pantau dampak program secara real-time.</p>
        </div>
        <div class="admin__profile">
          <button class="admin__icon-btn"><i class="fa-regular fa-bell"></i></button>
          <img src="https://i.pravatar.cc/40?img=68" alt="Admin" />
        </div>
      </header>

      <div class="admin__content">
        <!-- Dashboard Section -->
        <section v-show="activeMenu === 'Dashboard'" class="admin__section">
          <section class="admin__stats">
            <div v-for="s in stats" :key="s.label" class="admin__stat-card">
              <div class="admin__stat-icon" :class="'admin__stat-icon--' + s.color"><i :class="'fa-solid ' + s.icon"></i></div>
              <div>
                <span>{{ s.label }}</span>
                <strong>{{ s.value }}</strong>
                <small>{{ s.trend }}</small>
              </div>
            </div>
          </section>

          <section class="admin__grid">
            <div class="admin__panel">
              <div class="admin__panel-head">
                <h3>Riwayat Aktivitas Terbaru</h3>
                <a href="#" class="admin__link-small">Lihat Semua</a>
              </div>
              <ul class="admin__activity">
                <li v-for="a in activity" :key="a.title">
                  <div class="admin__activity-dot"></div>
                  <div>
                    <strong>{{ a.title }}</strong>
                    <p>{{ a.desc }}</p>
                    <small>{{ a.time }}</small>
                  </div>
                </li>
              </ul>
            </div>

            <div class="admin__panel admin__panel--gold">
              <h3>Progres Target Bulanan</h3>
              <p>Rp850.000.000 terkumpul dari target Rp1.200.000.000</p>
              <div class="admin__ring">
                <svg viewBox="0 0 120 120">
                  <circle cx="60" cy="60" r="52" class="admin__ring-bg" />
                  <circle cx="60" cy="60" r="52" class="admin__ring-fg" :style="{ strokeDashoffset: 326 - (326 * monthlyTarget) / 100 }" />
                </svg>
                <strong>{{ monthlyTarget }}%</strong>
              </div>
            </div>
          </section>
        </section>

        <!-- Program Section -->
        <section v-show="activeMenu === 'Program'" class="admin__section">
          <div class="admin__panel">
            <div class="admin__panel-head">
              <h3>Manajemen Program</h3>
              <button class="admin__btn-primary">+ Tambah Program</button>
            </div>
            <div class="admin__empty-state">
              <i class="fa-solid fa-diagram-project"></i>
              <p>Fitur program management akan segera hadir</p>
              <p class="admin__empty-desc">Kelola semua program donasi Anda di sini</p>
            </div>
          </div>
        </section>

        <!-- Gallery Section -->
        <section v-show="activeMenu === 'Galeri'" class="admin__section">
          <div class="admin__panel">
            <div class="admin__panel-head">
              <h3>Manajemen Galeri</h3>
              <button class="admin__btn-primary">+ Tambah Foto</button>
            </div>
            <div class="admin__empty-state">
              <i class="fa-solid fa-images"></i>
              <p>Fitur galeri management akan segera hadir</p>
              <p class="admin__empty-desc">Upload dan kelola foto kegiatan di sini</p>
            </div>
          </div>
        </section>

        <!-- Messages Section -->
        <section v-show="activeMenu === 'Pesan Kontak'" class="admin__section">
          <div class="admin__panel">
            <div class="admin__panel-head">
              <h3>Pesan Kontak</h3>
            </div>
            <div class="admin__empty-state">
              <i class="fa-solid fa-envelope"></i>
              <p>Fitur pesan kontak akan segera hadir</p>
              <p class="admin__empty-desc">Lihat dan balas pesan dari pengunjung di sini</p>
            </div>
          </div>
        </section>

        <!-- Reports Section -->
        <section v-show="activeMenu === 'Laporan'" class="admin__section">
          <div class="admin__panel">
            <div class="admin__panel-head">
              <h3>Laporan</h3>
            </div>
            <div class="admin__empty-state">
              <i class="fa-solid fa-chart-line"></i>
              <p>Fitur laporan akan segera hadir</p>
              <p class="admin__empty-desc">Lihat analitik dan laporan performa di sini</p>
            </div>
          </div>
        </section>

        <!-- Settings Section -->
        <section v-show="activeMenu === 'Pengaturan'" class="admin__section">
          <div class="admin__panel">
            <div class="admin__panel-head">
              <h3>Pengaturan</h3>
            </div>
            <div class="admin__empty-state">
              <i class="fa-solid fa-gear"></i>
              <p>Fitur pengaturan akan segera hadir</p>
              <p class="admin__empty-desc">Atur konfigurasi website di sini</p>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</template>

<style scoped>
.admin { display: flex; min-height: 100vh; background: #F7F7F5; font-family: var(--font-body); }

.admin__sidebar {
  width: 260px;
  background: #161616;
  color: rgba(255,255,255,0.75);
  padding: 28px 20px;
  display: flex;
  flex-direction: column;
  flex-shrink: 0;
}
.admin__brand { display: flex; align-items: center; gap: 10px; color: #fff; font-family: var(--font-heading); margin-bottom: 36px; font-size: 1rem; }
.admin__mark {
  width: 34px; height: 34px; border-radius: 9px;
  background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
  display: flex; align-items: center; justify-content: center;
}
.admin__group { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.35); margin-bottom: 14px; }
.admin__nav { display: flex; flex-direction: column; gap: 4px; }
.admin__link {
  display: flex; align-items: center; gap: 12px;
  padding: 11px 14px;
  border-radius: 10px;
  font-size: 0.9rem;
  transition: background 0.2s ease;
  background: none;
  border: none;
  color: rgba(255,255,255,0.75);
  cursor: pointer;
  width: 100%;
  text-align: left;
}
.admin__link i { width: 18px; }
.admin__link:hover { background: rgba(255,255,255,0.06); }
.admin__link--active { background: var(--color-primary); color: #1a1500; font-weight: 600; }
.admin__exit { margin-top: auto; display: flex; align-items: center; gap: 10px; font-size: 0.88rem; padding: 12px 14px; color: rgba(255,255,255,0.75); text-decoration: none; }
.admin__logout { 
  display: flex; align-items: center; gap: 10px; 
  font-size: 0.88rem; padding: 12px 14px; 
  background: none; border: none; color: #e74c3c; 
  cursor: pointer; width: 100%; text-align: left;
}
.admin__logout:hover { background: rgba(231, 76, 60, 0.1); border-radius: 8px; }

.admin__main { flex: 1; padding: 32px 40px; overflow-y: auto; }
.admin__topbar { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; gap: 20px; flex-wrap: wrap; }
.admin__topbar h1 { font-size: 1.5rem; }
.admin__topbar p { color: var(--color-text-muted); margin-top: 6px; font-size: 0.92rem; }
.admin__profile { display: flex; align-items: center; gap: 14px; }
.admin__icon-btn { width: 40px; height: 40px; border-radius: 50%; background: #fff; box-shadow: var(--shadow-sm); border: none; cursor: pointer; }
.admin__profile img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }

.admin__content { }
.admin__section { animation: fadeIn 0.3s ease; }

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.admin__stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 28px; }
.admin__stat-card {
  background: #fff;
  border-radius: var(--radius-md);
  padding: 22px;
  display: flex;
  gap: 16px;
  box-shadow: var(--shadow-sm);
}
.admin__stat-icon {
  width: 46px; height: 46px; border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  color: #fff; font-size: 1.05rem; flex-shrink: 0;
}
.admin__stat-icon--gold { background: var(--color-primary); }
.admin__stat-icon--green { background: var(--color-secondary); }
.admin__stat-card span { display: block; font-size: 0.82rem; color: var(--color-text-muted); }
.admin__stat-card strong { display: block; font-size: 1.35rem; margin-top: 4px; font-family: var(--font-heading); }
.admin__stat-card small { color: var(--color-secondary); font-size: 0.78rem; font-weight: 700; }

.admin__grid { display: grid; grid-template-columns: 1.6fr 1fr; gap: 20px; }
.admin__panel { background: #fff; border-radius: var(--radius-md); padding: 26px; box-shadow: var(--shadow-sm); }
.admin__panel-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.admin__panel-head h3 { font-size: 1rem; margin: 0; }
.admin__panel-head a { font-size: 0.82rem; color: var(--color-secondary); font-weight: 600; text-decoration: none; }
.admin__link-small { font-size: 0.82rem; color: var(--color-secondary); font-weight: 600; }

.admin__btn-primary {
  background: var(--color-primary);
  color: #1a1500;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  font-size: 0.85rem;
  font-weight: 600;
  cursor: pointer;
  transition: opacity 0.2s ease;
}
.admin__btn-primary:hover { opacity: 0.9; }

.admin__activity { display: flex; flex-direction: column; gap: 20px; list-style: none; padding: 0; margin: 0; }
.admin__activity li { display: flex; gap: 14px; }
.admin__activity-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--color-primary); margin-top: 6px; flex-shrink: 0; }
.admin__activity strong { font-size: 0.9rem; }
.admin__activity p { font-size: 0.85rem; color: var(--color-text-muted); margin-top: 4px; margin: 0; }
.admin__activity small { color: var(--color-text-muted); font-size: 0.75rem; }

.admin__panel--gold { background: linear-gradient(160deg, var(--color-primary), #A9890F); color: #2a2205; text-align: center; }
.admin__panel--gold h3 { color: #2a2205; }
.admin__panel--gold p { font-size: 0.82rem; margin-top: 8px; opacity: 0.85; }
.admin__ring { position: relative; width: 130px; height: 130px; margin: 20px auto 0; }
.admin__ring svg { width: 100%; height: 100%; transform: rotate(-90deg); }
.admin__ring-bg { fill: none; stroke: rgba(255,255,255,0.35); stroke-width: 10; }
.admin__ring-fg {
  fill: none; stroke: #2a2205; stroke-width: 10; stroke-linecap: round;
  stroke-dasharray: 326;
  transition: stroke-dashoffset 1s ease;
}
.admin__ring strong { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }

.admin__empty-state {
  text-align: center;
  padding: 60px 20px;
  color: var(--color-text-muted);
}
.admin__empty-state i {
  font-size: 3rem;
  color: #ddd;
  display: block;
  margin-bottom: 16px;
}
.admin__empty-state p {
  font-size: 1rem;
  margin: 8px 0;
  color: var(--color-text);
}
.admin__empty-desc {
  font-size: 0.9rem;
  color: var(--color-text-muted);
}

@media (max-width: 1100px) {
  .admin__stats { grid-template-columns: 1fr; }
  .admin__grid { grid-template-columns: 1fr; }
}
@media (max-width: 860px) {
  .admin__sidebar { display: none; }
  .admin__main { padding: 24px; }
}
</style>
