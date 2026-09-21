<?php

namespace Database\Seeders;

use App\Models\AboutPage;
use App\Models\ContactSetting;
use App\Models\DonationSetting;
use App\Models\FooterSetting;
use App\Models\Gallery;
use App\Models\HomepageSection;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            ProgramSeeder::class,
        ]);

        SiteSetting::updateOrCreate(['id' => 1], [
            'site_name' => 'Gerakan Sedekah Umat',
            'meta_title' => 'Gerakan Sedekah Umat',
            'meta_description' => 'Website resmi Yayasan Gerakan Sedekah Umat.',
            'primary_color' => '#2E7D32',
        ]);

        $homepageSections = [
            [
                'section_key' => 'hero',
                'title' => 'Gerakan Sedekah Umat',
                'subtitle' => 'Yayasan Amal Terpercaya',
                'description' => 'Menghubungkan kebaikan, memberdayakan masyarakat, dan membangun masa depan bersama.',
                'image' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?q=80&w=900&auto=format&fit=crop',
                'button_primary_text' => 'Donasi Sekarang',
                'button_primary_link' => '/donasi',
                'button_secondary_text' => 'Pelajari Program',
                'button_secondary_link' => '/program',
                'extra_data' => [
                    'donors' => 15230,
                    'funds_distributed' => 850000000,
                    'active_programs' => 24,
                    'beneficiaries' => 5000,
                ],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'section_key' => 'donation_cta',
                'title' => 'Bantuan Ramadan 2026',
                'subtitle' => 'Kampanye Khusus',
                'description' => 'Total target untuk kampanye khusus bulan ini.',
                'button_primary_text' => 'Berikan Dampak Sekarang',
                'button_primary_link' => '/donasi',
                'extra_data' => ['current' => 850000000, 'target' => 1200000000],
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($homepageSections as $section) {
            HomepageSection::updateOrCreate(
                ['section_key' => $section['section_key']],
                $section
            );
        }

        AboutPage::updateOrCreate(['id' => 1], [
            'title' => 'Tentang Yayasan Gerakan Sedekah Umat',
            'description' => 'Gerakan Sedekah Umat hadir untuk mengelola amanah kebaikan secara profesional, transparan, dan berdampak bagi masyarakat.',
            'vision' => 'Menjadi gerakan sosial Islam yang terpercaya dalam menebar manfaat dan pemberdayaan umat.',
            'mission' => ['Menghimpun sedekah secara amanah.', 'Menyalurkan bantuan tepat sasaran.', 'Membangun program pemberdayaan berkelanjutan.'],
            'values' => ['Amanah', 'Transparan', 'Humanis', 'Berkelanjutan'],
            'image' => 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=900&auto=format&fit=crop',
            'legal_info' => 'Informasi legalitas dapat diperbarui melalui dashboard admin.',
        ]);

        DonationSetting::updateOrCreate(['id' => 1], [
            'title' => 'Setiap Rupiah Membawa Harapan',
            'description' => 'Salurkan donasi terbaik Anda melalui rekening resmi yayasan atau konfirmasi melalui WhatsApp.',
            'bank_name' => 'Bank Syariah Indonesia',
            'bank_account_number' => '1234567890',
            'bank_account_holder' => 'Yayasan Gerakan Sedekah Umat',
            'whatsapp_number' => '6281234567890',
            'payment_instruction' => 'Transfer ke rekening resmi, lalu kirim bukti transfer melalui tombol WhatsApp.',
            'suggested_amounts' => [50000, 100000, 250000, 500000],
        ]);

        ContactSetting::updateOrCreate(['id' => 1], [
            'address' => 'Jl. Kebajikan No. 12, Jakarta Selatan, Indonesia',
            'email' => 'halo@sedekahumat.org',
            'phone' => '+62 812-3456-7890',
            'whatsapp' => '6281234567890',
            'instagram_url' => 'https://instagram.com/',
            'facebook_url' => 'https://facebook.com/',
            'google_maps_embed' => 'https://maps.google.com/maps?q=Jakarta%20Selatan&t=&z=13&ie=UTF8&iwloc=&output=embed',
            'operational_hours' => 'Senin - Jumat, 09.00 - 17.00 WIB',
        ]);

        FooterSetting::updateOrCreate(['id' => 1], [
            'description' => 'Gerakan Sedekah Umat menghubungkan kebaikan para donatur dengan penerima manfaat.',
            'copyright_text' => '(c) 2026 Gerakan Sedekah Umat. Semua hak dilindungi.',
            'quick_links' => [
                ['label' => 'Tentang', 'url' => '/tentang'],
                ['label' => 'Program', 'url' => '/program'],
                ['label' => 'Donasi', 'url' => '/donasi'],
            ],
            'social_links' => [
                ['label' => 'Instagram', 'url' => 'https://instagram.com/'],
                ['label' => 'Facebook', 'url' => 'https://facebook.com/'],
            ],
        ]);

        $galleries = [
            [
                'title' => 'Distribusi Bantuan Pangan',
                'description' => 'Relawan menyalurkan paket pangan untuk keluarga prasejahtera.',
                'image' => 'https://images.unsplash.com/photo-1509099836639-18ba1795216d?q=80&w=600&auto=format&fit=crop',
                'category' => 'Sedekah Pangan',
                'activity_date' => now()->subDays(10)->toDateString(),
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Kegiatan Belajar Anak',
                'description' => 'Pendampingan belajar untuk anak yatim dan dhuafa.',
                'image' => 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=600&auto=format&fit=crop',
                'category' => 'Pendidikan',
                'activity_date' => now()->subDays(20)->toDateString(),
                'is_active' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($galleries as $gallery) {
            Gallery::updateOrCreate(
                ['title' => $gallery['title']],
                $gallery
            );
        }
    }
}
