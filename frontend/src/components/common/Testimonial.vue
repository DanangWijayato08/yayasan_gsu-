<script setup>
import { ref } from 'vue'

const testimonials = [
  { name: 'Rina Amelia', role: 'Donatur Rutin', city: 'Bandung', initials: 'RA', rating: 5, quote: 'Setiap laporan penyaluran dana program disampaikan dengan sangat transparan dan terdokumentasi. Kami yakin donasi benar-benar sampai kepada yang berhak.' },
  { name: 'H. Ahmad Fauzi', role: 'Relawan Komunitas', city: 'Surabaya', initials: 'AF', rating: 5, quote: 'Terjun langsung bersama relawan GSU membuka mata saya tentang betapa besarnya dampak gotong royong yang dikelola secara profesional dan amanah.' },
  { name: 'Siti Nurhaliza', role: 'Donatur Program Pendidikan', city: 'Jakarta Selatan', initials: 'SN', rating: 5, quote: 'Proses donasi sangat mudah dan cepat. Adanya konfirmasi berkala dan update perkembangan program membuat hati tenang dan semakin percaya.' },
  { name: 'Budi Santoso', role: 'Donatur Tetap', city: 'Yogyakarta', initials: 'BS', rating: 5, quote: 'Program-program pemberdayaannya berkesinambungan, bukan sekadar santunan sesaat. Bangga bisa menjadi bagian dari ikhtiar kebaikan bersama ini.' },
]

const index = ref(0)
function next() { index.value = (index.value + 1) % testimonials.length }
function prev() { index.value = (index.value - 1 + testimonials.length) % testimonials.length }
</script>

<template>
  <div class="testi">
    <div class="testi__track">
      <transition name="slide" mode="out-in">
        <div :key="index" class="testi__card">
          <div class="testi__stars">
            <i v-for="n in 5" :key="n" class="fa-solid fa-star" :class="{ 'testi__star--off': n > testimonials[index].rating }"></i>
          </div>
          <p class="testi__quote">&ldquo;{{ testimonials[index].quote }}&rdquo;</p>
          <div class="testi__person">
            <div class="testi__avatar-monogram">
              {{ testimonials[index].initials }}
            </div>
            <div>
              <strong>{{ testimonials[index].name }}</strong>
              <span>{{ testimonials[index].role }} &bull; {{ testimonials[index].city }}</span>
            </div>
          </div>
        </div>
      </transition>
    </div>

    <div class="testi__controls">
      <button @click="prev" aria-label="Sebelumnya"><i class="fa-solid fa-arrow-left"></i></button>
      <div class="testi__dots">
        <span v-for="(t, i) in testimonials" :key="i" :class="{ 'testi__dot--active': i === index }" @click="index = i"></span>
      </div>
      <button @click="next" aria-label="Selanjutnya"><i class="fa-solid fa-arrow-right"></i></button>
    </div>
  </div>
</template>

<style scoped>
.testi { max-width: 680px; margin: 0 auto; text-align: center; }
.testi__card { padding: 8px; }
.testi__stars { color: var(--color-primary); font-size: 0.95rem; margin-bottom: 20px; letter-spacing: 4px; }
.testi__star--off { color: var(--color-border); }
.testi__quote {
  font-family: var(--font-heading);
  font-size: 1.32rem;
  font-style: italic;
  color: var(--color-text);
  line-height: 1.65;
  font-weight: 500;
}
.testi__person { display: flex; align-items: center; justify-content: center; gap: 14px; margin-top: 28px; }
.testi__avatar-monogram {
  width: 48px;
  height: 48px;
  border-radius: var(--radius-sm);
  background: var(--color-secondary);
  color: #FFFFFF;
  font-family: var(--font-body);
  font-weight: 700;
  font-size: 0.95rem;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: var(--shadow-xs);
  letter-spacing: 0.05em;
}
.testi__person div { text-align: left; }
.testi__person strong { display: block; font-size: 0.96rem; color: var(--color-text); }
.testi__person span { font-size: 0.82rem; color: var(--color-text-muted); }

.testi__controls { display: flex; align-items: center; justify-content: center; gap: 22px; margin-top: 36px; }
.testi__controls button {
  width: 44px; height: 44px;
  border-radius: 50%;
  border: 1.5px solid var(--color-border);
  color: var(--color-text);
  transition: all 0.25s ease;
  display: flex; align-items: center; justify-content: center;
}
.testi__controls button:hover {
  background: var(--color-secondary);
  border-color: var(--color-secondary);
  color: #fff;
  transform: translateY(-2px);
}
.testi__dots { display: flex; gap: 8px; align-items: center; }
.testi__dots span { width: 8px; height: 8px; border-radius: 50%; background: var(--color-border); cursor: pointer; transition: all 0.3s ease; }
.testi__dots span:hover { background: var(--color-primary); }
.testi__dots .testi__dot--active { background: var(--color-primary); width: 24px; border-radius: 4px; }

.slide-enter-active, .slide-leave-active { transition: all 0.35s ease; }
.slide-enter-from { opacity: 0; transform: translateX(20px); }
.slide-leave-to { opacity: 0; transform: translateX(-20px); }
</style>
