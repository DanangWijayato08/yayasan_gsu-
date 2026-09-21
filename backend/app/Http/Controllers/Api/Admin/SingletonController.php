<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use App\Models\ContactSetting;
use App\Models\DonationSetting;
use App\Models\FooterSetting;
use App\Models\SiteSetting;
use App\Services\PublicContentCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SingletonController extends Controller
{
    private array $map = [
        'site-settings' => SiteSetting::class,
        'about' => AboutPage::class,
        'donation-settings' => DonationSetting::class,
        'contact-settings' => ContactSetting::class,
        'footer-settings' => FooterSetting::class,
    ];

    public function show(string $type)
    {
        return response()->json($this->model($type)::first());
    }

    public function update(Request $request, string $type, PublicContentCache $cache)
    {
        $modelClass = $this->model($type);
        $rules = $this->validationRules($type);
        $validated = $request->validate($rules);

        if ($type === 'contact-settings' && isset($validated['google_maps_embed'])) {
            $validated['google_maps_embed'] = $this->normalizeGoogleMapsEmbedUrl($validated['google_maps_embed']);
        }

        /** @var Model $model */
        $model = $modelClass::firstOrNew();
        $model->fill($validated);
        $model->save();
        $this->flush($type, $cache);

        return response()->json($model);
    }

    private function model(string $type): string
    {
        abort_unless(isset($this->map[$type]), 404);
        return $this->map[$type];
    }

    private function validationRules(string $type): array
    {
        return match ($type) {
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
                'mission' => 'nullable',
                'values' => 'nullable',
                'image' => 'nullable|string|max:2048',
                'legal_info' => 'nullable|string',
                'organization_structure' => 'nullable',
            ],
            'donation-settings' => [
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'bank_name' => 'nullable|string|max:100',
                'bank_account_number' => 'nullable|string|max:100',
                'bank_account_holder' => 'nullable|string|max:150',
                'qris_image' => 'nullable|string|max:2048',
                'whatsapp_number' => 'nullable|string|max:50',
                'payment_instruction' => 'nullable|string',
                'suggested_amounts' => 'nullable',
            ],
            'contact-settings' => [
                'address' => 'nullable|string|max:1000',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:50',
                'whatsapp' => 'nullable|string|max:50',
                'facebook_url' => 'nullable|string|max:500',
                'instagram_url' => 'nullable|string|max:500',
                'youtube_url' => 'nullable|string|max:500',
                'tiktok_url' => 'nullable|string|max:500',
                'google_maps_embed' => 'nullable|string|max:2000',
                'operational_hours' => 'nullable|string|max:255',
            ],
            'footer-settings' => [
                'description' => 'nullable|string|max:1000',
                'copyright_text' => 'nullable|string|max:255',
                'quick_links' => 'nullable',
                'social_links' => 'nullable',
            ],
            default => [],
        };
    }

    private function flush(string $type, PublicContentCache $cache): void
    {
        Cache::forget('public.'.$type);

        if (in_array($type, ['site-settings', 'contact-settings', 'footer-settings'], true)) {
            $cache->flushLayout();
            return;
        }

        if (in_array($type, ['about', 'donation-settings'], true)) {
            $cache->flushHome();
        }
    }

    private function normalizeGoogleMapsEmbedUrl(?string $url): ?string
    {
        if (empty($url)) {
            return $url;
        }

        $url = trim($url);

        // 1. Ekstrak src jika user mem-paste seluruh tag <iframe>
        if (preg_match('/src=["\']([^"\']+)["\']/i', $url, $matches)) {
            $url = $matches[1];
        }

        // 2. Jika sudah berupa embed URL Google Maps
        if (str_contains($url, '/maps/embed') || str_contains($url, 'output=embed')) {
            return $url;
        }

        // 3. Jika berupa tautan pendek maps.app.goo.gl, cari tujuan redirect-nya
        if (str_contains($url, 'maps.app.goo.gl')) {
            try {
                $response = Http::withUserAgent('Mozilla/5.0')->timeout(4)->withoutRedirecting()->head($url);
                $location = $response->header('Location');
                if ($location) {
                    $url = $location;
                }
            } catch (\Throwable) {
                // Lanjutkan ke pola berikutnya jika gagal request
            }
        }

        // 4. Jika tautan tempat /maps/place/{nama_tempat}
        if (preg_match('/\/maps\/place\/([^\/@]+)/i', $url, $matches)) {
            $place = urldecode(str_replace('+', ' ', $matches[1]));
            return 'https://maps.google.com/maps?q=' . urlencode($place) . '&output=embed';
        }

        // 5. Jika terdapat koordinat latitude,longitude (@-7.123,110.123)
        if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $matches)) {
            return 'https://maps.google.com/maps?q=' . $matches[1] . ',' . $matches[2] . '&output=embed';
        }

        // 6. Jika tautan pencarian /maps/search/{query}
        if (preg_match('#/maps/search/([^/?]+)#i', $url, $matches)) {
            $query = urldecode(str_replace('+', ' ', $matches[1]));
            return 'https://maps.google.com/maps?q=' . urlencode($query) . '&output=embed';
        }

        // 7. Jika berupa teks alamat biasa (bukan URL http)
        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
            return 'https://maps.google.com/maps?q=' . urlencode($url) . '&output=embed';
        }

        return $url;
    }
}
