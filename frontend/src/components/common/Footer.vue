<script setup>
const year = new Date().getFullYear()
</script>

<template>
  <footer class="footer">
    <div class="container footer__grid">
      <div class="footer__col footer__brand">
        <div class="footer__logo">
          <span class="navbar__mark-footer"><i class="fa-solid fa-hand-holding-heart"></i></span>
          <span>Gerakan Sedekah Umat</span>
        </div>
        <p>Menerangi jalan kebaikan melalui gerakan amal yang transparan, berdampak, dan berkelanjutan bagi masyarakat.</p>
        <div class="footer__social">
          <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
        </div>
      </div>

      <div class="footer__col">
        <h4>Jelajahi</h4>
        <ul>
          <li><router-link to="/tentang">Tentang Kami</router-link></li>
          <li><router-link to="/program">Program</router-link></li>
          <li><router-link to="/galeri">Galeri</router-link></li>
        </ul>
      </div>

      <div class="footer__col">
        <h4>Terlibat</h4>
        <ul>
          <li><router-link to="/donasi">Donasi Sekarang</router-link></li>
          <li><router-link to="/kontak">Jadi Relawan</router-link></li>
          <li><router-link to="/kontak">Hubungi Kami</router-link></li>
        </ul>
      </div>

      <div class="footer__col">
        <h4>Kantor Kami</h4>
        <ul class="footer__contact">
          <li><i class="fa-solid fa-location-dot"></i> Jl. Kebajikan No. 12, Jakarta Selatan, 12340</li>
          <li><i class="fa-solid fa-envelope"></i> halo@sedekahumat.org</li>
          <li><i class="fa-solid fa-phone"></i> +62 812-3456-7890</li>
        </ul>
      </div>
    </div>

    <div class="container footer__bottom">
      <p>&copy; {{ year }} Yayasan Gerakan Sedekah Umat. Menerangi jalan kebaikan.</p>
      <div class="footer__legal">
        <a href="#">Kebijakan Privasi</a>
        <a href="#">Syarat &amp; Ketentuan</a>
      </div>
    </div>
  </footer>
</template>

<style scoped>
.footer {
  background: #1B1B1B;
  color: rgba(255,255,255,0.75);
  padding: 72px 0 0;
}
.footer__grid {
  display: grid;
  grid-template-columns: 1.6fr 1fr 1fr 1.2fr;
  gap: 40px;
  padding-bottom: 48px;
  border-bottom: 1px solid rgba(255,255,255,0.1);
}
.footer__logo {
  display: flex;
  align-items: center;
  gap: 10px;
  color: #fff;
  font-family: var(--font-heading);
  font-size: 1.1rem;
  margin-bottom: 16px;
}
.navbar__mark-footer {
  width: 34px; height: 34px;
  border-radius: 9px;
  background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
  display: flex; align-items: center; justify-content: center;
  font-size: 0.85rem;
}
.footer__brand p { font-size: 0.92rem; max-width: 320px; }
.footer__social { display: flex; gap: 12px; margin-top: 22px; }
.footer__social a {
  width: 36px; height: 36px;
  border-radius: 50%;
  background: rgba(255,255,255,0.08);
  display: flex; align-items: center; justify-content: center;
  transition: background 0.2s ease;
}
.footer__social a:hover { background: var(--color-primary); color: #1B1B1B; }

.footer__col h4 {
  color: #fff;
  font-family: var(--font-body);
  font-size: 0.9rem;
  letter-spacing: 0.04em;
  text-transform: uppercase;
  margin-bottom: 18px;
}
.footer__col ul { display: flex; flex-direction: column; gap: 12px; }
.footer__col a { font-size: 0.92rem; transition: color 0.2s ease; }
.footer__col a:hover { color: var(--color-primary); }
.footer__contact li { display: flex; gap: 10px; font-size: 0.9rem; align-items: flex-start; }
.footer__contact i { color: var(--color-primary); margin-top: 3px; }

.footer__bottom {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 22px 24px;
  font-size: 0.85rem;
  flex-wrap: wrap;
  gap: 12px;
}
.footer__legal { display: flex; gap: 20px; }

@media (max-width: 860px) {
  .footer__grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 560px) {
  .footer__grid { grid-template-columns: 1fr; }
}
</style>
