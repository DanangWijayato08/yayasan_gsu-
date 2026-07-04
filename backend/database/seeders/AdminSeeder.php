<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin GSU',
            'email' => 'admin@sedekahumat.org',
            'password' => Hash::make('admin123456'),
            'email_verified_at' => now(),
        ]);
    }
}
