<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Services\PublicContentCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class ProgramController extends Controller
{
    public function index()
    {
        return response()->json(
            Cache::remember(PublicContentCache::PROGRAMS_KEY, PublicContentCache::TTL, fn () => Program::where('is_active', true)
                ->orderBy('sort_order')
                ->latest()
                ->get()
                ->toArray()),
            200
        );
    }

    public function featured()
    {
        return response()->json(
            Cache::remember('public.programs.featured', PublicContentCache::TTL, fn () => Program::where('is_active', true)
                ->where('is_featured', true)
                ->orderBy('sort_order')
                ->latest()
                ->get()
                ->toArray()),
            200
        );
    }

    public function adminIndex(Request $request)
    {
        $perPage = min((int) $request->integer('per_page', 10), 50);
        $query = Program::query();

        if ($search = $request->string('search')->toString()) {
            $likeOp = config('database.default') === 'pgsql' ? 'ilike' : 'like';
            $query->where(function ($q) use ($search, $likeOp) {
                $q->where('title', $likeOp, "%{$search}%")
                    ->orWhere('category', $likeOp, "%{$search}%")
                    ->orWhere('short_description', $likeOp, "%{$search}%");
            });
        }

        if ($category = $request->string('category')->toString()) {
            $query->where('category', $category);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->string('status')->toString() === 'active');
        }

        return response()->json($query->orderBy('sort_order')->latest()->paginate($perPage), 200);
    }

    public function store(Request $request, PublicContentCache $cache)
    {
        $validated = $this->validated($request);

        $program = Program::create($validated);
        $cache->flushPublicLists();
        Cache::forget('public.programs.featured');
        return response()->json($program, 201);
    }

    public function show(Program $program)
    {
        return response()->json($program, 200);
    }

    public function showBySlug(string $slug)
    {
        $program = Program::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return response()->json($program, 200);
    }

    public function update(Request $request, Program $program, PublicContentCache $cache)
    {
        $validated = $this->validated($request, $program);

        $program->update($validated);
        $cache->flushPublicLists();
        Cache::forget('public.programs.featured');
        return response()->json($program, 200);
    }

    public function destroy(Program $program, PublicContentCache $cache)
    {
        $program->delete();
        $cache->flushPublicLists();
        Cache::forget('public.programs.featured');
        return response()->json(['message' => 'Program deleted'], 200);
    }

    private function validated(Request $request, ?Program $program = null): array
    {
        $validated = $request->validate([
            'title' => [$program ? 'sometimes' : 'required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('programs', 'slug')->ignore($program?->id),
            ],
            'category' => [$program ? 'sometimes' : 'required', 'string', 'max:100'],
            'short_description' => 'nullable|string',
            'description' => [$program ? 'sometimes' : 'required', 'string'],
            'image' => 'nullable|string|max:2048',
            'target_amount' => 'nullable|integer|min:0',
            'collected_amount' => 'nullable|integer|min:0',
            'beneficiaries_count' => 'nullable|integer|min:0',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'target' => 'nullable|integer|min:0',
            'collected' => 'nullable|integer|min:0',
            'active' => 'nullable|boolean',
        ]);

        if (array_key_exists('target', $validated)) {
            $validated['target_amount'] = $validated['target'];
            unset($validated['target']);
        }

        if (array_key_exists('collected', $validated)) {
            $validated['collected_amount'] = $validated['collected'];
            unset($validated['collected']);
        }

        if (array_key_exists('active', $validated)) {
            $validated['is_active'] = $validated['active'];
            unset($validated['active']);
        }

        return $validated;
    }
}
