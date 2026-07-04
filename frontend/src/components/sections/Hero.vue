<script setup>
import Counter from '../common/Counter.vue'
</script>

<template>
  <section class="hero">
    <div class="container hero__grid">
      <div class="hero__copy" v-reveal:0="'left'">
        <span class="eyebrow"><i class="fa-solid fa-sparkles"></i> Yayasan Amal Terpercaya</span>
        <h1 class="hero__title">Gerakan <span>Sedekah</span> Umat</h1>
        <p class="hero__subtitle">
          Menghubungkan kebaikan, memberdayakan masyarakat, dan membangun masa depan bersama.
        </p>
        <div class="hero__actions">
          <router-link to="/donasi" class="btn btn-primary">
            Donasi Sekarang <i class="fa-solid fa-arrow-right"></i>
          </router-link>
          <router-link to="/program" class="btn btn-outline">Pelajari Program</router-link>
        </div>

        <div class="hero__trust">
          <div class="hero__avatars">
            <img src="https://i.pravatar.cc/40?img=12" alt="Donatur" />
            <img src="https://i.pravatar.cc/40?img=32" alt="Donatur" />
            <img src="https://i.pravatar.cc/40?img=45" alt="Donatur" />
          </div>
          <p>Dipercaya oleh <strong>15.000+</strong> donatur di seluruh Indonesia</p>
        </div>
      </div>

      <div class="hero__visual" v-reveal:150="'right'">
        <img
          class="hero__image"
          src="https://images.unsplash.com/photo-1593113598332-cd288d649433?q=80&w=900&auto=format&fit=crop"
          alt="Kegiatan sosial Gerakan Sedekah Umat"
        />
        <div class="hero__card hero__card--donatur">
          <i class="fa-solid fa-people-group"></i>
          <div>
            <strong><Counter :target="15230" /></strong>
            <span>Donatur</span>
          </div>
        </div>
        <div class="hero__card hero__card--dana">
          <i class="fa-solid fa-hand-holding-dollar"></i>
          <div>
            <strong>Rp<Counter :target="850" suffix="Jt" /></strong>
            <span>Dana Terkumpul</span>
          </div>
        </div>
        <div class="hero__card hero__card--program">
          <i class="fa-solid fa-diagram-project"></i>
          <div>
            <strong><Counter :target="24" /></strong>
            <span>Program Aktif</span>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.hero {
  position: relative;
  padding: 72px 0 60px;
  overflow: hidden;
  background:
    radial-gradient(700px 400px at 85% -10%, rgba(200,164,21,0.10), transparent),
    radial-gradient(600px 400px at -10% 90%, rgba(46,125,50,0.08), transparent);
}

.hero__grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 56px;
  align-items: center;
}

.hero__title {
  font-size: clamp(2.4rem, 5vw, 3.6rem);
  margin-top: 20px;
}
.hero__title span { color: var(--color-secondary); }

.hero__subtitle {
  margin-top: 20px;
  font-size: 1.1rem;
  color: var(--color-text-muted);
  max-width: 460px;
}

.hero__actions {
  display: flex;
  gap: 16px;
  margin-top: 34px;
  flex-wrap: wrap;
}

.hero__trust {
  display: flex;
  align-items: center;
  gap: 14px;
  margin-top: 42px;
}
.hero__avatars { display: flex; }
.hero__avatars img {
  width: 40px; height: 40px;
  border-radius: 50%;
  border: 3px solid #fff;
  margin-left: -12px;
  box-shadow: var(--shadow-sm);
}
.hero__avatars img:first-child { margin-left: 0; }
.hero__trust p { font-size: 0.92rem; color: var(--color-text-muted); }

.hero__visual { position: relative; }
.hero__image {
  border-radius: var(--radius-lg);
  width: 100%;
  aspect-ratio: 4/4.5;
  object-fit: cover;
  box-shadow: var(--shadow-md);
}

.hero__card {
  position: absolute;
  display: flex;
  align-items: center;
  gap: 12px;
  background: #fff;
  border-radius: var(--radius-md);
  padding: 14px 18px;
  box-shadow: var(--shadow-md);
  font-size: 0.85rem;
}
.hero__card i {
  width: 40px; height: 40px;
  border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  color: #fff;
  font-size: 1rem;
}
.hero__card strong { display: block; font-size: 1.05rem; }
.hero__card span { color: var(--color-text-muted); font-size: 0.78rem; }

.hero__card--donatur { top: 8%; left: -8%; }
.hero__card--donatur i { background: var(--color-secondary); }
.hero__card--dana { bottom: 22%; right: -10%; }
.hero__card--dana i { background: var(--color-primary); }
.hero__card--program { bottom: 4%; left: 10%; }
.hero__card--program i { background: #2E7D32; }

@media (max-width: 960px) {
  .hero__grid { grid-template-columns: 1fr; }
  .hero__visual { margin-top: 40px; }
  .hero__card--donatur, .hero__card--dana, .hero__card--program {
    position: static;
    display: inline-flex;
    margin: 10px 8px 0 0;
  }
  .hero__visual { display: flex; flex-direction: column; }
}
</style>
