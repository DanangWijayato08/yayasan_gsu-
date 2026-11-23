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
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
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
            report($e);

            return response()->json([
                'message' => 'Gagal mengunggah file. Silakan coba lagi.',
            ], 422);
        }
    }
}
