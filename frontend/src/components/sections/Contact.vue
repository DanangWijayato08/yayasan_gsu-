<script setup>
import { computed, onMounted, ref } from 'vue'
import { contactService, publicService } from '@/services/api/client'
import { usePublicLayoutStore } from '@/stores/publicLayout'

const form = ref({ name: '', email: '', phone: '', subject: '', message: '' })
const settings = ref(null)
const layoutStore = usePublicLayoutStore()
const isSubmitting = ref(false)
const sent = ref(false)
const error = ref('')

const activeContact = computed(() => {
  const current = settings.value && !settings.value.__PHP_Incomplete_Class_Name ? settings.value : null
  const layout = layoutStore.contact && !layoutStore.contact.__PHP_Incomplete_Class_Name ? layoutStore.contact : null

  return {
    address:
      current?.address ||
      layout?.address ||
      'Suronandan RT 3, Ngaranan, Sendangrejo, Kec. Minggir, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55264',
    operational_hours:
      current?.operational_hours ||
      layout?.operational_hours ||
      'Senin - Jumat, 09.00 - 17.00 WIB',
    email:
      current?.email ||
      layout?.email ||
      'yayasansemat@gmail.com',
    phone:
      current?.phone ||
      layout?.phone ||
      '+62 812-3456-7890',
    whatsapp:
      current?.whatsapp ||
      layout?.whatsapp ||
      '6281234567890',
    instagram_url: current?.instagram_url || layout?.instagram_url,
    facebook_url: current?.facebook_url || layout?.facebook_url,
    youtube_url: current?.youtube_url || layout?.youtube_url,
    tiktok_url: current?.tiktok_url || layout?.tiktok_url,
    google_maps_embed: current?.google_maps_embed || layout?.google_maps_embed,
  }
})

function cleanWaNumber(val) {
  if (!val) return ''
  return String(val).replace(/[^0-9]/g, '')
}

const mapEmbedUrl = computed(() => {
  let url = activeContact.value.google_maps_embed?.trim()
  if (!url) {
    if (activeContact.value.address) {
      return `https://maps.google.com/maps?q=${encodeURIComponent(activeContact.value.address)}&z=17&hl=id&output=embed`
    }
    return 'https://maps.google.com/maps?q=Yayasan%20GSU&z=17&hl=id&output=embed'
  }

  // 1. Ekstrak src jika user mem-paste seluruh tag <iframe>
  const iframeMatch = url.match(/src=["']([^"']+)["']/i)
  if (iframeMatch) {
    url = iframeMatch[1]
  }

  // 2. Jika sudah berupa embed URL Google Maps
  if (url.includes('/maps/embed') || url.includes('output=embed')) {
    if (url.includes('output=embed') && !url.includes('z=')) {
      url += '&z=17&hl=id'
    }
    return url
  }

  // 3. Jika tautan tempat /maps/place/{nama_tempat}
  const placeMatch = url.match(/\/maps\/place\/([^/@]+)/i)
  if (placeMatch) {
    const place = decodeURIComponent(placeMatch[1].replace(/\+/g, ' '))
    return `https://maps.google.com/maps?q=${encodeURIComponent(place)}&z=17&hl=id&output=embed`
  }

  // 4. Jika terdapat koordinat latitude,longitude (@-7.123,110.123)
  const coordMatch = url.match(/@(-?\d+\.\d+),(-?\d+\.\d+)/)
  if (coordMatch) {
    return `https://maps.google.com/maps?q=${coordMatch[1]},${coordMatch[2]}&z=17&hl=id&output=embed`
  }

  // 5. Jika short link maps.app.goo.gl belum ter-resolve oleh backend, gunakan alamat sebagai fallback
  if (url.includes('maps.app.goo.gl')) {
    if (activeContact.value.address) {
      return `https://maps.google.com/maps?q=${encodeURIComponent(activeContact.value.address)}&z=17&hl=id&output=embed`
    }
    return 'https://maps.google.com/maps?q=Yayasan%20GSU&z=17&hl=id&output=embed'
  }

  // 6. Jika teks biasa (alamat)
  if (!url.startsWith('http://') && !url.startsWith('https://')) {
    return `https://maps.google.com/maps?q=${encodeURIComponent(url)}&z=17&hl=id&output=embed`
  }

  return url
})

const directMapLink = computed(() => {
  const raw = activeContact.value.google_maps_embed?.trim()
  if (raw && (raw.includes('maps.app.goo.gl') || raw.includes('google.com/maps'))) {
    const iframeMatch = raw.match(/src=["']([^"']+)["']/i)
    return iframeMatch ? iframeMatch[1] : raw
  }
  if (activeContact.value.address) {
    return `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(activeContact.value.address)}`
  }
  return 'https://www.google.com/maps/search/?api=1&query=Yayasan+GSU'
})

onMounted(async () => {
  try {
    const response = await publicService.contactSettings()
    if (response?.data && !response.data.__PHP_Incomplete_Class_Name) {
      settings.value = response.data
    }
  } catch {
    settings.value = null
  }
  if (!layoutStore.loaded) {
    await layoutStore.load()
  }
})

async function submit() {
  isSubmitting.value = true
  error.value = ''
  sent.value = false

  try {
    await contactService.create(form.value)
    form.value = { name: '', email: '', phone: '', subject: '', message: '' }
    sent.value = true
    setTimeout(() => (sent.value = false), 3000)
  } catch (err) {
    if (err.response?.status === 429) {
      error.value = 'Terlalu banyak pengiriman pesan. Mohon tunggu 1 menit sebelum mencoba lagi.'
    } else if (err.response?.data?.message) {
      error.value = err.response.data.message
    } else {
      error.value = 'Pesan belum berhasil dikirim. Silakan coba lagi.'
    }
  } finally {
    isSubmitting.value = false
  }
}
</script>

<template>
  <div class="contact">
    <div class="contact__grid">
      <!-- Contact Information -->
      <div class="contact__info">
        <h3>Informasi Kontak</h3>
        <ul class="contact__list">
          <li>
            <div class="contact__list-icon"><i class="fa-solid fa-location-dot"></i></div>
            <div class="contact__list-detail">
              <strong>Alamat Kantor</strong>
              <span>{{ activeContact.address }}</span>
            </div>
          </li>
          <li>
            <div class="contact__list-icon"><i class="fa-solid fa-clock"></i></div>
            <div class="contact__list-detail">
              <strong>Jam Operasional</strong>
              <span>{{ activeContact.operational_hours }}</span>
            </div>
          </li>
          <li>
            <div class="contact__list-icon"><i class="fa-solid fa-envelope"></i></div>
            <div class="contact__list-detail">
              <strong>Email Resmi</strong>
              <a :href="'mailto:' + activeContact.email" class="contact__link">{{ activeContact.email }}</a>
            </div>
          </li>
          <li>
            <div class="contact__list-icon"><i class="fa-brands fa-whatsapp"></i></div>
            <div class="contact__list-detail">
              <strong>Layanan WhatsApp</strong>
              <a
                :href="'https://wa.me/' + cleanWaNumber(activeContact.whatsapp || activeContact.phone)"
                target="_blank"
                rel="noreferrer"
                class="contact__link"
              >
                {{ activeContact.whatsapp ? ('+' + String(activeContact.whatsapp).replace(/^\+/, '')) : activeContact.phone }}
              </a>
            </div>
          </li>
        </ul>

        <div v-if="activeContact.instagram_url || activeContact.facebook_url || activeContact.youtube_url || activeContact.tiktok_url" class="contact__social">
          <a v-if="activeContact.instagram_url" :href="activeContact.instagram_url" target="_blank" rel="noreferrer">
            <i class="fa-brands fa-instagram"></i> Instagram
          </a>
          <a v-if="activeContact.facebook_url" :href="activeContact.facebook_url" target="_blank" rel="noreferrer">
            <i class="fa-brands fa-facebook-f"></i> Facebook
          </a>
          <a v-if="activeContact.youtube_url" :href="activeContact.youtube_url" target="_blank" rel="noreferrer">
            <i class="fa-brands fa-youtube"></i> YouTube
          </a>
          <a v-if="activeContact.tiktok_url" :href="activeContact.tiktok_url" target="_blank" rel="noreferrer">
            <i class="fa-brands fa-tiktok"></i> TikTok
          </a>
        </div>
      </div>

      <!-- Contact Form -->
      <form class="contact__form card" @submit.prevent="submit">
        <h3>Hubungi Kami</h3>
        <input
          v-model="form.name"
          type="text"
          placeholder="Nama Lengkap"
          required
          class="contact__input"
        />
        <input
          v-model="form.email"
          type="email"
          placeholder="Alamat Email"
          required
          class="contact__input"
        />
        <input
          v-model="form.phone"
          type="tel"
          placeholder="Nomor Telepon / WhatsApp"
          class="contact__input"
        />
        <input
          v-model="form.subject"
          type="text"
          placeholder="Subjek Pesan"
          class="contact__input"
        />
        <textarea
          v-model="form.message"
          rows="5"
          placeholder="Tulis pesan atau pertanyaan Anda di sini..."
          required
          class="contact__input contact__textarea"
        ></textarea>
        <button type="submit" class="btn btn-secondary contact__submit" :disabled="isSubmitting">
          <span v-if="!isSubmitting">Kirim Pesan Sekarang <i class="fa-solid fa-paper-plane"></i></span>
          <span v-else><i class="fa-solid fa-spinner fa-spin"></i> Mengirim...</span>
        </button>
        <transition name="fade">
          <p v-if="sent" class="contact__sent">
            <i class="fa-solid fa-circle-check"></i> Pesan Anda berhasil dikirim! Tim kami akan segera merespons.
          </p>
        </transition>
        <p v-if="error" class="contact__error">{{ error }}</p>
      </form>
    </div>

    <!-- Dedicated Full-Width Map Section (450px height, zero clipping) -->
    <div class="contact__map-card">
      <div class="contact__map-header">
        <div class="contact__map-header-left">
          <div class="contact__map-pin-icon">
            <i class="fa-solid fa-map-location-dot"></i>
          </div>
          <div>
            <h4>Peta Lokasi Yayasan</h4>
            <p>{{ activeContact.address }}</p>
          </div>
        </div>
        <a :href="directMapLink" target="_blank" rel="noopener noreferrer" class="contact__map-btn">
          <i class="fa-solid fa-diamond-turn-right"></i>
          <span>Buka Petunjuk Arah di Google Maps</span>
          <i class="fa-solid fa-arrow-up-right-from-square"></i>
        </a>
      </div>
      <div class="contact__map-frame">
        <iframe
          title="Lokasi Yayasan GSU"
          :src="mapEmbedUrl"
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
        ></iframe>
      </div>
    </div>
  </div>
</template>

<style scoped>
.contact {
  display: flex;
  flex-direction: column;
  gap: 48px;
}

.contact__grid {
  display: grid;
  grid-template-columns: 1fr 1.1fr;
  gap: 48px;
  align-items: start;
}

.contact__info h3,
.contact__form h3 {
  font-size: 1.25rem;
  margin-bottom: 24px;
  color: var(--color-text);
  font-family: var(--font-heading);
}

.contact__list {
  display: flex;
  flex-direction: column;
  gap: 20px;
  list-style: none;
  padding: 0;
  margin: 0;
}

.contact__list li {
  display: flex;
  gap: 16px;
  align-items: flex-start;
}

.contact__list-icon {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  background: rgba(15, 138, 95, 0.08);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.contact__list-icon i {
  color: var(--color-secondary);
  font-size: 1.1rem;
}

.contact__list-detail {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.contact__list-detail strong {
  font-size: 0.8rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: var(--color-text-muted);
}

.contact__list-detail span,
.contact__link {
  font-size: 0.95rem;
  color: var(--color-text);
  line-height: 1.5;
  text-decoration: none;
  transition: color 0.2s ease;
}

.contact__link:hover {
  color: var(--color-secondary);
  text-decoration: underline;
}

.contact__social {
  display: flex;
  gap: 10px;
  margin-top: 28px;
  flex-wrap: wrap;
}

.contact__social a {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 18px;
  border: 1.5px solid var(--color-border);
  border-radius: var(--radius-full);
  font-size: 0.85rem;
  font-weight: 600;
  transition: all 0.25s ease;
  color: var(--color-text-muted);
  text-decoration: none;
}

.contact__social a:hover {
  border-color: var(--color-secondary);
  color: var(--color-secondary);
  background: rgba(15, 138, 95, 0.06);
  transform: translateY(-2px);
}

/* Full-Width Map Section */
.contact__map-card {
  background: #ffffff;
  border: 1px solid var(--color-border);
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 4px 24px -2px rgba(8, 36, 26, 0.06);
  display: flex;
  flex-direction: column;
}

.contact__map-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 28px;
  background: #FAFCFA;
  border-bottom: 1px solid var(--color-border);
  flex-wrap: wrap;
  gap: 16px;
}

.contact__map-header-left {
  display: flex;
  align-items: center;
  gap: 14px;
}

.contact__map-pin-icon {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  background: rgba(15, 138, 95, 0.08);
  color: var(--color-secondary);
  display: grid;
  place-items: center;
  font-size: 1.2rem;
}

.contact__map-header-left h4 {
  margin: 0;
  font-size: 1.15rem;
  color: var(--color-text);
  font-family: var(--font-heading);
}

.contact__map-header-left p {
  margin: 2px 0 0;
  font-size: 0.88rem;
  color: var(--color-text-muted);
}

.contact__map-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: 0.86rem;
  font-weight: 650;
  color: var(--color-secondary);
  text-decoration: none;
  padding: 10px 18px;
  border-radius: var(--radius-sm);
  background: rgba(15, 138, 95, 0.08);
  border: 1px solid rgba(15, 138, 95, 0.2);
  transition: all 0.2s ease;
}

.contact__map-btn:hover {
  background: var(--color-secondary);
  color: #ffffff;
  transform: translateY(-1px);
}

.contact__map-frame {
  width: 100%;
  height: 450px;
  background: #EDF2EE;
}

.contact__map-frame iframe {
  width: 100%;
  height: 100%;
  border: 0;
  display: block;
}

/* Form Styles */
.contact__form {
  padding: 36px;
  display: flex;
  flex-direction: column;
  gap: 14px;
}

.contact__input {
  width: 100%;
  padding: 14px 18px;
  border: 1.5px solid var(--color-border);
  border-radius: var(--radius-sm);
  font-size: 0.92rem;
  background: #fff;
  transition: all 0.25s ease;
}

.contact__input:focus {
  outline: none;
  border-color: var(--color-secondary);
  box-shadow: 0 0 0 4px rgba(15, 138, 95, 0.08);
}

.contact__textarea {
  resize: vertical;
  min-height: 120px;
}

.contact__submit {
  margin-top: 6px;
  width: 100%;
  justify-content: center;
  padding: 16px;
}

.contact__sent {
  color: var(--color-secondary-dark);
  font-size: 0.88rem;
  display: flex;
  gap: 8px;
  align-items: center;
  background: rgba(15, 138, 95, 0.06);
  padding: 12px 16px;
  border-radius: var(--radius-sm);
}

.contact__error {
  color: var(--color-error);
  font-size: 0.88rem;
  background: rgba(220, 38, 38, 0.06);
  padding: 12px 16px;
  border-radius: var(--radius-sm);
}

.fade-enter-active { transition: opacity 0.3s ease; }
.fade-enter-from { opacity: 0; }

@media (max-width: 860px) {
  .contact__grid { grid-template-columns: 1fr; gap: 36px; }
  .contact__map-frame { height: 320px; }
}

@media (max-width: 520px) {
  .contact__form { padding: 24px; }
}
</style>
