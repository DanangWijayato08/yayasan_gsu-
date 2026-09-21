<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use App\Services\PublicContentCache;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HomepageSectionController extends Controller
{
    public function index()
    {
        return response()->json(HomepageSection::orderBy('sort_order')->get());
    }

    public function store(Request $request, PublicContentCache $cache)
    {
        $section = HomepageSection::create($this->validated($request));
        $cache->flushHome();
        return response()->json($section, 201);
    }

    public function update(Request $request, HomepageSection $homepageSection, PublicContentCache $cache)
    {
        $homepageSection->update($this->validated($request, $homepageSection));
        $cache->flushHome();
        return response()->json($homepageSection);
    }

    public function destroy(HomepageSection $homepageSection, PublicContentCache $cache)
    {
        $homepageSection->delete();
        $cache->flushHome();
        return response()->json(['message' => 'Homepage section deleted']);
    }

    private function validated(Request $request, ?HomepageSection $section = null): array
    {
        return $request->validate([
            'section_key' => [
                $section ? 'sometimes' : 'required',
                'string',
                'max:100',
                Rule::unique('homepage_sections', 'section_key')->ignore($section?->id),
            ],
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:2048',
            'button_primary_text' => 'nullable|string|max:255',
            'button_primary_link' => 'nullable|string|max:255',
            'button_secondary_text' => 'nullable|string|max:255',
            'button_secondary_link' => 'nullable|string|max:255',
            'extra_data' => 'nullable|array',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
    }
}
