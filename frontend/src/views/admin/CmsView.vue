<script setup>
import { computed, nextTick, onMounted, reactive, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import AdminShell from '@/components/admin/AdminShell.vue'
import { adminService } from '@/services/adminService'
import { uploadService } from '@/services/uploadService'
import { usePublicLayoutStore } from '@/stores/publicLayout'

const route = useRoute()
const loading = ref(true)
const saving = ref(false)
const error = ref('')
const toast = ref('')
const items = ref([])
const form = reactive({})
const editingId = ref(null)
const search = ref('')
const statusFilter = ref('')
const imagePreview = ref('')
const page = ref(1)
const perPage = ref(10)
const pagination = ref(null)
const isSlugManuallyEdited = ref(false)
let searchTimer = null

const categoryPresets = {
  programs: ['Sedekah Pangan', 'Pendidikan', 'Kesehatan', 'Wakaf', 'Kemanusiaan', 'Dakwah & Religi'],
  galleries: ['Penyaluran Bantuan', 'Santunan Yatim', 'Bakti Sosial', 'Tanggap Bencana', 'Pemberdayaan Umat'],
}

const configs = {
  homepage: {
    title: 'Bagian Beranda',
    subtitle: 'Kelola hero, statistik, CTA donasi, dan section beranda.',
    mode: 'homepage',
    fields: [
      ['section_key', 'Section Key', 'text', null, true],
      ['sort_order', 'Urutan Tampilan', 'number'],
      ['title', 'Judul Bagian', 'text'],
      ['subtitle', 'Subjudul Bagian', 'text'],
      ['description', 'Deskripsi Bagian', 'textarea'],
      ['image', 'Gambar Latar / Aksen', 'image', 'hero'],
      ['button_primary_text', 'Teks Tombol Utama', 'text'],
      ['button_primary_link', 'Link Tombol Utama', 'text'],
      ['button_secondary_text', 'Teks Tombol Kedua', 'text'],
      ['button_secondary_link', 'Link Tombol Kedua', 'text'],
      ['extra_data', 'Data Ekstra (JSON)', 'json'],
      ['is_active', 'Status Aktif', 'boolean'],
    ],
  },
  about: {
    title: 'Tentang Yayasan',
    subtitle: 'Edit profil, visi, misi, nilai, legalitas, dan struktur pengurus.',
    mode: 'singleton',
    endpoint: 'about',
    fields: [
      ['title', 'Judul Profil', 'text'],
      ['description', 'Deskripsi Singkat', 'textarea'],
      ['vision', 'Visi Lembaga', 'textarea'],
      ['mission', 'Poin-Poin Misi Yayasan', 'list'],
      ['values', 'Nilai Pokok Lembaga (Core Values)', 'tags'],
      ['image', 'Gambar Profil', 'image', 'about'],
      ['legal_info', 'Informasi Legalitas', 'textarea'],
      ['organization_structure', 'Struktur Pengurus & Organisasi', 'org'],
    ],
  },
  programs: {
    title: 'Program',
    subtitle: 'Tambah, edit, aktifkan, dan tandai program unggulan.',
    mode: 'collection',
    list: adminService.programs,
    create: adminService.createProgram,
    update: adminService.updateProgram,
    delete: adminService.deleteProgram,
    fields: [
      ['title', 'Nama Program', 'text', null, true],
      ['slug', 'Slug URL (Otomatis)', 'text'],
      ['category', 'Kategori Program', 'text', null, true],
      ['beneficiaries_count', 'Penerima Manfaat (Jiwa)', 'number'],
      ['target_amount', 'Target Dana (Rp)', 'number'],
      ['collected_amount', 'Dana Terkumpul (Rp)', 'number'],
      ['image', 'Gambar Utama Program', 'image', 'programs'],
      ['short_description', 'Deskripsi Singkat', 'textarea'],
      ['description', 'Deskripsi Lengkap Program', 'textarea', null, true],
      ['is_featured', 'Program Unggulan', 'boolean'],
      ['is_active', 'Status Aktif', 'boolean'],
      ['sort_order', 'Urutan Tampilan', 'number'],
    ],
  },
  galleries: {
    title: 'Galeri',
    subtitle: 'Kelola dokumentasi kegiatan dan status publikasi.',
    mode: 'collection',
    list: adminService.galleries,
    create: adminService.createGallery,
    update: adminService.updateGallery,
    delete: adminService.deleteGallery,
    fields: [
      ['title', 'Judul Foto Kegiatan', 'text', null, true],
      ['category', 'Kategori Dokumentasi', 'text'],
      ['activity_date', 'Tanggal Kegiatan', 'date'],
      ['sort_order', 'Urutan Tampilan', 'number'],
      ['image', 'Foto Dokumentasi', 'image', 'galleries', true],
      ['description', 'Keterangan Dokumentasi', 'textarea'],
      ['is_active', 'Status Publikasi', 'boolean'],
    ],
  },
  donation: {
    title: 'Pengaturan Donasi',
    subtitle: 'Kelola rekening, QRIS, instruksi pembayaran, dan WhatsApp konfirmasi.',
    mode: 'singleton',
    endpoint: 'donation-settings',
    fields: [
      ['title', 'Judul', 'text'],
      ['description', 'Deskripsi', 'textarea'],
      ['collected_amount', 'Dana Terkumpul (Rp)', 'number'],
      ['target_amount', 'Target Dana (Rp)', 'number'],
      ['bank_name', 'Nama Bank', 'text'],
      ['bank_account_number', 'Nomor Rekening', 'text'],
      ['bank_account_holder', 'Pemilik Rekening', 'text'],
      ['qris_image', 'Gambar QRIS', 'image', 'qris'],
      ['whatsapp_number', 'WhatsApp Konfirmasi', 'text'],
      ['payment_instruction', 'Instruksi Pembayaran', 'textarea'],
      ['suggested_amounts', 'Pilihan Nominal Donasi Cepat', 'amounts'],
    ],
  },
  messages: {
    title: 'Pesan Kontak',
    subtitle: 'Baca, filter, arsipkan, dan hapus pesan dari pengunjung.',
    mode: 'messages',
  },
  contact: {
    title: 'Pengaturan Kontak',
    subtitle: 'Kelola alamat, email, WhatsApp, sosial media, maps, dan jam operasional.',
    mode: 'singleton',
    endpoint: 'contact-settings',
    fields: [
      ['address', 'Alamat Lengkap Yayasan', 'textarea'],
      ['operational_hours', 'Jam Operasional', 'text'],
      ['phone', 'Nomor Telepon', 'text'],
      ['whatsapp', 'Nomor WhatsApp', 'text'],
      ['email', 'Alamat Email', 'email'],
      ['facebook_url', 'Facebook URL', 'url'],
      ['instagram_url', 'Instagram URL', 'url'],
      ['youtube_url', 'YouTube URL', 'url'],
      ['tiktok_url', 'TikTok URL', 'url'],
      ['google_maps_embed', 'Lokasi Peta Google Maps', 'map'],
    ],
  },
  site: {
    title: 'Pengaturan Website',
    subtitle: 'Kelola nama website, logo, favicon, SEO, warna, dan maintenance.',
    mode: 'singleton',
    endpoint: 'site-settings',
    fields: [
      ['site_name', 'Nama Website', 'text'],
      ['logo', 'Logo', 'image', 'logos'],
      ['favicon', 'Favicon', 'image', 'logos'],
      ['meta_title', 'Meta Title', 'text'],
      ['meta_description', 'Meta Description', 'textarea'],
      ['meta_keywords', 'Meta Keywords', 'textarea'],
      ['primary_color', 'Warna Utama', 'color'],
      ['maintenance_mode', 'Maintenance Mode', 'boolean'],
    ],
  },
  footer: {
    title: 'Footer',
    subtitle: 'Kelola deskripsi, copyright, quick links, dan social links.',
    mode: 'singleton',
    endpoint: 'footer-settings',
    fields: [
      ['description', 'Deskripsi Footer', 'textarea'],
      ['copyright_text', 'Teks Hak Cipta', 'text'],
      ['quick_links', 'Tautan Cepat (Quick Links)', 'links'],
      ['social_links', 'Tautan Media Sosial', 'links'],
    ],
  },
}

const config = computed(() => configs[route.meta.cms] || configs.site)
const titleField = computed(() => (config.value.mode === 'homepage' ? 'title' : config.value.fields?.[0]?.[0] || 'title'))
const isListMode = computed(() => ['homepage', 'collection'].includes(config.value.mode))
const filteredMessages = computed(() => {
  if (pagination.value) return items.value
  const q = search.value.toLowerCase()
  return items.value.filter((item) => {
    const statusOk = !statusFilter.value || item.status === statusFilter.value
    const text = `${item.name} ${item.email} ${item.subject} ${item.message}`.toLowerCase()
    return statusOk && (!q || text.includes(q))
  })
})

const newTagInput = reactive({})

function normalizeForForm(data = {}) {
  Object.keys(form).forEach((key) => delete form[key])
  for (const field of config.value.fields || []) {
    const key = field[0]
    const type = field[2]
    const value = data?.[key]

    if (type === 'list') {
      let arr = []
      if (Array.isArray(value)) arr = [...value]
      else if (typeof value === 'string' && value.trim()) {
        try { arr = JSON.parse(value) } catch { arr = [value] }
      }
      form[key] = arr.length ? arr : ['']
    } else if (type === 'tags') {
      let arr = []
      if (Array.isArray(value)) arr = [...value]
      else if (typeof value === 'string' && value.trim()) {
        try { arr = JSON.parse(value) } catch { arr = [value] }
      }
      form[key] = arr
      newTagInput[key] = ''
    } else if (type === 'amounts') {
      let arr = []
      if (Array.isArray(value)) arr = [...value]
      else if (typeof value === 'string' && value.trim()) {
        try { arr = JSON.parse(value) } catch { arr = [] }
      }
      form[key] = arr.length ? arr : [50000, 100000, 250000, 500000]
    } else if (type === 'links') {
      let arr = []
      if (Array.isArray(value)) {
        arr = value.map((l) => ({ label: l?.label || '', url: l?.url || '' }))
      } else if (typeof value === 'string' && value.trim()) {
        try {
          const parsed = JSON.parse(value)
          if (Array.isArray(parsed)) arr = parsed.map((l) => ({ label: l?.label || '', url: l?.url || '' }))
        } catch {}
      }
      form[key] = arr
    } else if (type === 'org') {
      let arr = []
      if (Array.isArray(value)) {
        arr = value.map((o) => ({ name: o?.name || '', role: o?.role || '' }))
      } else if (typeof value === 'string' && value.trim()) {
        try {
          const parsed = JSON.parse(value)
          if (Array.isArray(parsed)) arr = parsed.map((o) => ({ name: o?.name || '', role: o?.role || '' }))
        } catch {}
      }
      form[key] = arr
    } else if (type === 'json') {
      form[key] = JSON.stringify(value ?? {}, null, 2)
    } else if (type === 'boolean') {
      form[key] = value !== undefined ? Boolean(value) : (key === 'is_active')
    } else if (type === 'date') {
      form[key] = value ? String(value).slice(0, 10) : ''
    } else {
      form[key] = value ?? ''
    }
  }
  imagePreview.value = data?.image || data?.logo || data?.favicon || data?.qris_image || ''
}

function payload() {
  const data = {}
  for (const field of config.value.fields || []) {
    const [key, , type] = field
    if (type === 'list') {
      data[key] = (form[key] || []).filter((item) => typeof item === 'string' && item.trim() !== '')
    } else if (type === 'tags') {
      data[key] = (form[key] || []).filter((item) => typeof item === 'string' && item.trim() !== '')
    } else if (type === 'amounts') {
      data[key] = (form[key] || []).map(Number).filter((n) => !isNaN(n) && n > 0)
    } else if (type === 'links') {
      data[key] = (form[key] || []).filter((l) => l && (l.label?.trim() || l.url?.trim()))
    } else if (type === 'org') {
      data[key] = (form[key] || []).filter((o) => o && (o.name?.trim() || o.role?.trim()))
    } else if (type === 'json') {
      try {
        data[key] = form[key] ? JSON.parse(form[key]) : null
      } catch {
        throw new Error(`${field[1]} harus berformat JSON valid.`)
      }
    } else if (type === 'number') {
      data[key] = form[key] !== '' && form[key] !== null && !isNaN(Number(form[key])) ? Number(form[key]) : 0
    } else if (type === 'boolean') {
      data[key] = Boolean(form[key])
    } else {
      data[key] = form[key]
    }
  }
  return data
}

// Auto-Slug & Field Event Helpers
function handleTitleInput(field, val) {
  form[field[0]] = val
  if (!editingId.value && field[0] === 'title' && 'slug' in form && !isSlugManuallyEdited.value) {
    form.slug = generateSlug(val)
  }
}

function onSlugManualEdit() {
  isSlugManuallyEdited.value = true
}

function generateSlug(text = '') {
  return String(text || '')
    .toLowerCase()
    .trim()
    .replace(/[^\w\s-]/g, '')
    .replace(/[\s_-]+/g, '-')
    .replace(/^-+|-+$/g, '')
}

function getBooleanTitle(field) {
  const [key, label] = field
  if (key === 'is_featured') return 'Tandai sebagai Program Unggulan'
  if (key === 'is_active') return 'Status Publikasi (Aktif di Website)'
  if (key === 'maintenance_mode') return 'Mode Pemeliharaan (Maintenance Mode)'
  return label
}

function getBooleanDesc(field) {
  const [key] = field
  if (key === 'is_featured') return 'Program diprioritaskan tampil pada bagian sorotan utama di halaman depan.'
  if (key === 'is_active') return 'Jika aktif, data langsung dapat diakses oleh masyarakat di website.'
  if (key === 'maintenance_mode') return 'Pengunjung akan diarahkan ke layar perbaikan saat mode ini aktif.'
  return `Aktifkan atau nonaktifkan pengaturan ${field[1].toLowerCase()}.`
}

// Translate status ke Bahasa Indonesia
function formatStatus(status) {
  const statusMap = {
    'unread': 'Belum Dibaca',
    'read': 'Sudah Dibaca',
    'archived': 'Diarsipkan'
  }
  return statusMap[status] || status
}


// Visual Repeater Helpers
function addListItem(key) {
  if (!Array.isArray(form[key])) form[key] = []
  form[key].push('')
}
function removeListItem(key, index) {
  if (Array.isArray(form[key])) {
    form[key].splice(index, 1)
  }
}

function addTag(key) {
  const val = (newTagInput[key] || '').trim()
  if (!val) return
  if (!Array.isArray(form[key])) form[key] = []
  if (!form[key].includes(val)) {
    form[key].push(val)
  }
  newTagInput[key] = ''
}
function removeTag(key, index) {
  if (Array.isArray(form[key])) {
    form[key].splice(index, 1)
  }
}

function addAmount(key) {
  if (!Array.isArray(form[key])) form[key] = []
  const last = form[key][form[key].length - 1]
  const next = typeof last === 'number' && !isNaN(last) && last > 0 ? last * 2 : 100000
  form[key].push(next)
}
function removeAmount(key, index) {
  if (Array.isArray(form[key])) {
    form[key].splice(index, 1)
  }
}
function formatRupiahSimple(val) {
  const num = Number(val) || 0
  return 'Rp ' + num.toLocaleString('id-ID')
}

function addLink(key) {
  if (!Array.isArray(form[key])) form[key] = []
  form[key].push({ label: '', url: '' })
}
function removeLink(key, index) {
  if (Array.isArray(form[key])) {
    form[key].splice(index, 1)
  }
}

function addOrg(key) {
  if (!Array.isArray(form[key])) form[key] = []
  form[key].push({ name: '', role: '' })
}
function removeOrg(key, index) {
  if (Array.isArray(form[key])) {
    form[key].splice(index, 1)
  }
}

async function load() {
  loading.value = true
  error.value = ''
  try {
    if (config.value.mode === 'singleton') {
      normalizeForForm((await adminService.getSetting(config.value.endpoint)).data || {})
    } else if (config.value.mode === 'homepage') {
      items.value = (await adminService.homepageSections()).data || []
      if (!editingId.value) {
        startNew()
      }
    } else if (config.value.mode === 'messages') {
      const response = await adminService.messages(queryParams())
      applyPage(response.data)
    } else {
      const response = await config.value.list(queryParams())
      applyPage(response.data)
      if (!editingId.value) {
        startNew()
      }
    }
  } catch (err) {
    error.value = err.response?.data?.message || 'Data belum bisa dimuat.'
  } finally {
    loading.value = false
  }
}

function reloadRoute() {
  page.value = 1
  pagination.value = null
  items.value = []
  editingId.value = null
  startNew()
  load()
}

function queryParams() {
  const params = { page: page.value, per_page: perPage.value }
  if (search.value) params.search = search.value
  if (statusFilter.value) params.status = statusFilter.value
  return params
}

function applyPage(data) {
  if (data?.data && Array.isArray(data.data)) {
    items.value = data.data
    pagination.value = {
      current_page: data.current_page,
      last_page: data.last_page,
      total: data.total,
      from: data.from,
      to: data.to,
    }
    return
  }
  items.value = data || []
  pagination.value = null
}

function goPage(target) {
  if (!pagination.value) return
  page.value = Math.min(Math.max(target, 1), pagination.value.last_page || 1)
  load()
}

function debouncedLoad() {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => {
    page.value = 1
    load()
  }, 350)
}

function startNew() {
  editingId.value = null
  isSlugManuallyEdited.value = false
  normalizeForForm({})
  error.value = ''
  toast.value = `Formulir tambah ${config.value.title.toLowerCase()} baru siap diisi.`
  setTimeout(() => {
    if (toast.value.startsWith('Formulir tambah')) toast.value = ''
  }, 2200)

  nextTick(() => {
    const formEl = document.querySelector('.panel.form')
    if (formEl) {
      if (window.innerWidth < 980) {
        formEl.scrollIntoView({ behavior: 'smooth' })
      }
      const firstInput = formEl.querySelector('input:not([type="checkbox"]):not([type="hidden"]), textarea')
      if (firstInput) {
        firstInput.focus()
      }
    }
  })
}

function edit(item) {
  editingId.value = item.id
  isSlugManuallyEdited.value = true
  normalizeForForm(item)
  error.value = ''

  nextTick(() => {
    const formEl = document.querySelector('.panel.form')
    if (formEl && window.innerWidth < 980) {
      formEl.scrollIntoView({ behavior: 'smooth' })
    }
  })
}

async function save() {
  saving.value = true
  error.value = ''
  try {
    // Validasi field wajib
    for (const field of config.value.fields || []) {
      const [key, label, , , isRequired] = field
      if (isRequired) {
        const val = form[key]
        if (val === undefined || val === null || (typeof val === 'string' && val.trim() === '')) {
          throw new Error(`Bidang "${label}" wajib diisi sebelum menyimpan data.`)
        }
      }
    }

    const data = payload()
    const prevEditingId = editingId.value
    let savedItem = null

    if (config.value.mode === 'singleton') {
      await adminService.updateSetting(config.value.endpoint, data)
      try {
        const layoutStore = usePublicLayoutStore()
        await layoutStore.load(true)
      } catch {}
    } else if (config.value.mode === 'homepage') {
      const res = prevEditingId
        ? await adminService.saveHomepageSection(prevEditingId, data)
        : await adminService.createHomepageSection(data)
      savedItem = res?.data
    } else {
      const res = prevEditingId
        ? await config.value.update(prevEditingId, data)
        : await config.value.create(data)
      savedItem = res?.data
    }

    toast.value = prevEditingId ? 'Perubahan berhasil disimpan.' : 'Data baru berhasil ditambahkan!'
    await load()

    if (savedItem?.id) {
      const found = items.value.find((i) => i.id === savedItem.id)
      if (found) {
        edit(found)
      }
    } else if (prevEditingId) {
      const found = items.value.find((i) => i.id === prevEditingId)
      if (found) {
        edit(found)
      }
    }
  } catch (err) {
    error.value = err.response?.data?.message || err.message || 'Data belum berhasil disimpan.'
    nextTick(() => {
      const alertEl = document.querySelector('.form-alert')
      if (alertEl) {
        alertEl.scrollIntoView({ behavior: 'smooth', block: 'center' })
      }
    })
  } finally {
    saving.value = false
    setTimeout(() => {
      if (toast.value === 'Perubahan berhasil disimpan.' || toast.value === 'Data baru berhasil ditambahkan!') {
        toast.value = ''
      }
    }, 2800)
  }
}

async function destroy(item) {
  if (!confirm(`Hapus "${item[titleField.value] || item.name || 'data'}"?`)) return
  try {
    if (config.value.mode === 'homepage') await adminService.deleteHomepageSection(item.id)
    else if (config.value.mode === 'messages') await adminService.deleteMessage(item.id)
    else await config.value.delete(item.id)
    toast.value = 'Data berhasil dihapus.'
    await load()
  } catch (err) {
    error.value = err.response?.data?.message || 'Data belum berhasil dihapus.'
  }
}

async function updateMessage(item, status) {
  await adminService.updateMessage(item.id, { status })
  await load()
}

async function upload(event, field) {
  const file = event.target.files?.[0]
  if (!file) return
  saving.value = true
  try {
    const response = await uploadService.image(file, field[3] || 'uploads')
    form[field[0]] = response.data.url
    imagePreview.value = response.data.url
  } catch (err) {
    error.value = err.response?.data?.message || 'Upload gambar gagal.'
  } finally {
    saving.value = false
  }
}

function resolveMapPreview(val) {
  if (!val) return ''
  let clean = val.trim()
  const iframeMatch = clean.match(/src=["']([^"']+)["']/i)
  if (iframeMatch) clean = iframeMatch[1]
  if (clean.includes('/maps/embed') || clean.includes('output=embed')) return clean
  if (clean.includes('/maps/place/')) {
    const pm = clean.match(/\/maps\/place\/([^/@]+)/i)
    if (pm) return `https://maps.google.com/maps?q=${pm[1]}&output=embed`
  }
  const cm = clean.match(/@(-?\d+\.\d+),(-?\d+\.\d+)/)
  if (cm) return `https://maps.google.com/maps?q=${cm[1]},${cm[2]}&output=embed`
  if (!clean.startsWith('http://') && !clean.startsWith('https://')) {
    return `https://maps.google.com/maps?q=${encodeURIComponent(clean)}&output=embed`
  }
  return clean
}

function handleMapInput(event) {
  const val = event.target.value
  const iframeMatch = val.match(/src=["']([^"']+)["']/i)
  if (iframeMatch) {
    form.google_maps_embed = iframeMatch[1]
  }
}

function resolveMapExternalLink(val) {
  if (!val) return 'https://www.google.com/maps'
  let clean = val.trim()
  const iframeMatch = clean.match(/src=["']([^"']+)["']/i)
  if (iframeMatch) clean = iframeMatch[1]
  if (clean.includes('maps.app.goo.gl') || clean.includes('/maps/place')) {
    return clean
  }
  const qMatch = clean.match(/[?&]q=([^&]+)/)
  if (qMatch) {
    return `https://www.google.com/maps/search/?api=1&query=${qMatch[1]}`
  }
  return clean
}

watch(() => route.meta.cms, reloadRoute)
watch([search, statusFilter, perPage], debouncedLoad)
onMounted(load)
</script>

<template>
  <AdminShell :title="config.title" :subtitle="config.subtitle">
    <transition name="toast">
      <p v-if="toast" class="toast">
        <i class="fa-solid fa-circle-check"></i> {{ toast }}
      </p>
    </transition>

    <section v-if="loading" class="cms-loading">
      <aside v-if="isListMode" class="panel skeleton-list" aria-label="Memuat daftar">
        <div class="skeleton skeleton--title"></div>
        <div v-for="i in 5" :key="i" class="skeleton skeleton--row"></div>
      </aside>
      <div class="panel skeleton-form" aria-label="Memuat formulir">
        <div class="skeleton skeleton--title"></div>
        <div class="fields">
          <div v-for="i in 6" :key="i" class="skeleton skeleton--input"></div>
        </div>
      </div>
    </section>

    <div v-else-if="error && !items.length && config.mode !== 'singleton'" class="state state--error">
      <i class="fa-solid fa-triangle-exclamation"></i>
      <span>{{ error }}</span>
      <button type="button" class="btn-sm" @click="load">Coba Lagi</button>
    </div>

    <template v-else-if="config.mode === 'messages'">
      <section class="toolbar">
        <div class="toolbar__search">
          <i class="fa-solid fa-search"></i>
          <input v-model="search" placeholder="Cari nama, email, subjek..." />
        </div>
        <select v-model="statusFilter">
          <option value="">Semua status</option>
          <option value="unread">Belum Dibaca</option>
          <option value="read">Sudah Dibaca</option>
          <option value="archived">Diarsipkan</option>
        </select>
        <select v-model="perPage">
          <option :value="10">10 / halaman</option>
          <option :value="20">20 / halaman</option>
          <option :value="50">50 / halaman</option>
        </select>
      </section>

      <section class="panel">
        <p v-if="!filteredMessages.length" class="state">Belum ada pesan sesuai filter.</p>
        <article v-for="message in filteredMessages" :key="message.id" class="message">
          <div class="message__content">
            <div class="message__head">
              <strong>{{ message.name }}</strong>
              <span class="message__status" :class="'message__status--' + (message.status || 'unread')">
                {{ formatStatus(message.status || 'unread') }}
              </span>
            </div>
            <small>{{ message.email }} &middot; {{ message.phone || '-' }}</small>
            <h3 v-if="message.subject">{{ message.subject }}</h3>
            <p>{{ message.message }}</p>
          </div>
          <div class="message__actions">
            <button class="btn-sm" @click="updateMessage(message, 'read')">Baca</button>
            <button class="btn-sm btn-ghost" @click="updateMessage(message, 'archived')">Arsip</button>
            <button class="btn-sm btn-danger" @click="destroy(message)">Hapus</button>
          </div>
        </article>

        <div v-if="pagination" class="pagination">
          <span class="pagination__info">{{ pagination.from || 0 }}-{{ pagination.to || 0 }} dari {{ pagination.total || 0 }}</span>
          <div class="pagination__controls">
            <button :disabled="pagination.current_page <= 1" @click="goPage(pagination.current_page - 1)">
              <i class="fa-solid fa-chevron-left"></i> Sebelumnya
            </button>
            <strong>{{ pagination.current_page }} / {{ pagination.last_page }}</strong>
            <button :disabled="pagination.current_page >= pagination.last_page" @click="goPage(pagination.current_page + 1)">
              Selanjutnya <i class="fa-solid fa-chevron-right"></i>
            </button>
          </div>
        </div>
      </section>
    </template>

    <template v-else>
      <section v-if="['collection'].includes(config.mode)" class="toolbar">
        <div class="toolbar__search">
          <i class="fa-solid fa-search"></i>
          <input v-model="search" placeholder="Cari data..." />
        </div>
        <select v-model="statusFilter">
          <option value="">Semua status</option>
          <option value="active">Aktif</option>
          <option value="inactive">Nonaktif</option>
        </select>
        <select v-model="perPage">
          <option :value="10">10 / halaman</option>
          <option :value="20">20 / halaman</option>
          <option :value="50">50 / halaman</option>
        </select>
      </section>

      <section :class="['cms-grid', { 'cms-grid--singleton': config.mode === 'singleton' }]">
        <aside v-if="config.mode !== 'singleton'" class="panel list">
          <div class="panel-head">
            <div>
              <h2>Daftar {{ config.title }}</h2>
              <small class="panel-subtitle">{{ items.length }} data terdaftar</small>
            </div>
            <button
              type="button"
              class="btn-primary btn-add-new"
              :class="{ 'btn-add-new--active': !editingId }"
              title="Buat data baru"
              @click="startNew"
            >
              <i class="fa-solid fa-plus"></i> Tambah Baru
            </button>
          </div>

          <div v-if="!editingId" class="list-status-pill">
            <span class="pulse-dot"></span>
            <span>Mode Tambah Baru Sedang Aktif</span>
          </div>

          <p v-if="!items.length" class="state">Belum ada data {{ config.title.toLowerCase() }}.</p>
          <div class="list-cards">
            <button
              v-for="item in items"
              :key="item.id"
              class="list-card"
              :class="{ active: editingId === item.id }"
              type="button"
              @click="edit(item)"
            >
              <div v-if="item.image" class="list-card__thumb">
                <img :src="item.image" alt="" />
              </div>
              <div class="list-card__body">
                <div class="list-card__head">
                  <strong class="list-card__title">{{ item[titleField] || item.section_key || item.name }}</strong>
                  <span v-if="item.is_featured" class="badge-featured" title="Program Unggulan"><i class="fa-solid fa-star"></i></span>
                </div>
                <div class="list-card__meta">
                  <span v-if="item.category" class="list-card__badge">{{ item.category }}</span>
                  <span
                    v-if="item.is_active !== undefined"
                    class="list-card__status"
                    :class="item.is_active ? 'list-card__status--active' : 'list-card__status--inactive'"
                  >
                    {{ item.is_active ? 'Aktif' : 'Nonaktif' }}
                  </span>
                </div>
              </div>
            </button>
          </div>

          <div v-if="pagination && pagination.last_page > 1" class="pagination pagination--list">
            <button :disabled="pagination.current_page <= 1" @click="goPage(pagination.current_page - 1)" title="Sebelumnya">
              <i class="fa-solid fa-chevron-left"></i>
            </button>
            <span>{{ pagination.current_page }} / {{ pagination.last_page }}</span>
            <button :disabled="pagination.current_page >= pagination.last_page" @click="goPage(pagination.current_page + 1)" title="Selanjutnya">
              <i class="fa-solid fa-chevron-right"></i>
            </button>
          </div>
        </aside>

        <form class="panel form" @submit.prevent="save">
          <!-- Inline Dismissible Alert for Errors -->
          <div v-if="error" class="form-alert form-alert--error">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <div class="form-alert__body">
              <strong>Perhatian:</strong>
              <span>{{ error }}</span>
            </div>
            <button type="button" class="form-alert__close" @click="error = ''" title="Tutup peringatan">
              <i class="fa-solid fa-xmark"></i>
            </button>
          </div>

          <div class="panel-head">
            <div>
              <div class="form-title-row">
                <h2>{{ editingId ? 'Edit ' + config.title : config.mode === 'singleton' ? config.title : 'Tambah ' + config.title + ' Baru' }}</h2>
                <span v-if="config.mode !== 'singleton'" class="mode-badge" :class="editingId ? 'mode-badge--edit' : 'mode-badge--create'">
                  {{ editingId ? 'Mode Edit Data' : 'Mode Tambah Baru' }}
                </span>
              </div>
              <small class="panel-subtitle">
                {{ editingId ? `Perbarui informasi ${config.title.toLowerCase()} dan simpan perubahan` : `Isi formulir dengan lengkap untuk menambahkan ${config.title.toLowerCase()} baru` }}
              </small>
            </div>
            <div class="panel-head__actions">
              <button
                v-if="editingId && config.mode !== 'singleton'"
                type="button"
                class="btn-sm btn-ghost"
                @click="startNew"
                title="Batal edit dan beralih ke form data baru"
              >
                <i class="fa-solid fa-plus"></i> Buat Baru
              </button>
              <button type="submit" :disabled="saving" class="btn-primary">
                <span v-if="!saving">
                  <i :class="editingId || config.mode === 'singleton' ? 'fa-solid fa-floppy-disk' : 'fa-solid fa-plus'"></i>
                  {{ editingId || config.mode === 'singleton' ? 'Simpan Perubahan' : 'Tambah ' + config.title + ' Baru' }}
                </span>
                <span v-else><i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...</span>
              </button>
            </div>
          </div>

          <div class="fields">
            <div
              v-for="field in config.fields"
              :key="field[0]"
              class="field-group"
              :class="{ 'field-group--wide': ['textarea', 'json', 'image', 'map', 'list', 'tags', 'amounts', 'links', 'org'].includes(field[2]) }"
            >
              <div class="field-header">
                <span class="field-label">
                  {{ field[1] }}
                  <span v-if="field[4]" class="required-star" title="Wajib diisi">*</span>
                </span>
                <span v-if="field[2] === 'list'" class="field-counter-badge">{{ (form[field[0]] || []).length }} Poin</span>
                <span v-else-if="field[2] === 'tags'" class="field-counter-badge">{{ (form[field[0]] || []).length }} Nilai</span>
                <span v-else-if="field[2] === 'amounts'" class="field-counter-badge">{{ (form[field[0]] || []).length }} Pilihan</span>
                <span v-else-if="field[2] === 'links'" class="field-counter-badge">{{ (form[field[0]] || []).length }} Tautan</span>
                <span v-else-if="field[2] === 'org'" class="field-counter-badge">{{ (form[field[0]] || []).length }} Pengurus</span>
                <span v-else-if="field[2] === 'json'" class="field-badge-json">Format JSON</span>
              </div>

              <!-- 1. List of Items (e.g. Mission Points) -->
              <div v-if="field[2] === 'list'" class="field-list-card">
                <div v-if="!(form[field[0]] || []).length" class="field-empty-state">
                  <i class="fa-solid fa-list-check"></i> Belum ada poin. Klik tombol di bawah untuk menambah poin baru.
                </div>
                <div
                  v-for="(item, idx) in form[field[0]]"
                  :key="idx"
                  class="field-list-row"
                >
                  <span class="field-list-num">{{ idx + 1 }}</span>
                  <input
                    v-model="form[field[0]][idx]"
                    type="text"
                    class="field-list-input"
                    :placeholder="`Tulis ${field[1].toLowerCase()} #${idx + 1}...`"
                  />
                  <button
                    type="button"
                    class="btn-remove-row"
                    title="Hapus poin ini"
                    @click="removeListItem(field[0], idx)"
                  >
                    <i class="fa-solid fa-trash-can"></i>
                  </button>
                </div>
                <button
                  type="button"
                  class="btn-add-dashed"
                  @click="addListItem(field[0])"
                >
                  <i class="fa-solid fa-plus"></i> Tambah Poin Baru
                </button>
              </div>

              <!-- 2. Tag Chips (e.g. Core Values) -->
              <div v-else-if="field[2] === 'tags'" class="field-tags-card">
                <div class="field-tags-list">
                  <div
                    v-for="(tag, idx) in form[field[0]]"
                    :key="idx"
                    class="tag-pill"
                  >
                    <i class="fa-solid fa-check"></i>
                    <span>{{ tag }}</span>
                    <button
                      type="button"
                      class="btn-remove-tag"
                      title="Hapus nilai ini"
                      @click="removeTag(field[0], idx)"
                    >
                      <i class="fa-solid fa-xmark"></i>
                    </button>
                  </div>
                  <span v-if="!(form[field[0]] || []).length" class="tag-empty-hint">
                    Belum ada nilai terdaftar. Tulis nilai baru di bawah lalu tekan tombol Tambah.
                  </span>
                </div>

                <div class="tag-input-group">
                  <input
                    v-model="newTagInput[field[0]]"
                    type="text"
                    class="tag-text-input"
                    placeholder="Ketik nilai baru (cth: Amanah, Transparan, Humanis) lalu tekan Enter"
                    @keydown.enter.prevent="addTag(field[0])"
                  />
                  <button
                    type="button"
                    class="btn-add-tag-action"
                    @click="addTag(field[0])"
                  >
                    <i class="fa-solid fa-plus"></i> Tambah Nilai
                  </button>
                </div>
              </div>

              <!-- 3. Suggested Amounts (Donation) -->
              <div v-else-if="field[2] === 'amounts'" class="field-amounts-card">
                <div class="amounts-grid">
                  <div
                    v-for="(amt, idx) in form[field[0]]"
                    :key="idx"
                    class="amount-item"
                  >
                    <div class="amount-input-box">
                      <span class="amount-curr">Rp</span>
                      <input
                        v-model.number="form[field[0]][idx]"
                        type="number"
                        min="1000"
                        step="1000"
                        class="amount-field"
                      />
                    </div>
                    <span class="amount-badge">{{ formatRupiahSimple(amt) }}</span>
                    <button
                      type="button"
                      class="btn-remove-amt"
                      title="Hapus nominal ini"
                      @click="removeAmount(field[0], idx)"
                    >
                      <i class="fa-solid fa-xmark"></i>
                    </button>
                  </div>
                </div>

                <button
                  type="button"
                  class="btn-add-dashed"
                  @click="addAmount(field[0])"
                >
                  <i class="fa-solid fa-plus"></i> Tambah Pilihan Nominal
                </button>
              </div>

              <!-- 4. Link Pairs (Quick Links & Social Links) -->
              <div v-else-if="field[2] === 'links'" class="field-links-card">
                <div v-if="!(form[field[0]] || []).length" class="field-empty-state">
                  <i class="fa-solid fa-link"></i> Belum ada tautan ditambahkan.
                </div>
                <div
                  v-for="(link, idx) in form[field[0]]"
                  :key="idx"
                  class="link-row-card"
                >
                  <div class="link-field-cell">
                    <label class="link-sublabel">Label / Nama Tautan</label>
                    <input
                      v-model="link.label"
                      type="text"
                      placeholder="Contoh: Tentang Kami / Instagram"
                      class="link-input"
                    />
                  </div>
                  <div class="link-field-cell link-field-cell--url">
                    <label class="link-sublabel">Tujuan URL</label>
                    <input
                      v-model="link.url"
                      type="text"
                      placeholder="Contoh: /tentang atau https://..."
                      class="link-input"
                    />
                  </div>
                  <button
                    type="button"
                    class="btn-remove-row"
                    title="Hapus tautan ini"
                    @click="removeLink(field[0], idx)"
                  >
                    <i class="fa-solid fa-trash-can"></i>
                  </button>
                </div>

                <button
                  type="button"
                  class="btn-add-dashed"
                  @click="addLink(field[0])"
                >
                  <i class="fa-solid fa-plus"></i> Tambah Tautan Baru
                </button>
              </div>

              <!-- 5. Organization Structure -->
              <div v-else-if="field[2] === 'org'" class="field-links-card">
                <div v-if="!(form[field[0]] || []).length" class="field-empty-state">
                  <i class="fa-solid fa-sitemap"></i> Belum ada data pengurus.
                </div>
                <div
                  v-for="(org, idx) in form[field[0]]"
                  :key="idx"
                  class="link-row-card"
                >
                  <div class="link-field-cell">
                    <label class="link-sublabel">Nama Pengurus</label>
                    <input
                      v-model="org.name"
                      type="text"
                      placeholder="Contoh: H. Ahmad Supriyadi"
                      class="link-input"
                    />
                  </div>
                  <div class="link-field-cell">
                    <label class="link-sublabel">Jabatan / Amanah</label>
                    <input
                      v-model="org.role"
                      type="text"
                      placeholder="Contoh: Ketua Pembina Yayasan"
                      class="link-input"
                    />
                  </div>
                  <button
                    type="button"
                    class="btn-remove-row"
                    title="Hapus pengurus ini"
                    @click="removeOrg(field[0], idx)"
                  >
                    <i class="fa-solid fa-trash-can"></i>
                  </button>
                </div>

                <button
                  type="button"
                  class="btn-add-dashed"
                  @click="addOrg(field[0])"
                >
                  <i class="fa-solid fa-plus"></i> Tambah Pengurus
                </button>
              </div>

              <!-- Dedicated Google Maps Embed Widget -->
              <div v-else-if="field[2] === 'map'" class="field-map-widget">
                <div class="field-map-input-wrap">
                  <div class="field-map-input-bar">
                    <i class="fa-solid fa-map-location-dot field-map-icon"></i>
                    <input
                      v-model="form[field[0]]"
                      type="text"
                      class="field-map-input"
                      placeholder="Tempel link share (maps.app.goo.gl), link Google Maps, atau kode <iframe> di sini"
                      @input="handleMapInput($event)"
                    />
                    <button
                      v-if="form[field[0]]"
                      type="button"
                      class="btn-clear-map"
                      title="Hapus URL Peta"
                      @click="form[field[0]] = ''"
                    >
                      <i class="fa-solid fa-xmark"></i>
                    </button>
                  </div>
                  <div class="field-map-hint">
                    <i class="fa-solid fa-lightbulb"></i>
                    <span><strong>Tips Praktis:</strong> Cukup salin tautan dari tab Google Maps atau klik <em>Bagikan &rarr; Sematkan peta &rarr; Salin HTML</em>. Sistem otomatis mengonversinya.</span>
                  </div>
                </div>

                <!-- Live Map Preview Card -->
                <div v-if="form[field[0]]" class="field-map-card">
                  <div class="field-map-card-head">
                    <div class="field-map-badge">
                      <span class="pulse-dot"></span>
                      <span>Pratinjau Peta Interaktif</span>
                    </div>
                    <a
                      :href="resolveMapExternalLink(form[field[0]])"
                      target="_blank"
                      rel="noopener noreferrer"
                      class="field-map-ext-link"
                    >
                      <span>Buka di Google Maps</span>
                      <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </a>
                  </div>
                  <div class="field-map-iframe-container">
                    <iframe
                      :src="resolveMapPreview(form[field[0]])"
                      class="field-map-iframe"
                      loading="lazy"
                      referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                  </div>
                </div>
              </div>

              <!-- Modern Toggle Switch Card -->
              <div v-else-if="field[2] === 'boolean'" class="field-toggle-card">
                <div class="field-toggle-info">
                  <span class="field-toggle-title">{{ getBooleanTitle(field) }}</span>
                  <span class="field-toggle-desc">{{ getBooleanDesc(field) }}</span>
                </div>
                <label class="toggle-switch">
                  <input v-model="form[field[0]]" type="checkbox" />
                  <span class="toggle-slider"></span>
                </label>
              </div>

              <!-- Unified Image Upload Widget -->
              <div v-else-if="field[2] === 'image'" class="field-image-widget">
                <div v-if="form[field[0]]" class="image-preview-card">
                  <img :src="form[field[0]]" alt="Preview" class="image-preview-img" />
                  <div class="image-preview-info">
                    <span class="image-preview-url" :title="form[field[0]]">{{ form[field[0]] }}</span>
                    <button type="button" class="btn-remove-image" @click="form[field[0]] = ''">
                      <i class="fa-solid fa-trash-can"></i> Hapus Gambar
                    </button>
                  </div>
                </div>

                <div class="image-upload-controls">
                  <label class="btn-upload-picker">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <span>Pilih Berkas Gambar</span>
                    <input type="file" accept="image/png,image/jpeg,image/webp,image/svg+xml" @change="upload($event, field)" hidden />
                  </label>
                  <div class="image-url-wrap">
                    <i class="fa-solid fa-link"></i>
                    <input v-model="form[field[0]]" type="url" placeholder="Atau masukkan URL gambar langsung (https://...)" />
                  </div>
                </div>
              </div>

              <!-- Title Input with Auto-Slug trigger -->
              <input
                v-else-if="field[0] === 'title'"
                :value="form[field[0]]"
                type="text"
                :placeholder="field[1]"
                @input="handleTitleInput(field, $event.target.value)"
              />

              <!-- Slug Input with Magic Auto Hint -->
              <div v-else-if="field[0] === 'slug'" class="field-slug-wrap">
                <input
                  v-model="form[field[0]]"
                  type="text"
                  :placeholder="field[1]"
                  @input="onSlugManualEdit"
                />
                <small v-if="!editingId" class="field-slug-hint">
                  <i class="fa-solid fa-wand-magic-sparkles"></i> Otomatis terisi saat mengetik nama judul program.
                </small>
              </div>

              <!-- Category Input with Preset Chips -->
              <div v-else-if="field[0] === 'category'" class="field-category-wrap">
                <input
                  v-model="form[field[0]]"
                  type="text"
                  :placeholder="field[1]"
                  list="cat-datalist"
                />
                <datalist id="cat-datalist">
                  <option v-for="cat in categoryPresets[route.meta.cms] || []" :key="cat" :value="cat" />
                </datalist>
                <div v-if="(categoryPresets[route.meta.cms] || []).length" class="category-preset-chips">
                  <button
                    v-for="cat in categoryPresets[route.meta.cms] || []"
                    :key="cat"
                    type="button"
                    class="cat-chip"
                    :class="{ active: form[field[0]] === cat }"
                    @click="form[field[0]] = cat"
                  >
                    {{ cat }}
                  </button>
                </div>
              </div>

              <!-- Number Input with Live Rupiah Helper -->
              <div v-else-if="field[2] === 'number'" class="field-number-wrap">
                <div class="input-with-icon">
                  <span v-if="['target_amount', 'collected_amount', 'target', 'collected'].includes(field[0])" class="input-prefix">Rp</span>
                  <input
                    v-model.number="form[field[0]]"
                    type="number"
                    min="0"
                    :placeholder="field[1]"
                    :class="{ 'has-prefix': ['target_amount', 'collected_amount', 'target', 'collected'].includes(field[0]) }"
                  />
                </div>
                <span
                  v-if="['target_amount', 'collected_amount', 'target', 'collected'].includes(field[0]) && form[field[0]]"
                  class="field-currency-helper"
                >
                  <i class="fa-solid fa-coins"></i> Terbilang: {{ formatRupiahSimple(form[field[0]]) }}
                </span>
              </div>

              <!-- Textarea or Raw JSON Editor -->
              <textarea
                v-else-if="field[2] === 'textarea' || field[2] === 'json'"
                v-model="form[field[0]]"
                :class="{ 'is-json': field[2] === 'json' }"
                :rows="field[2] === 'json' ? 8 : 4"
                :placeholder="field[2] === 'json' ? 'Contoh format: { &quot;key&quot;: &quot;value&quot; }' : field[1]"
              />

              <!-- Standard input -->
              <input v-else v-model="form[field[0]]" :type="field[2]" :placeholder="field[1]" />
            </div>
          </div>

          <div class="form-footer">
            <button
              v-if="editingId && config.mode !== 'singleton'"
              type="button"
              class="btn-danger"
              @click="destroy(items.find(i => i.id === editingId))"
            >
              <i class="fa-solid fa-trash-can"></i> Hapus {{ config.title }} Ini
            </button>
            <div class="form-footer__right">
              <button
                v-if="editingId && config.mode !== 'singleton'"
                type="button"
                class="btn-sm btn-ghost"
                @click="startNew"
              >
                Batal Edit
              </button>
              <button type="submit" :disabled="saving" class="btn-primary">
                <span v-if="!saving">
                  <i :class="editingId || config.mode === 'singleton' ? 'fa-solid fa-floppy-disk' : 'fa-solid fa-plus'"></i>
                  {{ editingId || config.mode === 'singleton' ? 'Simpan Perubahan' : 'Tambah ' + config.title + ' Baru' }}
                </span>
                <span v-else><i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...</span>
              </button>
            </div>
          </div>
        </form>
      </section>
    </template>
  </AdminShell>
</template>

<style scoped>
.toast {
  position: fixed;
  right: 24px;
  top: 22px;
  z-index: 80;
  background: linear-gradient(135deg, #0f684b, #0b4735);
  color: #fff;
  border-radius: 16px;
  padding: 14px 20px;
  box-shadow: 0 18px 48px rgba(6, 55, 39, 0.28);
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 600;
  font-size: 0.9rem;
}

.toast-enter-active { transition: all 0.3s ease; }
.toast-leave-active { transition: all 0.25s ease; }
.toast-enter-from { opacity: 0; transform: translateY(-12px); }
.toast-leave-to { opacity: 0; transform: translateY(-12px); }

.state {
  padding: 32px;
  text-align: center;
  color: #65746b;
  background: rgba(255,255,255,0.7);
  border: 1px dashed #d9e4dd;
  border-radius: 20px;
  font-size: 0.9rem;
}

.state--error {
  background: #fff1f2;
  color: var(--color-error);
  border: 1px solid #fecdd3;
}

.cms-grid {
  display: grid;
  grid-template-columns: 340px minmax(0, 1fr);
  gap: 28px;
  align-items: start;
}

.cms-grid--singleton {
  grid-template-columns: 1fr;
  max-width: 1060px;
}

.panel {
  background: #FFFFFF;
  border: 1px solid #E2E8F0;
  border-radius: 16px;
  padding: 28px 32px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 0 4px 14px rgba(15, 60, 40, 0.03);
}

.panel-head {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 16px;
  margin-bottom: 24px;
  padding-bottom: 18px;
  border-bottom: 1px solid #F1F5F9;
}

.panel-head h2 {
  font-size: 1.22rem;
  font-weight: 700;
  color: #0F172A;
  font-family: var(--font-heading);
  letter-spacing: -0.01em;
  margin: 0 0 2px;
}

.panel-subtitle {
  color: #64748B;
  font-size: 0.82rem;
}

.panel-head__actions {
  display: flex;
  align-items: center;
  gap: 10px;
}

.form-title-row {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
}

.mode-badge {
  font-size: 0.68rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.04em;
  padding: 3px 9px;
  border-radius: 9999px;
}

.mode-badge--create {
  background: #DCFCE7;
  color: #15803D;
  border: 1px solid #BBF7D0;
}

.mode-badge--edit {
  background: #FEF3C7;
  color: #B45309;
  border: 1px solid #FDE68A;
}

.btn-primary {
  border: 0;
  border-radius: 9px;
  background: #0F8A5F;
  color: #FFFFFF;
  padding: 10px 18px;
  font-weight: 600;
  font-size: 0.86rem;
  cursor: pointer;
  transition: all 0.2s ease;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  box-shadow: 0 2px 6px rgba(15, 138, 95, 0.2);
}

.btn-primary:hover:not(:disabled) {
  background: #0A6D4B;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(15, 138, 95, 0.28);
}

.btn-primary:disabled {
  opacity: 0.6;
  cursor: wait;
}

.btn-add-new {
  font-size: 0.82rem;
  padding: 8px 14px;
}

.btn-add-new--active {
  background: #08241A !important;
  color: #E5C378 !important;
  box-shadow: 0 0 0 2px rgba(8, 36, 26, 0.2), 0 2px 6px rgba(0, 0, 0, 0.15) !important;
}

.btn-sm {
  border: 0;
  border-radius: 8px;
  padding: 7px 13px;
  font-weight: 600;
  font-size: 0.78rem;
  cursor: pointer;
  transition: all 0.2s ease;
  background: #F1F5F9;
  color: #334155;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.btn-sm:hover {
  background: #E2E8F0;
}

.btn-ghost {
  background: transparent;
  border: 1px solid #E2E8F0;
}

.btn-ghost:hover {
  border-color: #94A3B8;
  background: #F8FAFC;
}

.btn-danger {
  background: #FEF2F2;
  color: #EF4444;
  border: 1px solid #FEE2E2;
  border-radius: 9px;
  padding: 10px 18px;
  font-weight: 600;
  font-size: 0.85rem;
  cursor: pointer;
  transition: all 0.2s ease;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.btn-danger:hover {
  background: #FEE2E2;
}

/* Form Alert Banner */
.form-alert {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  padding: 14px 18px;
  border-radius: 12px;
  margin-bottom: 22px;
}

.form-alert--error {
  background: #FEF2F2;
  border: 1px solid #FEE2E2;
  color: #991B1B;
}

.form-alert i {
  font-size: 1.1rem;
  margin-top: 2px;
  flex-shrink: 0;
}

.form-alert__body {
  flex: 1;
  font-size: 0.88rem;
  line-height: 1.45;
}

.form-alert__body strong {
  display: block;
  margin-bottom: 2px;
}

.form-alert__close {
  background: transparent;
  border: none;
  color: #991B1B;
  cursor: pointer;
  padding: 4px;
  font-size: 0.95rem;
  opacity: 0.7;
}

.form-alert__close:hover {
  opacity: 1;
}

/* List Side Panel */
.list {
  display: flex;
  flex-direction: column;
}

.list-status-pill {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  background: #F0FDF4;
  color: #15803D;
  border: 1px solid #BBF7D0;
  border-radius: 8px;
  padding: 6px 12px;
  font-size: 0.75rem;
  font-weight: 600;
  margin-bottom: 14px;
  width: fit-content;
}

.list-cards {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.list-card {
  display: flex;
  align-items: center;
  gap: 12px;
  width: 100%;
  text-align: left;
  background: #FFFFFF;
  color: #1E293B;
  border: 1.5px solid #E2E8F0;
  border-radius: 12px;
  padding: 12px 14px;
  cursor: pointer;
  transition: all 0.2s ease;
  position: relative;
}

.list-card:hover {
  border-color: #0F8A5F;
  background: #F8FAF9;
  transform: translateY(-1px);
}

.list-card.active {
  border-color: #0F8A5F;
  background: #F0FDF4;
  box-shadow: 0 2px 8px rgba(15, 138, 95, 0.12), inset 4px 0 0 #0F8A5F;
}

.list-card__thumb {
  width: 44px;
  height: 44px;
  border-radius: 8px;
  overflow: hidden;
  background: #E2E8F0;
  flex-shrink: 0;
}

.list-card__thumb img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.list-card__body {
  flex: 1;
  min-width: 0;
}

.list-card__head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 6px;
}

.list-card__title {
  font-size: 0.88rem;
  font-weight: 600;
  color: #0F172A;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  display: block;
}

.badge-featured {
  color: #D97706;
  font-size: 0.78rem;
}

.list-card__meta {
  display: flex;
  align-items: center;
  gap: 6px;
  margin-top: 4px;
  flex-wrap: wrap;
}

.list-card__badge {
  font-size: 0.7rem;
  background: #F1F5F9;
  color: #475569;
  padding: 2px 7px;
  border-radius: 6px;
  font-weight: 500;
}

.list-card__status {
  font-size: 0.68rem;
  padding: 2px 7px;
  border-radius: 6px;
  font-weight: 600;
}

.list-card__status--active {
  background: #DCFCE7;
  color: #15803D;
}

.list-card__status--inactive {
  background: #F1F5F9;
  color: #94A3B8;
}

.form-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  margin-top: 28px;
  padding-top: 22px;
  border-top: 1px solid #F1F5F9;
  flex-wrap: wrap;
}

.form-footer__right {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-left: auto;
}

.required-star {
  color: #DC2626;
  font-weight: 700;
  margin-left: 2px;
}

/* Auto-Slug and Category preset styles */
.field-slug-wrap {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.field-slug-hint {
  font-size: 0.76rem;
  color: #64748B;
  display: inline-flex;
  align-items: center;
  gap: 5px;
}

.field-slug-hint i {
  color: #D97706;
}

.field-category-wrap {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.category-preset-chips {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
}

.cat-chip {
  background: #F1F5F9;
  color: #475569;
  border: 1px solid #E2E8F0;
  border-radius: 6px;
  padding: 3px 9px;
  font-size: 0.72rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.15s ease;
}

.cat-chip:hover {
  background: #E2E8F0;
  color: #0F172A;
}

.cat-chip.active {
  background: #08241A;
  color: #E5C378;
  border-color: #08241A;
}

/* Number field with currency prefix & live text helper */
.field-number-wrap {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.input-with-icon {
  position: relative;
  display: flex;
  align-items: center;
}

.input-prefix {
  position: absolute;
  left: 14px;
  font-size: 0.88rem;
  font-weight: 700;
  color: #64748B;
  pointer-events: none;
}

.input-with-icon input.has-prefix {
  padding-left: 44px !important;
}

.field-currency-helper {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  margin-top: 2px;
  font-size: 0.78rem;
  font-weight: 600;
  color: #0F8A5F;
}

/* Modern Toggle Switch Card */
.field-toggle-card {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  background: #F8FAFC;
  border: 1px solid #E2E8F0;
  border-radius: 12px;
  padding: 14px 18px;
  transition: all 0.2s ease;
}

.field-toggle-card:hover {
  background: #F1F5F9;
  border-color: #CBD5E1;
}

.field-toggle-info {
  display: flex;
  flex-direction: column;
  gap: 3px;
}

.field-toggle-title {
  font-size: 0.88rem;
  font-weight: 600;
  color: #0F172A;
}

.field-toggle-desc {
  font-size: 0.78rem;
  color: #64748B;
  line-height: 1.4;
}

.toggle-switch {
  position: relative;
  display: inline-block;
  width: 46px;
  height: 24px;
  flex-shrink: 0;
  cursor: pointer;
}

.toggle-switch input {
  opacity: 0;
  width: 0;
  height: 0;
  position: absolute;
}

.toggle-slider {
  position: absolute;
  inset: 0;
  background-color: #CBD5E1;
  border-radius: 24px;
  transition: 0.3s;
}

.toggle-slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  border-radius: 50%;
  transition: 0.3s;
  box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

.toggle-switch input:checked + .toggle-slider {
  background-color: #0F8A5F;
}

.toggle-switch input:checked + .toggle-slider:before {
  transform: translateX(22px);
}

.fields {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 20px 24px;
}

.field-group {
  display: flex;
  flex-direction: column;
  gap: 7px;
}

.field-group--wide {
  grid-column: 1 / -1;
}

.field-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.field-label {
  font-size: 0.78rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  font-weight: 700;
  color: #475569;
}

.field-badge-json {
  font-size: 0.7rem;
  background: #F1F5F9;
  color: #64748B;
  padding: 2px 8px;
  border-radius: 6px;
  font-family: ui-monospace, monospace;
  font-weight: 600;
}

input:not([type="checkbox"]), textarea, select {
  width: 100%;
  border: 1.5px solid #CBD5E1;
  border-radius: 9px;
  padding: 11px 14px;
  font: inherit;
  font-size: 0.92rem;
  color: #0F172A;
  background: #FFFFFF;
  transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

input:not([type="checkbox"]):focus, textarea:focus, select:focus {
  outline: none;
  border-color: #0F8A5F;
  box-shadow: 0 0 0 3px rgba(15, 138, 95, 0.12);
}

textarea {
  resize: vertical;
  min-height: 100px;
  line-height: 1.6;
}

textarea.is-json {
  font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
  font-size: 0.86rem;
  background: #F8FAFC;
  color: #0F172A;
  line-height: 1.55;
}

.field-checkbox-custom {
  display: flex;
  align-items: center;
  gap: 10px;
  cursor: pointer;
  padding: 8px 0;
}

.field-checkbox-custom input[type="checkbox"] {
  width: 18px;
  height: 18px;
  accent-color: #0F8A5F;
  cursor: pointer;
}

.checkbox-text {
  font-size: 0.88rem;
  color: #334155;
  font-weight: 500;
}

/* Image upload widget */
.field-image-widget {
  display: flex;
  flex-direction: column;
  gap: 12px;
  background: #F8FAFC;
  border: 1px solid #E2E8F0;
  border-radius: 12px;
  padding: 16px;
}

.image-preview-card {
  display: flex;
  align-items: center;
  gap: 16px;
  background: #FFFFFF;
  border: 1px solid #E2E8F0;
  border-radius: 10px;
  padding: 10px 14px;
}

.image-preview-img {
  width: 84px;
  height: 56px;
  object-fit: cover;
  border-radius: 6px;
  border: 1px solid #CBD5E1;
  flex-shrink: 0;
}

.image-preview-info {
  display: flex;
  flex-direction: column;
  gap: 4px;
  min-width: 0;
  flex: 1;
}

.image-preview-url {
  font-size: 0.78rem;
  color: #64748B;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.btn-remove-image {
  border: 0;
  background: transparent;
  color: #EF4444;
  font-size: 0.78rem;
  font-weight: 600;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 0;
  width: fit-content;
}

.btn-remove-image:hover {
  text-decoration: underline;
}

.image-upload-controls {
  display: flex;
  gap: 12px;
  align-items: center;
  flex-wrap: wrap;
}

.btn-upload-picker {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 16px;
  border: 1.5px dashed #0F8A5F;
  border-radius: 9px;
  background: rgba(15, 138, 95, 0.05);
  color: #0F8A5F;
  font-size: 0.85rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  flex-shrink: 0;
}

.btn-upload-picker:hover {
  background: rgba(15, 138, 95, 0.1);
}

.image-url-wrap {
  position: relative;
  flex: 1;
  min-width: 240px;
}

.image-url-wrap i {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #94A3B8;
  font-size: 0.85rem;
}

.image-url-wrap input {
  padding-left: 36px !important;
}

/* Dedicated Google Maps Widget */
.field-map-widget {
  display: flex;
  flex-direction: column;
  gap: 14px;
}

.field-map-input-wrap {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.field-map-input-bar {
  display: flex;
  align-items: center;
  gap: 10px;
  background: #ffffff;
  border: 1.5px solid #CBD5E1;
  border-radius: 12px;
  padding: 0 14px;
  transition: all 0.2s ease;
}

.field-map-input-bar:focus-within {
  border-color: #0F8A5F;
  box-shadow: 0 0 0 3px rgba(15, 138, 95, 0.12);
}

.field-map-icon {
  color: #0F8A5F;
  font-size: 1.05rem;
  flex-shrink: 0;
}

.field-map-input {
  flex: 1;
  border: none !important;
  background: transparent !important;
  padding: 12px 0 !important;
  font-size: 0.92rem;
  box-shadow: none !important;
  outline: none !important;
}

.btn-clear-map {
  background: none;
  border: none;
  color: #94A3B8;
  cursor: pointer;
  padding: 6px;
  font-size: 0.95rem;
  border-radius: 6px;
  transition: all 0.15s ease;
}

.btn-clear-map:hover {
  color: #DC2626;
  background: #FEE2E2;
}

.field-map-hint {
  display: flex;
  align-items: flex-start;
  gap: 8px;
  font-size: 0.8rem;
  color: #64748B;
  line-height: 1.45;
  padding: 0 2px;
}

.field-map-hint i {
  color: #D97706;
  margin-top: 2px;
  flex-shrink: 0;
}

.field-map-card {
  background: #F8FAFC;
  border: 1px solid #E2E8F0;
  border-radius: 16px;
  padding: 16px;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.field-map-card-head {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.field-map-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: 0.82rem;
  font-weight: 700;
  color: #0F8A5F;
  background: rgba(15, 138, 95, 0.08);
  padding: 4px 12px;
  border-radius: 9999px;
}

.pulse-dot {
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background: #0F8A5F;
  box-shadow: 0 0 0 3px rgba(15, 138, 95, 0.25);
}

.field-map-ext-link {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 0.82rem;
  font-weight: 600;
  color: #0F8A5F;
  text-decoration: none;
  transition: color 0.15s ease;
}

.field-map-ext-link:hover {
  text-decoration: underline;
  color: #08241A;
}

.field-map-iframe-container {
  width: 100%;
  height: 380px;
  border-radius: 12px;
  overflow: hidden;
  border: 1px solid #E2E8F0;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.field-map-iframe {
  width: 100%;
  height: 100%;
  border: 0;
  background: #EDF2EE;
}

.toolbar {
  display: flex;
  gap: 12px;
  margin-bottom: 18px;
  padding: 14px 18px;
  border: 1px solid rgba(15,138,95,0.08);
  border-radius: 20px;
  background: rgba(255,255,255,0.75);
  box-shadow: var(--shadow-xs);
  flex-wrap: wrap;
}

.toolbar__search {
  position: relative;
  flex: 1;
  min-width: 200px;
}

.toolbar__search i {
  position: absolute;
  left: 14px;
  top: 50%;
  transform: translateY(-50%);
  color: #8a978f;
}

.toolbar__search input {
  padding-left: 40px;
  max-width: 100%;
}

.toolbar input, .toolbar select {
  max-width: 280px;
}

.pagination {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  margin-top: 18px;
  color: #65746b;
  flex-wrap: wrap;
  font-size: 0.9rem;
}

.pagination--list {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: space-between;
  gap: 8px;
  margin-top: 14px;
  padding-top: 14px;
  border-top: 1px solid #F1F5F9;
}

.pagination--list button {
  padding: 6px 12px;
  border-radius: 8px;
  font-size: 0.78rem;
}

.pagination--list span {
  font-size: 0.8rem;
  font-weight: 700;
  color: #475569;
}

.pagination__controls {
  display: flex;
  align-items: center;
  gap: 10px;
}

.pagination button {
  background: #eef7f1;
  color: #1d5b3d;
  padding: 8px 14px;
  border: 0;
  border-radius: 10px;
  font-weight: 600;
  font-size: 0.82rem;
  cursor: pointer;
  transition: all 0.2s ease;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.pagination button:hover:not(:disabled) {
  background: #d6efe0;
}

.pagination button:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

.message {
  display: grid;
  grid-template-columns: 1fr auto;
  gap: 18px;
  padding: 18px 20px;
  border: 1px solid #edf2ee;
  border-radius: 18px;
  background: linear-gradient(180deg, #fff, #fbfdfb);
  transition: background 0.2s ease;
}

.message:hover {
  background: #f8fbf9;
}

.message + .message {
  margin-top: 10px;
}

.message__head {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 6px;
}

.message__head strong {
  font-size: 0.95rem;
}

.message__status {
  font-size: 0.65rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  padding: 2px 10px;
  border-radius: var(--radius-full);
}

.message__status--unread {
  background: rgba(220, 38, 38, 0.08);
  color: var(--color-error);
}

.message__status--read {
  background: rgba(15, 138, 95, 0.08);
  color: var(--color-secondary);
}

.message small {
  display: block;
  color: #65746b;
  margin: 4px 0 8px;
  font-size: 0.82rem;
}

.message h3 {
  font-size: 1rem;
  margin-bottom: 4px;
}

.message p {
  color: #526158;
  font-size: 0.9rem;
  line-height: 1.6;
}

.message__actions {
  display: flex;
  flex-direction: column;
  gap: 6px;
  align-items: flex-end;
}

.form-footer {
  margin-top: 28px;
  padding-top: 20px;
  border-top: 1px solid #EDF2EE;
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: 12px;
}

.skeleton {
  position: relative;
  overflow: hidden;
  background: #edf3ee;
  border-radius: 14px;
  min-height: 18px;
}

.skeleton::after {
  content: '';
  position: absolute;
  inset: 0;
  transform: translateX(-100%);
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.72), transparent);
  animation: shimmer 1.25s infinite;
}

.skeleton--title {
  width: 42%;
  height: 24px;
  margin-bottom: 22px;
}

.skeleton--row {
  height: 54px;
  margin-top: 10px;
}

.skeleton--input {
  height: 50px;
}

/* Visual Repeaters & Dynamic Field Styling */
.field-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 8px;
}

.field-counter-badge {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 3px 9px;
  background: #f1f5f9;
  color: #475569;
  border-radius: 9999px;
  font-size: 0.72rem;
  font-weight: 700;
  letter-spacing: 0.02em;
  border: 1px solid #e2e8f0;
}

.field-badge-json {
  display: inline-flex;
  align-items: center;
  padding: 3px 9px;
  background: #fef2f2;
  color: #991b1b;
  border-radius: 9999px;
  font-size: 0.72rem;
  font-weight: 700;
  border: 1px solid #fecaca;
}

.field-empty-state {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 16px 18px;
  background: #f8fafc;
  border: 1px dashed #cbd5e1;
  border-radius: 10px;
  color: #64748b;
  font-size: 0.85rem;
}

.field-empty-state i {
  color: #94a3b8;
  font-size: 1rem;
}

/* 1. List Repeater */
.field-list-card {
  display: flex;
  flex-direction: column;
  gap: 10px;
  background: #f8fafc;
  padding: 16px;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
}

.field-list-row {
  display: flex;
  align-items: center;
  gap: 10px;
  background: #ffffff;
  padding: 6px 10px;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  transition: border-color 0.2s, box-shadow 0.2s;
}

.field-list-row:focus-within {
  border-color: #08241A;
  box-shadow: 0 0 0 3px rgba(8, 36, 26, 0.08);
}

.field-list-num {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background: #08241A;
  color: #ffffff;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 0.78rem;
  font-weight: 700;
  flex-shrink: 0;
}

.field-list-input {
  flex: 1;
  border: none !important;
  background: transparent !important;
  padding: 8px 6px !important;
  font-size: 0.9rem !important;
  color: #0f172a !important;
  box-shadow: none !important;
  outline: none !important;
}

.field-list-input:focus {
  outline: none !important;
  box-shadow: none !important;
}

.btn-remove-row {
  background: transparent;
  border: none;
  color: #94a3b8;
  width: 32px;
  height: 32px;
  border-radius: 8px;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 0.88rem;
  transition: all 0.2s;
  flex-shrink: 0;
}

.btn-remove-row:hover {
  background: #fee2e2;
  color: #dc2626;
}

.btn-add-dashed {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 10px 16px;
  background: #ffffff;
  border: 1.5px dashed #cbd5e1;
  border-radius: 10px;
  color: #08241A;
  font-size: 0.85rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  width: 100%;
}

.btn-add-dashed:hover {
  background: #f0fdf4;
  border-color: #08241A;
  color: #08241A;
}

/* 2. Tags / Nilai Core Values */
.field-tags-card {
  display: flex;
  flex-direction: column;
  gap: 12px;
  background: #f8fafc;
  padding: 16px;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
}

.field-tags-list {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  align-items: center;
  min-height: 36px;
}

.tag-pill {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 6px 12px;
  background: #08241A;
  color: #ffffff;
  border-radius: 9999px;
  font-size: 0.82rem;
  font-weight: 600;
  letter-spacing: 0.01em;
  box-shadow: 0 1px 3px rgba(0,0,0,0.08);
  animation: fadeInTag 0.2s ease-out;
}

.tag-pill i.fa-check {
  font-size: 0.72rem;
  color: #E5C378;
}

.btn-remove-tag {
  background: rgba(255, 255, 255, 0.18);
  border: none;
  color: #ffffff;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 0.65rem;
  transition: all 0.2s;
}

.btn-remove-tag:hover {
  background: #dc2626;
  color: #ffffff;
}

.tag-empty-hint {
  font-size: 0.82rem;
  color: #64748b;
  font-style: italic;
}

.tag-input-group {
  display: flex;
  gap: 10px;
}

.tag-text-input {
  flex: 1;
  background: #ffffff !important;
  border: 1px solid #cbd5e1 !important;
  border-radius: 10px !important;
  padding: 9px 14px !important;
  font-size: 0.88rem !important;
}

.tag-text-input:focus {
  border-color: #08241A !important;
  box-shadow: 0 0 0 3px rgba(8, 36, 26, 0.08) !important;
}

.btn-add-tag-action {
  background: #08241A;
  color: #E5C378;
  border: none;
  border-radius: 10px;
  padding: 0 18px;
  font-size: 0.85rem;
  font-weight: 600;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  transition: all 0.2s;
  white-space: nowrap;
}

.btn-add-tag-action:hover {
  background: #0d3829;
  color: #ffffff;
}

@keyframes fadeInTag {
  from { opacity: 0; transform: scale(0.92); }
  to { opacity: 1; transform: scale(1); }
}

/* 3. Amounts Repeater */
.field-amounts-card {
  display: flex;
  flex-direction: column;
  gap: 12px;
  background: #f8fafc;
  padding: 16px;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
}

.amounts-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 12px;
}

.amount-item {
  display: flex;
  align-items: center;
  gap: 8px;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  padding: 8px 10px;
  transition: all 0.2s;
}

.amount-item:focus-within {
  border-color: #08241A;
  box-shadow: 0 0 0 3px rgba(8, 36, 26, 0.08);
}

.amount-input-box {
  display: flex;
  align-items: center;
  gap: 4px;
  flex: 1;
  min-width: 0;
}

.amount-curr {
  font-size: 0.82rem;
  font-weight: 700;
  color: #64748b;
}

.amount-field {
  width: 100%;
  border: none !important;
  background: transparent !important;
  padding: 4px !important;
  font-size: 0.9rem !important;
  font-weight: 700 !important;
  color: #08241A !important;
  outline: none !important;
  box-shadow: none !important;
}

.amount-badge {
  display: none;
}

.btn-remove-amt {
  background: transparent;
  border: none;
  color: #94a3b8;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  transition: all 0.2s;
  flex-shrink: 0;
}

.btn-remove-amt:hover {
  background: #fee2e2;
  color: #dc2626;
}

/* 4 & 5. Links & Organization Cards */
.field-links-card {
  display: flex;
  flex-direction: column;
  gap: 12px;
  background: #f8fafc;
  padding: 16px;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
}

.link-row-card {
  display: grid;
  grid-template-columns: 1fr 1fr auto;
  gap: 12px;
  align-items: flex-end;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  padding: 12px;
  transition: all 0.2s;
}

.link-row-card:focus-within {
  border-color: #08241A;
  box-shadow: 0 0 0 3px rgba(8, 36, 26, 0.08);
}

.link-field-cell {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.link-sublabel {
  font-size: 0.72rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.04em;
  color: #64748b;
}

.link-input {
  background: #f8fafc !important;
  border: 1px solid #e2e8f0 !important;
  border-radius: 8px !important;
  padding: 7px 10px !important;
  font-size: 0.85rem !important;
}

.link-input:focus {
  background: #ffffff !important;
  border-color: #08241A !important;
  box-shadow: 0 0 0 2px rgba(8, 36, 26, 0.08) !important;
}

@media (max-width: 980px) {
  .link-row-card {
    grid-template-columns: 1fr;
  }

  .cms-grid { grid-template-columns: 1fr; }
  .fields { grid-template-columns: 1fr; }
  .message { grid-template-columns: 1fr; }
  .message__actions { flex-direction: row; }
  .toolbar { flex-direction: column; }
  .toolbar input, .toolbar select { max-width: none; }
  .panel { padding: 18px; }
  .panel-head { align-items: flex-start; flex-direction: column; }
  .pagination { flex-direction: column; align-items: flex-start; }
}
</style>
