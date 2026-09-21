<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\SupabaseStorageService;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function __invoke(Request $request, SupabaseStorageService $storage)
    {
        $validated = $request->validate([
            'image' => 'required|file|mimes:jpg,jpeg,png,webp,svg|max:5120',
            'folder' => 'nullable|string|in:logos,hero,programs,galleries,donation,about,qris,uploads',
        ]);

        try {
            $uploaded = $storage->upload($validated['image'], $validated['folder'] ?? 'uploads');

            return response()->json([
                'message' => 'Upload berhasil',
                'bucket' => $uploaded['bucket'],
                'path' => $uploaded['path'],
                'url' => $uploaded['url'],
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Gagal mengunggah file: ' . $e->getMessage(),
            ], 422);
        }
    }
}
