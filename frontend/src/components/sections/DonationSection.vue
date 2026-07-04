<script setup>
import { ref, computed } from 'vue'
import ProgressBar from '../common/ProgressBar.vue'

const amounts = [10000, 25000, 50000, 100000, 500000]
const selectedAmount = ref(50000)
const customAmount = ref('')
const method = ref('qris')
const form = ref({ name: '', email: '', phone: '' })
const submitted = ref(false)

const finalAmount = computed(() => customAmount.value ? Number(customAmount.value) : selectedAmount.value)

function pick(a) {
  selectedAmount.value = a
  customAmount.value = ''
}

function formatRp(n) {
  return 'Rp' + Number(n || 0).toLocaleString('id-ID')
}

function submit() {
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
          <strong>Bantuan Ramadan 2026</strong>
        </div>
        <ProgressBar :current="850" :target="1200" color="primary" />
        <div class="donate__campaign-figures">
          <span>Rp850Jt terkumpul</span>
          <span>Target Rp1.200Jt</span>
        </div>
      </div>

      <h3 class="donate__step-title">1. Pilih Nominal</h3>
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
      />

      <h3 class="donate__step-title">2. Data Donatur</h3>
      <div class="donate__fields">
        <input v-model="form.name" type="text" placeholder="Nama Lengkap" class="donate__input" />
        <input v-model="form.email" type="email" placeholder="Alamat Email" class="donate__input" />
        <input v-model="form.phone" type="tel" placeholder="Nomor WhatsApp" class="donate__input" />
      </div>

      <h3 class="donate__step-title">3. Metode Pembayaran</h3>
      <div class="donate__methods">
        <button class="donate__method" :class="{ 'donate__method--active': method === 'qris' }" @click="method = 'qris'">
          <i class="fa-solid fa-qrcode"></i> QRIS
        </button>
        <button class="donate__method" :class="{ 'donate__method--active': method === 'transfer' }" @click="method = 'transfer'">
          <i class="fa-solid fa-building-columns"></i> Transfer Bank
        </button>
        <button class="donate__method" :class="{ 'donate__method--active': method === 'ewallet' }" @click="method = 'ewallet'">
          <i class="fa-solid fa-wallet"></i> E-Wallet
        </button>
      </div>

      <button class="btn btn-primary donate__submit" @click="submit">
        Lanjutkan Donasi {{ formatRp(finalAmount) }} <i class="fa-solid fa-heart"></i>
      </button>

      <transition name="fade">
        <p v-if="submitted" class="donate__success">
          <i class="fa-solid fa-circle-check"></i> Terima kasih! Instruksi pembayaran telah dikirim ke email Anda.
        </p>
      </transition>
    </div>
  </div>
</template>

<style scoped>
.donate { max-width: 620px; margin: 0 auto; }
.donate__panel { padding: 32px; }
.donate__campaign {
  background: var(--color-accent);
  border-radius: var(--radius-sm);
  padding: 18px 20px;
  margin-bottom: 30px;
}
.donate__campaign-head { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 0.85rem; color: var(--color-text-muted); }
.donate__campaign-head strong { display: block; font-size: 1.05rem; color: var(--color-text); margin-top: 2px; }
.donate__campaign-figures { display: flex; justify-content: space-between; margin-top: 8px; font-size: 0.78rem; color: var(--color-text-muted); }

.donate__step-title { font-size: 0.95rem; margin: 26px 0 14px; }
.donate__amounts { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
.donate__amount {
  padding: 12px;
  border: 1.5px solid var(--color-border);
  border-radius: var(--radius-sm);
  font-weight: 600;
  font-size: 0.88rem;
  transition: all 0.2s ease;
}
.donate__amount:hover { border-color: var(--color-primary); }
.donate__amount--active { background: var(--color-primary); border-color: var(--color-primary); color: #2a2205; }

.donate__input {
  width: 100%;
  padding: 13px 16px;
  border: 1.5px solid var(--color-border);
  border-radius: var(--radius-sm);
  font-size: 0.92rem;
  margin-top: 12px;
  font-family: inherit;
}
.donate__input:focus { outline: none; border-color: var(--color-secondary); }
.donate__fields { display: flex; flex-direction: column; }

.donate__methods { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
.donate__method {
  padding: 14px 8px;
  border: 1.5px solid var(--color-border);
  border-radius: var(--radius-sm);
  font-size: 0.82rem;
  font-weight: 600;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  color: var(--color-text-muted);
  transition: all 0.2s ease;
}
.donate__method i { font-size: 1.2rem; }
.donate__method--active { border-color: var(--color-secondary); color: var(--color-secondary); background: rgba(46,125,50,0.06); }

.donate__submit { width: 100%; margin-top: 28px; font-size: 1rem; padding: 16px; }
.donate__success {
  margin-top: 16px;
  color: var(--color-secondary-dark);
  background: rgba(46,125,50,0.08);
  padding: 12px 16px;
  border-radius: var(--radius-sm);
  font-size: 0.88rem;
  display: flex;
  align-items: center;
  gap: 8px;
}
.fade-enter-active { transition: opacity 0.3s ease; }
.fade-enter-from { opacity: 0; }

@media (max-width: 520px) {
  .donate__amounts { grid-template-columns: repeat(2, 1fr); }
  .donate__methods { grid-template-columns: 1fr; }
}
</style>
