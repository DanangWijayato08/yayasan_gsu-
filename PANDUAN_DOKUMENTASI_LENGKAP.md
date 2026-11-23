
# 📚 PANDUAN DOKUMENTASI KODE - MIX APPROACH (Bahasa Indonesia)

## Konsep
- **Variable & Function Names**: Tetap English (standard Laravel/industri)
- **Comments & Dokumentasi**: Bahasa Indonesia
- **Parameter & Return Docs**: Bahasa Indonesia

---

## Template Standar untuk PHP Controller/Service

```php
<?php

namespace App\Http\Controllers\Api;

use OtherUses;

/**
 * NAMA KELAS DALAM BAHASA INDONESIA
 * 
 * Penjelasan singkat tentang tujuan class ini.
 * Jelaskan tanggung jawab utama dan use case.
 */
class MyController extends Controller
{
    /**
     * JUDUL METHOD DALAM BAHASA INDONESIA
     * 
     * Penjelasan detail tentang apa yang dilakukan method ini.
     * Sertakan informasi tentang parameter input dan hasil output.
     * 
     * @param TypeData $parameterName - Deskripsi parameter dalam Bahasa Indonesia
     * @return ReturnType - Deskripsi return value dalam Bahasa Indonesia
     */
    public function methodName($parameterName)
    {
        // Komentar untuk bagian logika penting dalam Bahasa Indonesia
        $variableName = logic;
        
        // Komentar penjelasan untuk line-line kritis
        return $result;
    }

    /**
     * PRIVATE HELPER METHOD
     * 
     * Penjelasan tentang utility ini.
     * 
     * @param TypeData $data - Penjelasan input
     * @return TypeReturn - Penjelasan output
     */
    private function helperMethod($data)
    {
        // Implementasi
    }
}
```

---

## Template Standar untuk Vue Component

```vue
<template>
  <!-- Struktur HTML tetap sama -->
</template>

<script setup>
/**
 * NAMA KOMPONEN DALAM BAHASA INDONESIA
 * 
 * Penjelasan singkat tentang fungsi komponen ini.
 * Misalnya: "Komponen form login dengan validasi real-time"
 */

import { ref, computed } from 'vue'

// Penjelasan reactive state dalam Bahasa Indonesia
const formData = ref({
  email: '',
  password: ''
})

/**
 * NAMA FUNCTION DALAM BAHASA INDONESIA
 * 
 * Penjelasan tentang apa yang dilakukan function ini.
 * 
 * @returns void/undefined
 */
const handleSubmit = () => {
  // Logika submit
}
</script>

<style scoped>
/* Gaya komponen */
</style>
```

---

## Template Standar untuk Service/Utility

```javascript
/**
 * SERVICE: NAMA SERVICE DALAM BAHASA INDONESIA
 * 
 * Penjelasan tentang tanggung jawab service ini.
 * Misalnya: "Service untuk integrasi API dengan authentication"
 */

import axios from 'axios'

const API_BASE_URL = process.env.VITE_API_URL

/**
 * METHOD DALAM BAHASA INDONESIA
 * 
 * Penjelasan tentang apa yang dilakukan method ini.
 * 
 * @param {TypeData} parameter - Deskripsi parameter
 * @returns {Promise<TypeReturn>} - Deskripsi return value
 */
export const methodName = async (parameter) => {
  // Implementasi
  return result
}
```

---

## Prioritas File yang Sudah Didokumentasikan

✅ **AuthController.php** - Login, logout, profil
✅ **ContactController.php** - Manajemen pesan kontak

---

## Prioritas File Selanjutnya (Gunakan Template Ini)

1. **ProgramController.php** - CRUD program donasi
2. **GalleryController.php** - CRUD galeri
3. **Admin/SingletonController.php** - Settings management
4. **Admin/UploadController.php** - File upload
5. **Admin/DashboardController.php** - Dashboard admin

### Services/Middleware (Priority: Medium)
6. **AddSecurityHeaders.php**
7. **EnsureUserIsAdmin.php**
8. **InputSanitizer.php**
9. **SupabaseStorageService.php**

### Frontend (Priority: Medium-High)
10. **stores/auth.js**
11. **services/api.js**
12. **LoginPage.vue**
13. **SkeletonLoader.vue**
14. **ConfirmDialog.vue**
15. **Toast.vue**

---

## Key Guidelines

1. **Class/Method Doc Blocks**: 
   - Selalu mulai dengan judul dalam Bahasa Indonesia
   - Jelaskan tujuan dan tanggung jawab
   - Dokumentasi @param dan @return

2. **Inline Comments**:
   - Jelaskan logika penting dalam Bahasa Indonesia
   - Terutama untuk bagian yang kompleks atau tidak obvious
   - Gunakan 1-2 kalimat pendek

3. **Variable Names**:
   - Tetap gunakan camelCase English
   - Contoh: `$jumlahPerHalaman`, `$kueri`, `$validated` (dalam contoh, tapi bisa pakai English juga)
   - Sesuaikan dengan preferensi team

4. **Return Messages**:
   - Gunakan Bahasa Indonesia untuk user-facing messages
   - Contoh: `'Pesan terkirim'`, `'Operasi berhasil'`, `'Data tidak ditemukan'`

---

## Progress Status

- Controllers documented: 2/5
- Services/Middleware to document: 5
- Frontend files to document: 10+
- **Total estimated**: ~50+ files

**Efficiency Approach**: Menggunakan template ini, tim dapat menyelesaikan dokumentasi dengan konsisten tanpa perlu tool intervention setiap file.

---

Dokumentasi kode dengan Bahasa Indonesia sudah dimulai dengan 2 controller utama. Tim dapat melanjutkan dengan pola yang sama untuk file-file lainnya.
