<script setup>
import { ref } from 'vue'

const form = ref({ name: '', email: '', message: '' })
const sent = ref(false)

function submit() {
  sent.value = true
  setTimeout(() => (sent.value = false), 3000)
}
</script>

<template>
  <div class="contact">
    <div class="contact__grid">
      <div class="contact__info">
        <h3>Informasi Kontak</h3>
        <ul class="contact__list">
          <li><i class="fa-solid fa-location-dot"></i> <span>Jl. Kebajikan No. 12, Jakarta Selatan, 12340, Indonesia</span></li>
          <li><i class="fa-solid fa-envelope"></i> <span>halo@sedekahumat.org</span></li>
          <li><i class="fa-brands fa-whatsapp"></i> <span>+62 812-3456-7890</span></li>
        </ul>
        <div class="contact__social">
          <a href="#"><i class="fa-brands fa-instagram"></i> Instagram</a>
          <a href="#"><i class="fa-brands fa-facebook-f"></i> Facebook</a>
        </div>

        <div class="contact__map">
          <iframe
            title="Lokasi Yayasan GSU"
            src="https://maps.google.com/maps?q=Jakarta%20Selatan&t=&z=13&ie=UTF8&iwloc=&output=embed"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
          ></iframe>
        </div>
      </div>

      <form class="contact__form card" @submit.prevent="submit">
        <h3>Hubungi Kami</h3>
        <input v-model="form.name" type="text" placeholder="Nama Lengkap" required class="donate__input" />
        <input v-model="form.email" type="email" placeholder="Alamat Email" required class="donate__input" />
        <textarea v-model="form.message" rows="5" placeholder="Tulis pesan Anda..." required class="donate__input contact__textarea"></textarea>
        <button type="submit" class="btn btn-secondary contact__submit">
          Kirim Pesan <i class="fa-solid fa-paper-plane"></i>
        </button>
        <transition name="fade">
          <p v-if="sent" class="contact__sent"><i class="fa-solid fa-circle-check"></i> Pesan berhasil dikirim!</p>
        </transition>
      </form>
    </div>
  </div>
</template>

<style scoped>
.contact__grid {
  display: grid;
  grid-template-columns: 1fr 1.1fr;
  gap: 48px;
}
.contact__info h3, .contact__form h3 { font-size: 1.2rem; margin-bottom: 22px; }
.contact__list { display: flex; flex-direction: column; gap: 18px; }
.contact__list li { display: flex; gap: 14px; align-items: flex-start; color: var(--color-text-muted); font-size: 0.94rem; }
.contact__list i { color: var(--color-secondary); margin-top: 3px; width: 18px; }
.contact__social { display: flex; gap: 14px; margin-top: 24px; }
.contact__social a {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 10px 18px;
  border: 1.5px solid var(--color-border);
  border-radius: 999px;
  font-size: 0.85rem;
  font-weight: 600;
}
.contact__social a:hover { border-color: var(--color-secondary); color: var(--color-secondary); }

.contact__map {
  margin-top: 28px;
  border-radius: var(--radius-md);
  overflow: hidden;
  aspect-ratio: 16/10;
  box-shadow: var(--shadow-sm);
}
.contact__map iframe { width: 100%; height: 100%; border: 0; }

.contact__form { padding: 32px; display: flex; flex-direction: column; gap: 14px; }
.contact__textarea { resize: vertical; font-family: inherit; }
.contact__submit { margin-top: 6px; }
.contact__sent { color: var(--color-secondary-dark); font-size: 0.88rem; display: flex; gap: 8px; align-items: center; }
.fade-enter-active { transition: opacity 0.3s ease; }
.fade-enter-from { opacity: 0; }

@media (max-width: 860px) {
  .contact__grid { grid-template-columns: 1fr; }
}
</style>
