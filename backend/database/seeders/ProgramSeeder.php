<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            [
                'title' => 'Lumbung Pangan Masyarakat',
                'category' => 'Sedekah Pangan',
                'short_description' => 'Dukungan nutrisi bulanan untuk keluarga prasejahtera.',
                'description' => 'Menyediakan dukungan nutrisi esensial bagi lebih dari 500 keluarga setiap bulannya.',
                'collected_amount' => 42000000,
                'target_amount' => 60000000,
                'image' => 'https://images.unsplash.com/photo-1593113646773-028c64a8f1b8?q=80&w=700&auto=format&fit=crop',
                'beneficiaries_count' => 500,
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Beasiswa Masa Depan Cerah',
                'category' => 'Pendidikan',
                'short_description' => 'Beasiswa pendidikan untuk anak yatim dan dhuafa.',
                'description' => 'Memberdayakan 50 anak yatim dengan cakupan akademik penuh hingga lulus.',
                'collected_amount' => 38000000,
                'target_amount' => 50000000,
                'image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?q=80&w=700&auto=format&fit=crop',
                'beneficiaries_count' => 50,
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Renovasi Masjid Sejahtera',
                'category' => 'Wakaf',
                'short_description' => 'Renovasi pusat ibadah dan kegiatan sosial warga.',
                'description' => 'Merenovasi pusat komunitas untuk kenyamanan lebih dari 2.000 jemaah dan warga sekitar.',
                'collected_amount' => 85000000,
                'target_amount' => 85000000,
                'image' => 'https://images.unsplash.com/photo-1519817650390-64a93db51149?q=80&w=700&auto=format&fit=crop',
                'beneficiaries_count' => 2000,
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($programs as $program) {
            Program::updateOrCreate(
                ['slug' => Str::slug($program['title'])],
                $program + ['slug' => Str::slug($program['title'])]
            );
        }
    }
}
