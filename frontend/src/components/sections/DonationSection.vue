<script setup>
import { ref, computed, onMounted } from 'vue'
import ProgressBar from '../common/ProgressBar.vue'
import { publicService } from '@/services/api/client'

const settings = ref(null)
const amounts = computed(() => settings.value?.suggested_amounts?.length ? settings.value.suggested_amounts : [10000, 25000, 50000, 100000, 500000])
const selectedAmount = ref(50000)
const customAmount = ref('')
const method = ref('qris')
const form = ref({ name: '', email: '', phone: '' })
const submitted = ref(false)
const copied = ref(false)
const step = ref(1)

const finalAmount = computed(() => customAmount.value ? Number(customAmount.value) : selectedAmount.value)

async function copyAccountNumber() {
  const acc = settings.value?.bank_account_number || '1234567890'
  try {
    await navigator.clipboard.writeText(acc)
    copied.value = true
    setTimeout(() => { copied.value = false }, 2000)
  } catch {
    // Clipboard fallback
    copied.value = true
    setTimeout(() => { copied.value = false }, 2000)
  }
}

onMounted(async () => {
  try {
    const response = await publicService.donationSettings()
    settings.value = response.data
    if (settings.value?.suggested_amounts?.length) {
      selectedAmount.value = settings.value.suggested_amounts[0]
    }
  } catch {
    settings.value = null
  }
})

function pick(a) {
  selectedAmount.value = a
  customAmount.value = ''
  step.value = 2
}

function formatRp(n) {
  return 'Rp' + Number(n || 0).toLocaleString('id-ID')
}

function submit() {
  const rawPhone = settings.value?.whatsapp_number || '6281234567890'
  const phone = rawPhone.replace(/\D/g, '')
  const donorName = form.value.name?.trim() || 'Hamba Allah'
  const methodLabel = method.value === 'qris' ? 'QRIS' : method.value === 'transfer' ? 'Transfer Bank' : 'E-Wallet'

  let msg = `*Konfirmasi Donasi Yayasan GSU*\n`
  msg += `--------------------------------\n`
  msg += `Nama Donatur : ${donorName}\n`
  if (form.value.phone?.trim()) msg += `No. WhatsApp : ${form.value.phone.trim()}\n`
  if (form.value.email?.trim()) msg += `Email        : ${form.value.email.trim()}\n`
  msg += `Nominal      : ${formatRp(finalAmount.value)}\n`
  msg += `Metode Bayar : ${methodLabel}\n`
  msg += `Program      : ${settings.value?.title || 'Sedekah Umum'}\n`
  msg += `--------------------------------\n`
  msg += `Mohon konfirmasi dan info penyaluran. Terima kasih.`

  const encoded = encodeURIComponent(msg)
  if (phone) {
    window.open(`https://wa.me/${phone}?text=${encoded}`, '_blank')
  }
  submitted.value = true
  setTimeout(() => (submitted.value = false), 3500)
}
</script>

<template>
  <div class="donate">
    <div class="donate__panel card">
      <div class="donate__campaign">
        <div class="donate__campaign-head">
          <span>Kampanye Aktif</span>
          <strong>{{ settings?.title || 'Bantuan Ramadan 2026' }}</strong>
        </div>
        <ProgressBar :current="850" :target="1200" color="primary" />
        <div class="donate__campaign-figures">
          <span><strong>Rp850Jt</strong> terkumpul</span>
          <span>Target <strong>Rp1.200Jt</strong></span>
        </div>
      </div>

      <div class="donate__steps">
        <div class="donate__step" :class="{ 'donate__step--active': step >= 1, 'donate__step--done': step > 1 }">
          <div class="donate__step-num">1</div>
          <span>Nominal</span>
        </div>
        <div class="donate__step-line"></div>
        <div class="donate__step" :class="{ 'donate__step--active': step >= 2, 'donate__step--done': step > 2 }">
          <div class="donate__step-num">2</div>
          <span>Data</span>
        </div>
        <div class="donate__step-line"></div>
        <div class="donate__step" :class="{ 'donate__step--active': step >= 3, 'donate__step--done': step > 3 }">
          <div class="donate__step-num">3</div>
          <span>Bayar</span>
        </div>
      </div>

      <div v-show="step >= 1">
        <h3 class="donate__step-title">Pilih Nominal</h3>
        <div class="donate__amounts">
          <button
            v-for="a in amounts"
            :key="a"
            class="donate__amount"
            :class="{ 'donate__amount--active': selectedAmount === a && !customAmount }"
            @click="pick(a)"
          >
            {{ formatRp(a) }}
          </button>
        </div>
        <input
          v-model="customAmount"
          type="number"
          placeholder="Atau masukkan nominal lain"
          class="donate__input"
          @focus="step = 2"
        />
      </div>

      <div v-show="step >= 2">
        <h3 class="donate__step-title">Data Donatur</h3>
        <div class="donate__fields">
          <input v-model="form.name" type="text" placeholder="Nama Lengkap" class="donate__input" @focus="step = 2" />
          <input v-model="form.email" type="email" placeholder="Alamat Email" class="donate__input" @focus="step = 2" />
          <input v-model="form.phone" type="tel" placeholder="Nomor WhatsApp" class="donate__input" @focus="step = 2" />
        </div>
      </div>

      <div v-show="step >= 3">
        <h3 class="donate__step-title">Metode Pembayaran</h3>

        <!-- Pilihan Metode -->
        <div class="donate__methods">
          <button
            type="button"
            class="donate__method"
            :class="{ 'donate__method--active': method === 'qris' }"
            @click="method = 'qris'"
          >
            <i class="fa-solid fa-qrcode"></i> QRIS
          </button>
          <button
            type="button"
            class="donate__method"
            :class="{ 'donate__method--active': method === 'transfer' }"
            @click="method = 'transfer'"
          >
            <i class="fa-solid fa-building-columns"></i> Transfer Bank
          </button>
          <button
            type="button"
            class="donate__method"
            :class="{ 'donate__method--active': method === 'ewallet' }"
            @click="method = 'ewallet'"
          >
            <i class="fa-solid fa-wallet"></i> E-Wallet
          </button>
        </div>

        <!-- Konten Metode QRIS -->
        <div v-if="method === 'qris'" class="donate__method-content donate__qris-box">
          <div v-if="settings?.qris_image" class="donate__qris-img-wrap">
            <img :src="settings.qris_image" alt="QRIS Yayasan Gerakan Sedekah Umat" class="donate__qris-img" />
          </div>
          <div v-else class="donate__qris-placeholder">
            <div class="donate__qris-placeholder-icon"><i class="fa-solid fa-qrcode"></i></div>
            <p>Scan barcode QRIS dengan aplikasi m-Banking atau e-Wallet pilihan Anda.</p>
          </div>
          <div class="donate__qris-hint">
            <i class="fa-solid fa-shield-halved"></i> Terverifikasi atas nama: <strong>{{ settings?.bank_account_holder || 'Yayasan Gerakan Sedekah Umat' }}</strong>
          </div>
        </div>

        <!-- Konten Metode Transfer Bank -->
        <div v-else-if="method === 'transfer'" class="donate__method-content donate__bank">
          <div class="donate__bank-top">
            <div class="donate__bank-icon"><i class="fa-solid fa-building-columns"></i></div>
            <div class="donate__bank-info">
              <span class="donate__bank-name">{{ settings?.bank_name || 'Bank Syariah Indonesia' }}</span>
              <strong class="donate__bank-acc">{{ settings?.bank_account_number || '1234567890' }}</strong>
              <span class="donate__bank-holder">a.n. {{ settings?.bank_account_holder || 'Yayasan Gerakan Sedekah Umat' }}</span>
            </div>
            <button
              type="button"
              class="donate__copy-btn"
              :class="{ 'donate__copy-btn--copied': copied }"
              @click="copyAccountNumber"
              :title="copied ? 'Tersalin' : 'Salin Nomor Rekening'"
            >
              <i class="fa-solid" :class="copied ? 'fa-check' : 'fa-copy'"></i>
              <span>{{ copied ? 'Tersalin!' : 'Salin' }}</span>
            </button>
          </div>
          <p v-if="settings?.payment_instruction" class="donate__bank-instruct">
            <i class="fa-solid fa-circle-info"></i> {{ settings.payment_instruction }}
          </p>
        </div>

        <!-- Konten Metode E-Wallet -->
        <div v-else-if="method === 'ewallet'" class="donate__method-content donate__ewallet-box">
          <div class="donate__ewallet-grid">
            <span class="ewallet-pill"><i class="fa-solid fa-wallet"></i> GoPay</span>
            <span class="ewallet-pill"><i class="fa-solid fa-wallet"></i> OVO</span>
            <span class="ewallet-pill"><i class="fa-solid fa-wallet"></i> DANA</span>
            <span class="ewallet-pill"><i class="fa-solid fa-wallet"></i> ShopeePay</span>
          </div>
          <p class="donate__ewallet-hint">
            Donasi e-wallet dapat dibayarkan melalui QRIS di tab QRIS atau konfirmasi langsung melalui layanan WhatsApp resmi kami.
          </p>
        </div>
      </div>

      <button class="btn btn-primary donate__submit" @click="submit">
        <i class="fa-solid fa-heart"></i> Donasi {{ formatRp(finalAmount) }}
      </button>

      <transition name="fade">
        <p v-if="submitted" class="donate__success">
          <i class="fa-solid fa-circle-check"></i> Terima kasih! Silakan lanjutkan konfirmasi melalui WhatsApp.
        </p>
      </transition>
    </div>
  </div>
</template>

<style scoped>
.donate { max-width: 620px; margin: 0 auto; }
.donate__panel { padding: 36px; background: var(--color-surface); border: 1px solid var(--color-border); border-radius: var(--radius-md); box-shadow: var(--shadow-sm); }

.donate__campaign {
  background: var(--color-accent);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-sm);
  padding: 20px 22px;
  margin-bottom: 28px;
}

.donate__campaign-head {
  display: flex;
  justify-content: space-between;
  margin-bottom: 12px;
  font-size: 0.85rem;
  color: var(--color-text-muted);
}

.donate__campaign-head strong {
  display: block;
  font-size: 1.05rem;
  color: var(--color-text);
  margin-top: 2px;
}

.donate__campaign-figures {
  display: flex;
  justify-content: space-between;
  margin-top: 8px;
  font-size: 0.8rem;
  color: var(--color-text-muted);
}

.donate__campaign-figures strong {
  color: var(--color-text);
}

.donate__steps {
  display: flex;
  align-items: center;
  gap: 0;
  margin-bottom: 28px;
}

.donate__step {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 6px;
  flex: 1;
}

.donate__step-num {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.8rem;
  font-weight: 700;
  background: var(--color-accent);
  border: 1px solid var(--color-border);
  color: var(--color-text-muted);
  transition: all 0.2s ease;
}

.donate__step--active .donate__step-num {
  background: var(--color-secondary);
  border-color: var(--color-secondary);
  color: #fff;
}

.donate__step--done .donate__step-num {
  background: var(--color-secondary);
  border-color: var(--color-secondary);
  color: #fff;
}

.donate__step span {
  font-size: 0.72rem;
  font-weight: 600;
  color: var(--color-text-muted);
  text-transform: uppercase;
  letter-spacing: 0.04em;
}

.donate__step--active span {
  color: var(--color-secondary);
}

.donate__step-line {
  flex: 0 0 24px;
  height: 1px;
  background: var(--color-border);
  margin: 0 4px;
  margin-bottom: 22px;
}

.donate__step-title {
  font-size: 0.95rem;
  margin: 24px 0 14px;
  color: var(--color-text);
}

.donate__amounts {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 10px;
}

.donate__amount {
  padding: 13px 12px;
  border: 1px solid var(--color-border);
  border-radius: var(--radius-sm);
  font-weight: 600;
  font-size: 0.9rem;
  transition: all 0.2s ease;
  background: #fff;
  color: var(--color-text);
}

.donate__amount:hover {
  border-color: var(--color-secondary);
  color: var(--color-secondary);
  transform: translateY(-2px);
  box-shadow: var(--shadow-xs);
}

.donate__amount--active {
  background: var(--color-primary);
  border-color: var(--color-primary-dark);
  color: #FFFFFF;
  box-shadow: var(--shadow-xs);
}

.donate__input {
  width: 100%;
  padding: 14px 18px;
  border: 1.5px solid var(--color-border);
  border-radius: var(--radius-sm);
  font-size: 0.92rem;
  margin-top: 12px;
  background: #fff;
  transition: all 0.25s ease;
}

.donate__input:focus {
  outline: none;
  border-color: var(--color-secondary);
  box-shadow: 0 0 0 4px rgba(15, 138, 95, 0.08);
}

.donate__fields {
  display: flex;
  flex-direction: column;
}

.donate__methods {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 10px;
  margin-bottom: 18px;
}

.donate__method-content {
  margin-top: 14px;
}

/* QRIS Box */
.donate__qris-box {
  background: #FFFFFF;
  border: 1.5px solid var(--color-border);
  border-radius: var(--radius-md);
  padding: 24px;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 14px;
  box-shadow: 0 4px 16px -2px rgba(8, 36, 26, 0.05);
}

.donate__qris-img-wrap {
  background: #fff;
  padding: 12px;
  border: 1px solid var(--color-border);
  border-radius: var(--radius-sm);
  max-width: 240px;
  box-shadow: var(--shadow-sm);
}

.donate__qris-img {
  width: 100%;
  height: auto;
  display: block;
  border-radius: 4px;
}

.donate__qris-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
  padding: 18px;
  color: var(--color-text-muted);
}

.donate__qris-placeholder-icon {
  width: 64px;
  height: 64px;
  border-radius: 16px;
  background: rgba(15, 138, 95, 0.08);
  color: var(--color-secondary);
  font-size: 2rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.donate__qris-placeholder p {
  max-width: 380px;
  font-size: 0.9rem;
  line-height: 1.6;
  margin: 0;
}

.donate__qris-hint {
  font-size: 0.84rem;
  color: var(--color-text-muted);
  display: flex;
  align-items: center;
  gap: 6px;
}

.donate__qris-hint i {
  color: var(--color-secondary);
}

/* Bank Box */
.donate__bank {
  background: rgba(15, 138, 95, 0.04);
  border: 1.5px solid rgba(15, 138, 95, 0.16);
  border-radius: var(--radius-md);
  padding: 20px;
}

.donate__bank-top {
  display: flex;
  align-items: center;
  gap: 16px;
}

.donate__bank-icon {
  width: 48px;
  height: 48px;
  border-radius: 14px;
  background: var(--color-secondary);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  flex-shrink: 0;
}

.donate__bank-info {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.donate__bank-name {
  font-size: 0.8rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: var(--color-text-muted);
  font-weight: 700;
}

.donate__bank-acc {
  font-family: var(--font-heading);
  font-size: 1.28rem;
  color: var(--color-text);
  letter-spacing: 0.03em;
}

.donate__bank-holder {
  font-size: 0.85rem;
  color: var(--color-text-muted);
}

.donate__copy-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 14px;
  border: 1px solid var(--color-secondary);
  border-radius: var(--radius-full);
  background: #FFFFFF;
  color: var(--color-secondary);
  font-size: 0.82rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s ease;
  flex-shrink: 0;
}

.donate__copy-btn:hover {
  background: var(--color-secondary);
  color: #FFFFFF;
  transform: translateY(-1px);
}

.donate__copy-btn--copied {
  background: var(--color-secondary);
  color: #FFFFFF;
  border-color: var(--color-secondary);
}

.donate__bank-instruct {
  margin-top: 14px;
  padding-top: 12px;
  border-top: 1px dashed rgba(15, 138, 95, 0.2);
  color: var(--color-text-muted);
  font-size: 0.85rem;
  line-height: 1.5;
  display: flex;
  align-items: center;
  gap: 6px;
}

.donate__bank-instruct i {
  color: var(--color-secondary);
}

/* E-Wallet Box */
.donate__ewallet-box {
  background: #FFFFFF;
  border: 1.5px solid var(--color-border);
  border-radius: var(--radius-md);
  padding: 20px;
}

.donate__ewallet-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 12px;
}

.ewallet-pill {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 16px;
  background: var(--color-accent);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-full);
  font-size: 0.85rem;
  font-weight: 600;
  color: var(--color-text);
}

.ewallet-pill i {
  color: var(--color-secondary);
}

.donate__ewallet-hint {
  color: var(--color-text-muted);
  font-size: 0.88rem;
  line-height: 1.6;
  margin: 0;
}

.donate__submit {
  width: 100%;
  margin-top: 28px;
  font-size: 1rem;
  padding: 16px;
}

.donate__success {
  margin-top: 16px;
  color: var(--color-secondary-dark);
  background: rgba(15, 138, 95, 0.08);
  padding: 14px 18px;
  border-radius: var(--radius-sm);
  font-size: 0.88rem;
  display: flex;
  align-items: center;
  gap: 8px;
}

.fade-enter-active { transition: opacity 0.3s ease; }
.fade-enter-from { opacity: 0; }

@media (max-width: 520px) {
  .donate__panel { padding: 24px; }
  .donate__amounts { grid-template-columns: repeat(2, 1fr); }
  .donate__methods { grid-template-columns: 1fr; }
}
</style>
