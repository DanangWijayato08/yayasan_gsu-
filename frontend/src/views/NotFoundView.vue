<script setup>
</script>

<template>
  <section class="not-found">
    <div class="container not-found__inner">
      <span class="eyebrow"><i class="fa-solid fa-compass"></i> Kesalahan 404</span>
      <h1>Halaman Tidak Ditemukan</h1>
      <p>
        Maaf, halaman yang Anda cari tidak tersedia atau mungkin telah dipindahkan.
        Silakan kembali ke beranda atau lihat program kebaikan kami.
      </p>
      <div class="not-found__actions">
        <router-link to="/" class="btn btn-primary btn-lg">
          <i class="fa-solid fa-house"></i> Kembali ke Beranda
        </router-link>
        <router-link to="/program" class="btn btn-outline btn-lg">
          Lihat Program <i class="fa-solid fa-arrow-right"></i>
        </router-link>
      </div>
    </div>
  </section>
</template>

<style scoped>
.not-found {
  min-height: 68vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 80px 0;
  background: var(--color-accent);
  text-align: center;
}

.not-found__inner {
  max-width: 560px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.not-found h1 {
  font-size: clamp(2rem, 4vw, 2.8rem);
  margin-top: 16px;
  font-weight: 650;
}

.not-found p {
  margin-top: 14px;
  color: var(--color-text-muted);
  font-size: 1.02rem;
  line-height: 1.7;
}

.not-found__actions {
  margin-top: 28px;
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
  justify-content: center;
}
</style>
