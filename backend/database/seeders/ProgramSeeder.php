<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        Program::insert([
            [
                'title' => 'Lumbung Pangan Masyarakat',
                'description' => 'Menyediakan dukungan nutrisi esensial bagi lebih dari 500 keluarga setiap bulannya.',
                'category' => 'Sosial',
                'collected' => 42000000,
                'target' => 60000000,
                'image' => 'https://images.unsplash.com/photo-1593113646773-028c64a8f1b8?q=80&w=700&auto=format&fit=crop',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Beasiswa Masa Depan Cerah',
                'description' => 'Memberdayakan 50 anak yatim dengan cakupan akademik penuh hingga lulus.',
                'category' => 'Pendidikan',
                'collected' => 38000000,
                'target' => 50000000,
                'image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?q=80&w=700&auto=format&fit=crop',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Renovasi Masjid Sejahtera',
                'description' => 'Merenovasi pusat komunitas untuk kenyamanan lebih dari 2.000 jemaah.',
                'category' => 'Sosial',
                'collected' => 85000000,
                'target' => 85000000,
                'image' => 'https://images.unsplash.com/photo-1519817650390-64a93db51149?q=80&w=700&auto=format&fit=crop',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
