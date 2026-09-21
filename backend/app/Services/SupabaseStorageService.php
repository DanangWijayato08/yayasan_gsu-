<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class SupabaseStorageService
{
    public function upload(UploadedFile $file, string $folder = 'uploads'): array
    {
        $url = rtrim((string) config('services.supabase.url'), '/');
        $key = (string) config('services.supabase.service_role_key');
        $bucket = (string) config('services.supabase.storage_bucket', 'public-assets');

        // Check if Supabase cloud storage credentials are configured
        if (!empty($url) && !empty($key)) {
            try {
                return $this->uploadToSupabase($file, $folder, $url, $key, $bucket);
            } catch (\Throwable $e) {
                // If Supabase fails (e.g. network/credentials), fallback to local storage
                report($e);
            }
        }

        // Fallback to local public disk
        return $this->uploadToLocal($file, $folder);
    }

    private function uploadToSupabase(UploadedFile $file, string $folder, string $url, string $key, string $bucket): array
    {
        $folder = trim($folder, '/');
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $path = ($folder ? $folder.'/' : '').Str::uuid().'.'.$extension;
        $mime = $file->getMimeType() ?: 'application/octet-stream';

        $response = Http::withToken($key)
            ->withHeaders([
                'apikey' => $key,
                'x-upsert' => 'true',
                'Content-Type' => $mime,
            ])
            ->withBody(file_get_contents($file->getRealPath()), $mime)
            ->post("{$url}/storage/v1/object/{$bucket}/{$path}");

        if ($response->failed()) {
            throw new RuntimeException($response->json('message') ?: 'Upload ke Supabase Storage gagal.');
        }

        return [
            'bucket' => $bucket,
            'path' => $path,
            'url' => "{$url}/storage/v1/object/public/{$bucket}/{$path}",
        ];
    }

    private function uploadToLocal(UploadedFile $file, string $folder): array
    {
        $folder = trim($folder, '/');
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $filename = Str::uuid().'.'.$extension;
        $targetDirectory = $folder ?: 'uploads';

        $storedPath = $file->storeAs($targetDirectory, $filename, 'public');

        if (!$storedPath) {
            throw new RuntimeException('Gagal menyimpan file ke penyimpanan lokal.');
        }

        // Normalize public URL using the current request host and scheme
        $baseUrl = request()?->getSchemeAndHttpHost() ?: rtrim(config('app.url', 'http://localhost'), '/');
        $url = $baseUrl . '/storage/' . ltrim($storedPath, '/');

        return [
            'bucket' => 'local-public',
            'path' => $storedPath,
            'url' => $url,
        ];
    }
}
