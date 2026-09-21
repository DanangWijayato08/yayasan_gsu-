<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Services\PublicContentCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GalleryController extends Controller
{
    public function index()
    {
        return response()->json(
            Cache::remember(PublicContentCache::GALLERIES_KEY, PublicContentCache::TTL, fn () => Gallery::where('is_active', true)
                ->orderBy('sort_order')
                ->latest('activity_date')
                ->get()
                ->toArray()),
            200
        );
    }

    public function adminIndex(Request $request)
    {
        $perPage = min((int) $request->integer('per_page', 10), 50);
        $query = Gallery::query();

        if ($search = $request->string('search')->toString()) {
            $likeOp = config('database.default') === 'pgsql' ? 'ilike' : 'like';
            $query->where(function ($q) use ($search, $likeOp) {
                $q->where('title', $likeOp, "%{$search}%")
                    ->orWhere('category', $likeOp, "%{$search}%")
                    ->orWhere('description', $likeOp, "%{$search}%");
            });
        }

        if ($category = $request->string('category')->toString()) {
            $query->where('category', $category);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->string('status')->toString() === 'active');
        }

        return response()->json($query->orderBy('sort_order')->latest('activity_date')->paginate($perPage), 200);
    }

    public function store(Request $request, PublicContentCache $cache)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|string|max:2048',
            'category' => 'nullable|string|max:100',
            'activity_date' => 'nullable|date',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'active' => 'nullable|boolean',
        ]);
        if (array_key_exists('active', $validated)) {
            $validated['is_active'] = $validated['active'];
            unset($validated['active']);
        }

        $gallery = Gallery::create($validated);
        $cache->flushPublicLists();
        return response()->json($gallery, 201);
    }

    public function show(Gallery $gallery)
    {
        return response()->json($gallery, 200);
    }

    public function update(Request $request, Gallery $gallery, PublicContentCache $cache)
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'image' => 'string|max:2048',
            'category' => 'nullable|string|max:100',
            'activity_date' => 'nullable|date',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'active' => 'nullable|boolean',
        ]);
        if (array_key_exists('active', $validated)) {
            $validated['is_active'] = $validated['active'];
            unset($validated['active']);
        }

        $gallery->update($validated);
        $cache->flushPublicLists();
        return response()->json($gallery, 200);
    }

    public function destroy(Gallery $gallery, PublicContentCache $cache)
    {
        $gallery->delete();
        $cache->flushPublicLists();
        return response()->json(['message' => 'Gallery deleted'], 200);
    }
}
