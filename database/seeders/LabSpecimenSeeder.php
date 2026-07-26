<?php

namespace Database\Seeders;

use App\Models\LabSpecimen;
use Illuminate\Database\Seeder;

/**
 * LabSpecimenSeeder
 *
 * Mengisi tabel lab_specimens dengan 2 spesimen awal yang sebelumnya
 * hardcoded di VirtualLabController. Gambar menggunakan path legacy
 * yang sudah ada di public/images/lab/.
 *
 * Jalankan: php artisan db:seed --class=LabSpecimenSeeder
 */
class LabSpecimenSeeder extends Seeder
{
    public function run(): void
    {
        // Hindari duplikasi saat re-seed
        if (LabSpecimen::count() > 0) {
            $this->command->info('LabSpecimen: Tabel sudah berisi data, seeder dilewati.');
            return;
        }

        $specimens = [
            [
                'name'             => 'Daun (Stomata & Kloroplas)',
                'icon'             => '🌿',
                'macro_image_path' => '/images/lab/macro-leaf.png',
                'micro_image_path' => '/images/lab/micro-stomata.png',
                'magnification'    => '400×',
                'structures'       => [
                    [
                        'name'     => 'Sel Penjaga (Guard Cell)',
                        'function' => 'Mengatur buka-tutup stomata untuk pertukaran gas CO₂ dan O₂ dalam proses fotosintesis.',
                        'icon'     => '🔵',
                    ],
                    [
                        'name'     => 'Kloroplas',
                        'function' => 'Organel tempat berlangsungnya fotosintesis — mengubah energi cahaya matahari menjadi glukosa (makanan sel).',
                        'icon'     => '🟢',
                    ],
                    [
                        'name'     => 'Dinding Sel',
                        'function' => 'Lapisan kaku dari selulosa yang memberikan bentuk dan perlindungan mekanis bagi sel tumbuhan.',
                        'icon'     => '🟫',
                    ],
                ],
                'quran_verse'  => '"Dan Dia-lah yang menurunkan air hujan dari langit, lalu Kami tumbuhkan dengan air itu segala macam tumbuh-tumbuhan, maka Kami keluarkan dari tumbuh-tumbuhan itu tanaman yang menghijau."',
                'quran_ref'    => "QS. Al-An'am (6): 99",
                'reflection'   => 'Stomata pada daun adalah "pintu napas" yang Allah ciptakan dengan presisi luar biasa — membuka saat siang untuk menyerap CO₂, menutup saat kekeringan agar tanaman tidak layu. Ketelitian mekanisme ini jauh melampaui teknologi katup buatan manusia.',
                'is_active'    => true,
                'sort_order'   => 1,
            ],
            [
                'name'             => 'Sel Darah Merah (Eritrosit)',
                'icon'             => '🔴',
                'macro_image_path' => '/images/lab/macro-blood.png',
                'micro_image_path' => '/images/lab/micro-blood.png',
                'magnification'    => '1000×',
                'structures'       => [
                    [
                        'name'     => 'Eritrosit (Sel Darah Merah)',
                        'function' => 'Mengangkut oksigen (O₂) dari paru-paru ke seluruh jaringan tubuh menggunakan protein hemoglobin.',
                        'icon'     => '🔴',
                    ],
                    [
                        'name'     => 'Bentuk Bikonkaf',
                        'function' => 'Bentuk cekung dua sisi memaksimalkan luas permukaan untuk pertukaran gas dan memudahkan sel melentur di kapiler sempit.',
                        'icon'     => '⭕',
                    ],
                    [
                        'name'     => 'Hemoglobin',
                        'function' => 'Protein pembawa oksigen yang mengandung atom besi (Fe) — inilah yang membuat darah berwarna merah.',
                        'icon'     => '🧬',
                    ],
                ],
                'quran_verse'  => '"Dan sesungguhnya Kami telah menciptakan manusia dari saripati (berasal) dari tanah. Kemudian Kami jadikan saripati itu air mani (yang disimpan) dalam tempat yang kokoh (rahim)."',
                'quran_ref'    => "QS. Al-Mu'minun (23): 12-13",
                'reflection'   => 'Setiap detik, tubuhmu memproduksi ±2 juta eritrosit baru. Sel-sel kecil ini bekerja tanpa henti selama 120 hari sebelum digantikan. Dalam kesederhanaan bentuknya tersimpan kecerdasan desain Yang Maha Pencipta.',
                'is_active'    => true,
                'sort_order'   => 2,
            ],
        ];

        foreach ($specimens as $data) {
            LabSpecimen::create($data);
        }

        $this->command->info('LabSpecimen: 2 spesimen awal berhasil di-seed ✅');
    }
}
