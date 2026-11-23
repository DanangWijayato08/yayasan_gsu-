<script setup>
import { computed, onMounted } from 'vue'
import { usePublicLayoutStore } from '@/stores/publicLayout'

const year = new Date().getFullYear()
const layoutStore = usePublicLayoutStore()

const site = computed(() => layoutStore.site)
const contact = computed(() => layoutStore.contact)
const footer = computed(() => layoutStore.footer)
const siteName = computed(() => site.value?.site_name || 'Gerakan Sedekah Umat')

const logoUrl = computed(() => {
  const raw = site.value?.logo
  if (!raw) return '/logo.svg'
  if (typeof window !== 'undefined' && window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
    return raw.replace(/http:\/\/(localhost|127\.0\.0\.1):8000/, window.location.origin)
  }
  return raw
})

const description = computed(
  () =>
    footer.value?.description ||
    'Menerangi jalan kebaikan melalui gerakan amal yang transparan, berdampak, dan berkelanjutan bagi masyarakat.'
)
const copyright = computed(
  () => footer.value?.copyright_text || `\u00A9 ${year} Yayasan Gerakan Sedekah Umat. Semua hak dilindungi.`
)

const defaultContact = {
  address: 'Suronandan RT 3, Ngaranan, Sendangrejo, Kec. Minggir, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55264',
  email: 'yayasansemat@gmail.com',
  phone: '+62 812-3456-7890',
  whatsapp: '6281234567890',
  operational_hours: 'Senin - Jumat, 09.00 - 17.00 WIB',
  instagram_url: 'https://www.instagram.com/yayasan.gsu/',
  tiktok_url: 'https://www.tiktok.com/@gerakansedekahumat',
}

const contactData = computed(() => {
  const c = contact.value && !contact.value.__PHP_Incomplete_Class_Name ? contact.value : null
  return {
    address: c?.address || defaultContact.address,
    email: c?.email || defaultContact.email,
    phone: c?.phone || defaultContact.phone,
    whatsapp: c?.whatsapp || defaultContact.whatsapp,
    operational_hours: c?.operational_hours || defaultContact.operational_hours,
    instagram_url: c?.instagram_url || defaultContact.instagram_url,
    tiktok_url: c?.tiktok_url || defaultContact.tiktok_url,
    youtube_url: c?.youtube_url || null,
    facebook_url: c?.facebook_url || null,
  }
})

function cleanWaNumber(val) {
  if (!val) return ''
  return String(val).replace(/[^0-9]/g, '')
}

const quickLinks = computed(() => {
  const links = footer.value?.quick_links
  return Array.isArray(links) && links.length
    ? links
    : [
        { label: 'Tentang Kami', url: '/tentang' },
        { label: 'Program', url: '/program' },
        { label: 'Galeri', url: '/galeri' },
        { label: 'Donasi', url: '/donasi' },
      ]
})

function detectType(label = '') {
  const key = label.toLowerCase()
  if (key.includes('instagram')) return 'instagram'
  if (key.includes('tiktok')) return 'tiktok'
  if (key.includes('whatsapp') || key.includes('wa')) return 'whatsapp'
  if (key.includes('youtube')) return 'youtube'
  if (key.includes('facebook')) return 'facebook'
  return 'link'
}

const socialLinks = computed(() => {
  const list = []
  const c = contactData.value

  // Prioritaskan akun media sosial dari Pengaturan Kontak / Default Resmi
  if (c.instagram_url) {
    list.push({ label: 'Instagram', type: 'instagram', url: c.instagram_url })
  }
  if (c.tiktok_url) {
    list.push({ label: 'TikTok', type: 'tiktok', url: c.tiktok_url })
  }
  if (c.whatsapp) {
    const waClean = cleanWaNumber(c.whatsapp)
    list.push({ label: 'WhatsApp', type: 'whatsapp', url: `https://wa.me/${waClean}` })
  }
  if (c.youtube_url) {
    list.push({ label: 'YouTube', type: 'youtube', url: c.youtube_url })
  }
  if (c.facebook_url) {
    list.push({ label: 'Facebook', type: 'facebook', url: c.facebook_url })
  }

  // Fallback ke footer_settings jika ada tautan tambahan
  if (Array.isArray(footer.value?.social_links)) {
    for (const item of footer.value.social_links) {
      if (item?.url && !list.some((s) => s.url === item.url)) {
        list.push({
          label: item.label || 'Sosial',
          type: detectType(item.label || item.url),
          url: item.url,
        })
      }
    }
  }

  return list
})

onMounted(async () => {
  await layoutStore.load()
})
</script>

<template>
  <footer class="footer">
    <div class="footer__top">
      <div class="container footer__grid">
        <!-- Kolom 1: Profil & Media Sosial -->
        <div class="footer__col footer__brand">
          <div class="footer__logo">
            <img :src="logoUrl" :alt="siteName" width="38" height="38" @error="(e) => e.target.src = '/logo.svg'" />
            <span>{{ siteName }}</span>
          </div>
          <p>{{ description }}</p>

          <!-- Media Sosial Simpel & Jelas -->
          <div v-if="socialLinks.length" class="footer__social-wrap">
            <span class="footer__social-label">Ikuti Kami:</span>
            <div class="footer__social-pills">
              <a
                v-for="social in socialLinks"
                :key="social.label"
                :href="social.url"
                target="_blank"
                rel="noopener noreferrer"
                class="footer__pill"
                :title="`Buka ${social.label} Yayasan GSU`"
              >
                <!-- SVG Icon (selalu tampil tanpa bergantung CDN) -->
                <svg v-if="social.type === 'instagram'" viewBox="0 0 24 24" width="15" height="15" fill="currentColor">
                  <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                </svg>
                <svg v-else-if="social.type === 'tiktok'" viewBox="0 0 24 24" width="15" height="15" fill="currentColor">
                  <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1.04-.1z"/>
                </svg>
                <svg v-else-if="social.type === 'whatsapp'" viewBox="0 0 24 24" width="15" height="15" fill="currentColor">
                  <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.275.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12 0 2.159.57 4.186 1.564 5.938l-1.664 6.082 6.221-1.632c1.706.93 3.662 1.455 5.741 1.455 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z"/>
                </svg>
                <svg v-else-if="social.type === 'youtube'" viewBox="0 0 24 24" width="15" height="15" fill="currentColor">
                  <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                </svg>
                <svg v-else-if="social.type === 'facebook'" viewBox="0 0 24 24" width="15" height="15" fill="currentColor">
                  <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                <i v-else class="fa-solid fa-link"></i>

                <span>{{ social.label }}</span>
              </a>
            </div>
          </div>
        </div>

        <!-- Kolom 2: Jelajahi -->
        <div class="footer__col">
          <h4>Jelajahi</h4>
          <ul>
            <li v-for="link in quickLinks" :key="link.label">
              <router-link v-if="link.url?.startsWith('/')" :to="link.url">
                <i class="fa-solid fa-chevron-right"></i> {{ link.label }}
              </router-link>
              <a v-else :href="link.url" target="_blank" rel="noopener noreferrer">
                <i class="fa-solid fa-chevron-right"></i> {{ link.label }}
              </a>
            </li>
          </ul>
        </div>

        <!-- Kolom 3: Terlibat -->
        <div class="footer__col">
          <h4>Terlibat</h4>
          <ul>
            <li><router-link to="/donasi"><i class="fa-solid fa-chevron-right"></i> Donasi Sekarang</router-link></li>
            <li><router-link to="/kontak"><i class="fa-solid fa-chevron-right"></i> Jadi Relawan</router-link></li>
            <li><router-link to="/kontak"><i class="fa-solid fa-chevron-right"></i> Hubungi Kami</router-link></li>
            <li><router-link to="/program"><i class="fa-solid fa-chevron-right"></i> Program Kami</router-link></li>
          </ul>
        </div>

        <!-- Kolom 4: Kantor Kami -->
        <div class="footer__col footer__office">
          <h4>Kantor Kami</h4>
          <ul class="footer__contact">
            <li>
              <i class="fa-solid fa-location-dot"></i>
              <span>{{ contactData.address }}</span>
            </li>
            <li>
              <i class="fa-solid fa-envelope"></i>
              <a :href="'mailto:' + contactData.email">{{ contactData.email }}</a>
            </li>
            <li>
              <i class="fa-brands fa-whatsapp"></i>
              <a
                :href="'https://wa.me/' + cleanWaNumber(contactData.whatsapp || contactData.phone)"
                target="_blank"
                rel="noopener noreferrer"
              >
                {{ contactData.whatsapp ? ('+' + String(contactData.whatsapp).replace(/^\+/, '')) : contactData.phone }}
              </a>
            </li>
            <li>
              <i class="fa-solid fa-clock"></i>
              <span>{{ contactData.operational_hours }}</span>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Bottom Bar -->
    <div class="footer__bottom">
      <div class="container footer__bottom-inner">
        <p>{{ copyright }}</p>
        <div class="footer__legal">
          <router-link to="/kontak">Kontak</router-link>
          <router-link to="/donasi">Donasi</router-link>
          <router-link to="/program">Program</router-link>
        </div>
      </div>
    </div>
  </footer>
</template>

<style scoped>
.footer {
  background: #08241A;
  color: rgba(255, 255, 255, 0.75);
  position: relative;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  font-family: var(--font-body);
}

.footer__top {
  padding: 60px 0 38px;
}

.footer__grid {
  display: grid;
  grid-template-columns: 1.6fr 1fr 1fr 1.3fr;
  gap: 40px;
}

/* Brand Section */
.footer__logo {
  display: flex;
  align-items: center;
  gap: 12px;
  color: #fff;
  font-family: var(--font-heading);
  font-size: 1.2rem;
  font-weight: 650;
  margin-bottom: 16px;
}

.footer__logo img {
  width: 38px;
  height: 38px;
  border-radius: var(--radius-sm);
  object-fit: contain;
  background: #fff;
  padding: 4px;
}

.footer__brand p {
  font-size: 0.9rem;
  max-width: 340px;
  line-height: 1.7;
  color: rgba(255, 255, 255, 0.65);
}

/* Social Media Pills - Simpel, Elegan, dan Jelas */
.footer__social-wrap {
  margin-top: 20px;
}

.footer__social-label {
  display: block;
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.06em;
  color: #E5C378;
  font-weight: 700;
  margin-bottom: 10px;
}

.footer__social-pills {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.footer__pill {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 6px 13px;
  border-radius: var(--radius-full);
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.16);
  color: #FFFFFF;
  font-size: 0.82rem;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.2s ease;
}

.footer__pill svg,
.footer__pill i {
  flex-shrink: 0;
  color: #E5C378;
  transition: transform 0.2s ease;
}

.footer__pill:hover {
  background: rgba(229, 195, 120, 0.15);
  border-color: #E5C378;
  color: #FFFFFF;
  transform: translateY(-2px);
}

.footer__pill:hover svg,
.footer__pill:hover i {
  transform: scale(1.1);
}

/* Headings */
.footer__col h4 {
  color: #E5C378;
  font-family: var(--font-body);
  font-size: 0.85rem;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  margin-bottom: 18px;
  font-weight: 750;
}

.footer__col ul {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.footer__col a {
  font-size: 0.88rem;
  transition: all 0.2s ease;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  color: rgba(255, 255, 255, 0.65);
  text-decoration: none;
}

.footer__col a i {
  font-size: 0.6rem;
  color: #E5C378;
  opacity: 0.7;
  transition: transform 0.2s ease, opacity 0.2s ease;
}

.footer__col a:hover {
  color: #FFFFFF;
}

.footer__col a:hover i {
  opacity: 1;
  transform: translateX(3px);
}

/* Contact List */
.footer__contact li {
  display: flex;
  gap: 12px;
  font-size: 0.88rem;
  align-items: flex-start;
  color: rgba(255, 255, 255, 0.75);
  line-height: 1.5;
  word-break: break-word;
  overflow-wrap: break-word;
}

.footer__contact li span,
.footer__contact a {
  word-break: break-word;
  overflow-wrap: break-word;
}

.footer__contact i {
  color: #E5C378;
  font-size: 0.9rem;
  margin-top: 3px;
  flex-shrink: 0;
  width: 16px;
  text-align: center;
}

.footer__contact a {
  color: rgba(255, 255, 255, 0.85);
  text-decoration: none;
  transition: color 0.2s ease;
}

.footer__contact a:hover {
  color: #E5C378;
  text-decoration: underline;
}

/* Bottom Bar */
.footer__bottom {
  border-top: 1px solid rgba(255, 255, 255, 0.08);
  padding: 18px 0;
  background: rgba(0, 0, 0, 0.15);
}

.footer__bottom-inner {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.82rem;
  flex-wrap: wrap;
  gap: 12px;
}

.footer__bottom p {
  color: rgba(255, 255, 255, 0.5);
}

.footer__legal {
  display: flex;
  gap: 20px;
}

.footer__legal a {
  color: rgba(255, 255, 255, 0.5);
  transition: color 0.2s ease;
  font-size: 0.82rem;
  text-decoration: none;
}

.footer__legal a:hover {
  color: #E5C378;
}

/* Responsive */
@media (max-width: 900px) {
  .footer__grid {
    grid-template-columns: 1fr 1fr;
    gap: 32px;
  }
  .footer__brand {
    grid-column: 1 / -1;
  }
}

@media (max-width: 560px) {
  .footer__top {
    padding: 44px 0 32px;
  }
  .footer__grid {
    grid-template-columns: 1fr;
    gap: 32px;
  }
  .footer__social-wrap {
    margin-top: 22px;
  }
  .footer__social-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
  }
  .footer__pill {
    padding: 8px 14px;
    font-size: 0.85rem;
    min-height: 38px;
  }
  .footer__office {
    padding-top: 8px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
  }
  .footer__bottom-inner {
    flex-direction: column;
    text-align: center;
    gap: 14px;
  }
  .footer__legal {
    justify-content: center;
  }
}
</style>
