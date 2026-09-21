<?php

namespace App\Services;

use App\Models\ContactSetting;
use App\Models\DonationSetting;
use App\Models\FooterSetting;
use App\Models\Gallery;
use App\Models\HomepageSection;
use App\Models\Program;
use App\Models\SiteSetting;
use App\Models\AboutPage;
use Illuminate\Support\Facades\Cache;

class PublicContentCache
{
    public const LAYOUT_KEY = 'public.layout';
    public const HOME_KEY = 'public.home';
    public const ABOUT_KEY = 'public.about';
    public const PROGRAMS_KEY = 'public.programs';
    public const GALLERIES_KEY = 'public.galleries';
    public const TTL = 600;

    public function layout(): array
    {
        return Cache::remember(self::LAYOUT_KEY, self::TTL, fn () => [
            'site_settings' => SiteSetting::first()?->toArray(),
            'contact_settings' => ContactSetting::first()?->toArray(),
            'footer_settings' => FooterSetting::first()?->toArray(),
        ]);
    }

    public function home(): array
    {
        return Cache::remember(self::HOME_KEY, self::TTL, fn () => [
            ...$this->layout(),
            'about_page' => AboutPage::first()?->toArray(),
            'homepage_sections' => HomepageSection::where('is_active', true)
                ->orderBy('sort_order')
                ->get()
                ->keyBy('section_key')
                ->toArray(),
            'featured_programs' => Program::where('is_active', true)
                ->where('is_featured', true)
                ->orderBy('sort_order')
                ->latest()
                ->limit(3)
                ->get()
                ->toArray(),
            'gallery_preview' => Gallery::where('is_active', true)
                ->orderBy('sort_order')
                ->latest('activity_date')
                ->limit(8)
                ->get()
                ->toArray(),
            'donation_settings' => DonationSetting::first()?->toArray(),
        ]);
    }

    public function flushLayout(): void
    {
        Cache::forget(self::LAYOUT_KEY);
        Cache::forget(self::HOME_KEY);
        Cache::forget('public.site-settings');
        Cache::forget('public.contact-settings');
        Cache::forget('public.footer');
    }

    public function flushHome(): void
    {
        Cache::forget(self::HOME_KEY);
        Cache::forget('public.homepage-sections');
        Cache::forget('public.about');
        Cache::forget('public.donation-settings');
    }

    public function flushPublicLists(): void
    {
        Cache::forget(self::HOME_KEY);
        Cache::forget(self::PROGRAMS_KEY);
        Cache::forget(self::GALLERIES_KEY);
    }
}
