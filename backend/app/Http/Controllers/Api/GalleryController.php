<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        return response()->json(Gallery::where('active', true)->get(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|url',
            'category' => 'nullable|string|max:100',
            'active' => 'boolean',
        ]);

        $gallery = Gallery::create($validated);
        return response()->json($gallery, 201);
    }

    public function show(Gallery $gallery)
    {
        return response()->json($gallery, 200);
    }

    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'image' => 'url',
            'category' => 'nullable|string|max:100',
            'active' => 'boolean',
        ]);

        $gallery->update($validated);
        return response()->json($gallery, 200);
    }

    public function destroy(Gallery $gallery)
    {
        $gallery->delete();
        return response()->json(['message' => 'Gallery deleted'], 200);
    }
}
