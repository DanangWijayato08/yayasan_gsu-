<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use App\Models\ContactSetting;
use App\Models\DonationSetting;
use App\Models\FooterSetting;
use App\Models\HomepageSection;
use App\Models\SiteSetting;
use App\Services\PublicContentCache;
use Illuminate\Support\Facades\Cache;

class ContentController extends Controller
{
    public function home(PublicContentCache $cache)
    {
        return response()->json($cache->home());
    }

    public function layout(PublicContentCache $cache)
    {
        return response()->json($cache->layout());
    }

    public function siteSettings()
    {
        return response()->json(Cache::remember('public.site-settings', PublicContentCache::TTL, fn () => SiteSetting::first()?->toArray()));
    }

    public function homepage()
    {
        return response()->json(
            Cache::remember('public.homepage-sections', PublicContentCache::TTL, fn () => HomepageSection::where('is_active', true)
                ->orderBy('sort_order')
                ->get()
                ->keyBy('section_key')
                ->toArray())
        );
    }

    public function about()
    {
        return response()->json(Cache::remember(PublicContentCache::ABOUT_KEY, PublicContentCache::TTL, fn () => AboutPage::first()?->toArray()));
    }

    public function donationSettings()
    {
        return response()->json(Cache::remember('public.donation-settings', PublicContentCache::TTL, fn () => DonationSetting::first()?->toArray()));
    }

    public function contactSettings()
    {
        return response()->json(Cache::remember('public.contact-settings', PublicContentCache::TTL, fn () => ContactSetting::first()?->toArray()));
    }

    public function footer()
    {
        return response()->json(Cache::remember('public.footer', PublicContentCache::TTL, fn () => FooterSetting::first()?->toArray()));
    }
}
