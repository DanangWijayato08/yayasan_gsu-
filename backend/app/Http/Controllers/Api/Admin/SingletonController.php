<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\AboutPage;
use App\Models\ContactSetting;
use App\Models\DonationSetting;
use App\Models\FooterSetting;
use App\Services\PublicContentCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Pengontrol Singleton Settings
 * 
 * Menangani pengaturan situs yang bersifat singleton (hanya ada 1 record).
 * Termasuk: site-settings, about, donation-settings, contact-settings, footer-settings
 */
class SingletonController extends Controller
{
    /**
     * Map tipe setting ke model yang sesuai
     */
    protected array $typeModelMap = [
        'site-settings' => SiteSetting::class,
        'about' => AboutPage::class,
        'donation-settings' => DonationSetting::class,
        'contact-settings' => ContactSetting::class,
        'footer-settings' => FooterSetting::class,
    ];

    /**
     * Ambil data setting berdasarkan tipe
     * 
     * @param Request $request - Request berisi tipe setting
     * @param string $type - Tipe setting (site-settings, about, dll)
     * @return \Illuminate\Http\JsonResponse - Data setting atau error
     */
    public function show(Request $request, string $type)
    {
        // Validasi tipe setting
        if (!isset($this->typeModelMap[$type])) {
            return response()->json([
                'message' => 'Tipe setting tidak valid',
                'valid_types' => array_keys($this->typeModelMap)
            ], 422);
        }

        try {
            // Ambil model class berdasarkan tipe
            $modelClass = $this->typeModelMap[$type];
            
            // Cek cache terlebih dahulu
            $cacheKey = "admin.settings.{$type}";
            $data = Cache::remember($cacheKey, 3600, function () use ($modelClass) {
                // Ambil record pertama (singleton)
                return $modelClass::first() ?? $modelClass::create([]);
            });

            return response()->json($data, 200);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data.'
            ], 500);
        }
    }

    /**
     * Perbarui data setting berdasarkan tipe
     * 
     * @param Request $request - Request berisi data setting baru
     * @param string $type - Tipe setting (site-settings, about, dll)
     * @return \Illuminate\Http\JsonResponse - Data setting yang diupdate
     */
    public function update(Request $request, string $type, PublicContentCache $cache)
    {
        // Validasi tipe setting
        if (!isset($this->typeModelMap[$type])) {
            return response()->json([
                'message' => 'Tipe setting tidak valid',
                'valid_types' => array_keys($this->typeModelMap)
            ], 422);
        }

        $validated = $this->validateSettings($request, $type);

        try {
            $modelClass = $this->typeModelMap[$type];
            
            // Ambil atau buat record singleton
            $data = $modelClass::firstOrCreate([]);

            // Update dengan data yang telah divalidasi
            $data->update($validated);

            // Invalidasi cache admin
            $cacheKey = "admin.settings.{$type}";
            Cache::forget($cacheKey);

            // Invalidasi cache konten publik instan
            $cache->flushLayout();
            $cache->flushHome();

            return response()->json($data, 200);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui data.'
            ], 500);
        }
    }

    /**
     * Validasi data input berdasarkan tipe singleton
     */
    protected function validateSettings(Request $request, string $type): array
    {
        $rules = match ($type) {
            'contact-settings' => [
                'address' => 'nullable|string|max:500',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:50',
                'whatsapp' => 'nullable|string|max:50',
                'instagram_url' => 'nullable|url|max:255',
                'facebook_url' => 'nullable|url|max:255',
                'youtube_url' => 'nullable|url|max:255',
                'tiktok_url' => 'nullable|url|max:255',
                'google_maps_embed' => 'nullable|string|max:2048',
                'operational_hours' => 'nullable|string|max:255',
            ],
            'site-settings' => [
                'site_name' => 'nullable|string|max:255',
                'logo' => 'nullable|string|max:2048',
                'favicon' => 'nullable|string|max:2048',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:1000',
                'meta_keywords' => 'nullable|string|max:500',
                'primary_color' => 'nullable|string|max:50',
                'maintenance_mode' => 'nullable|boolean',
            ],
            'about' => [
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'vision' => 'nullable|string',
                'mission' => 'nullable|array',
                'values' => 'nullable|array',
                'image' => 'nullable|string|max:2048',
                'legal_info' => 'nullable|string',
                'organization_structure' => 'nullable|array',
            ],
            'donation-settings' => [
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'collected_amount' => 'nullable|integer|min:0',
                'target_amount' => 'nullable|integer|min:0',
                'bank_name' => 'nullable|string|max:100',
                'bank_account_number' => 'nullable|string|max:100',
                'bank_account_holder' => 'nullable|string|max:255',
                'qris_image' => 'nullable|string|max:2048',
                'whatsapp_number' => 'nullable|string|max:50',
                'payment_instruction' => 'nullable|string',
                'suggested_amounts' => 'nullable|array',
            ],
            'footer-settings' => [
                'description' => 'nullable|string',
                'copyright_text' => 'nullable|string|max:255',
                'quick_links' => 'nullable|array',
                'social_links' => 'nullable|array',
            ],
            default => [],
        };

        return $request->validate($rules);
    }
}
