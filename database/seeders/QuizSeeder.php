<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

/**
 * QuizSeeder: Membuat kuis demo siap uji coba untuk Fase 6.
 * Kuis: "Ulangan Harian: Sel & Mikroskop"
 * Token: BIOCIN
 * Mode: Per-soal (question), 30 detik per soal
 */
class QuizSeeder extends Seeder
{
    public function run(): void
    {
        // Buat kuis demo menggunakan updateOrCreate agar aman di-seed ulang
        $quiz = Quiz::updateOrCreate(
            ['title' => 'Ulangan Harian: Sel & Mikroskop'],
            [
                'token'            => 'BIOCIN',
                'is_active'        => true,
                'duration_minutes' => 15,
                'timer_mode'       => 'question',
            ]
        );

        // Hapus soal lama jika ada (agar tidak duplikat saat seed ulang)
        $quiz->questions()->delete();

        $soal = [
            [
                'question_text'  => 'Organel sel yang berfungsi sebagai "pabrik energi" tempat berlangsungnya respirasi seluler adalah...',
                'option_a'       => 'Ribosom',
                'option_b'       => 'Mitokondria',
                'option_c'       => 'Kloroplas',
                'option_d'       => 'Retikulum Endoplasma',
                'correct_option' => 'b',
                'sort_order'     => 1,
                'duration_seconds' => 30,
            ],
            [
                'question_text'  => 'Struktur sel yang hanya ditemukan pada sel tumbuhan dan TIDAK terdapat pada sel hewan adalah...',
                'option_a'       => 'Mitokondria dan Ribosom',
                'option_b'       => 'Membran Sel dan Sitoplasma',
                'option_c'       => 'Dinding Sel, Kloroplas, dan Vakuola Sentral',
                'option_d'       => 'Nukleus dan Apparatus Golgi',
                'correct_option' => 'c',
                'sort_order'     => 2,
                'duration_seconds' => 30,
            ],
            [
                'question_text'  => 'Robert Hooke pertama kali mengamati dan memberi nama "sel" pada tahun 1665 dari pengamatannya terhadap...',
                'option_a'       => 'Sel darah merah manusia',
                'option_b'       => 'Jaringan akar tanaman',
                'option_c'       => 'Sayatan tipis gabus pohon ek',
                'option_d'       => 'Air kolam di bawah mikroskop',
                'correct_option' => 'c',
                'sort_order'     => 3,
                'duration_seconds' => 30,
            ],
            [
                'question_text'  => 'Organel sel yang bertanggung jawab dalam proses sintesis protein adalah...',
                'option_a'       => 'Lisosom',
                'option_b'       => 'Sentrosom',
                'option_c'       => 'Apparatus Golgi',
                'option_d'       => 'Ribosom',
                'correct_option' => 'd',
                'sort_order'     => 4,
                'duration_seconds' => 30,
            ],
            [
                'question_text'  => 'Perbedaan utama antara Mikroskop Cahaya dan Mikroskop Elektron terletak pada...',
                'option_a'       => 'Ukuran fisik dan beratnya',
                'option_b'       => 'Sumber pencahayaan yang digunakan (foton vs berkas elektron) sehingga menghasilkan resolusi yang sangat berbeda',
                'option_c'       => 'Warna gambar yang dihasilkan (berwarna vs hitam-putih)',
                'option_d'       => 'Kemampuan mengamati benda hidup vs benda mati',
                'correct_option' => 'b',
                'sort_order'     => 5,
                'duration_seconds' => 30,
            ],
        ];

        foreach ($soal as $item) {
            QuizQuestion::create(array_merge(['quiz_id' => $quiz->id], $item));
        }

        $this->command->info("✅ QuizSeeder: Kuis demo 'Ulangan Harian: Sel & Mikroskop' berhasil dibuat.");
        $this->command->line("   → Token Kuis: BIOCIN | Mode: Per-Soal (30 detik/soal) | Soal: 5 butir");
    }
}
