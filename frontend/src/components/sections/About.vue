<script setup>
const timeline = [
  { year: '2012', text: 'GSU didirikan oleh sekelompok relawan muda di Yogyakarta.' },
  { year: '2016', text: 'Memperluas program pendidikan ke 12 provinsi di Indonesia.' },
  { year: '2020', text: 'Meluncurkan platform donasi digital untuk transparansi penuh.' },
  { year: '2024', text: 'Menjangkau lebih dari 15.000 donatur dan 24 program aktif.' },
]
</script>

<template>
  <section class="about section">
    <div class="container about__grid">
      <div class="about__media" v-reveal="'left'">
        <img
          src="https://images.unsplash.com/photo-1509099836639-18ba1795216d?q=80&w=900&auto=format&fit=crop"
          alt="Kegiatan Yayasan Gerakan Sedekah Umat"
        />
        <div class="about__badge">
          <span>Berdiri Sejak</span>
          <strong>2012</strong>
        </div>
      </div>

      <div class="about__content" v-reveal:150="'right'">
        <span class="eyebrow">Tentang Kami</span>
        <h2>Membangun warisan kasih sayang melalui aksi kolektif.</h2>
        <p class="about__lead">
          Yayasan Gerakan Sedekah Umat bukan sekadar lembaga amal — kami adalah gerakan yang lahir dari
          keyakinan bahwa komunitas yang bersatu mampu mengatasi kemiskinan dan kesulitan secara sistematis.
        </p>

        <div class="about__pillars">
          <div class="about__pillar">
            <h3><i class="fa-solid fa-eye"></i> Visi</h3>
            <p>Menciptakan dunia di mana martabat dipulihkan melalui tindakan amal yang konsisten dan transparan.</p>
          </div>
          <div class="about__pillar">
            <h3><i class="fa-solid fa-bullseye"></i> Misi</h3>
            <p>Memberdayakan masyarakat lewat pendidikan, kesehatan, dan ekonomi berkelanjutan bagi setiap jiwa.</p>
          </div>
          <div class="about__pillar">
            <h3><i class="fa-solid fa-heart"></i> Nilai</h3>
            <p>Amanah, transparansi, dan semangat kekeluargaan menjadi landasan setiap program kami.</p>
          </div>
        </div>

        <div class="about__timeline">
          <div v-for="t in timeline" :key="t.year" class="about__timeline-item">
            <span class="about__timeline-year">{{ t.year }}</span>
            <p>{{ t.text }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.about__grid {
  display: grid;
  grid-template-columns: 0.85fr 1.15fr;
  gap: 64px;
  align-items: start;
}
.about__media { position: relative; }
.about__media img {
  border-radius: var(--radius-lg);
  width: 100%;
  aspect-ratio: 3/3.6;
  object-fit: cover;
  box-shadow: var(--shadow-md);
}
.about__badge {
  position: absolute;
  bottom: -20px; right: -16px;
  background: var(--color-primary);
  color: #2a2205;
  padding: 16px 22px;
  border-radius: var(--radius-md);
  text-align: center;
  box-shadow: var(--shadow-gold);
}
.about__badge span { display: block; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.05em; }
.about__badge strong { font-size: 1.4rem; font-family: var(--font-heading); }

.about__content h2 { font-size: clamp(1.7rem, 3vw, 2.3rem); margin-top: 16px; }
.about__lead { margin-top: 18px; color: var(--color-text-muted); font-size: 1.02rem; }

.about__pillars {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 18px;
  margin-top: 36px;
}
.about__pillar {
  background: var(--color-accent);
  border-radius: var(--radius-sm);
  padding: 20px;
}
.about__pillar h3 {
  font-family: var(--font-body);
  font-size: 0.95rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 8px;
  color: var(--color-secondary-dark);
}
.about__pillar p { margin-top: 10px; font-size: 0.88rem; color: var(--color-text-muted); }

.about__timeline {
  margin-top: 40px;
  border-left: 2px solid var(--color-border);
  padding-left: 24px;
  display: flex;
  flex-direction: column;
  gap: 22px;
}
.about__timeline-item { position: relative; }
.about__timeline-item::before {
  content: '';
  position: absolute;
  left: -30px; top: 4px;
  width: 10px; height: 10px;
  border-radius: 50%;
  background: var(--color-primary);
}
.about__timeline-year {
  font-weight: 700;
  color: var(--color-secondary);
  font-size: 0.95rem;
}
.about__timeline-item p { margin-top: 4px; color: var(--color-text-muted); font-size: 0.92rem; }

@media (max-width: 900px) {
  .about__grid { grid-template-columns: 1fr; }
  .about__pillars { grid-template-columns: 1fr; }
  .about__media { max-width: 420px; margin: 0 auto; }
}
</style>
