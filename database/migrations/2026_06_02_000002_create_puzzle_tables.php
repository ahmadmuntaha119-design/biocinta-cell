<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Task 1.3: Skema Tabel Gamifikasi Puzzle Harian
     */
    public function up(): void
    {
        // Bank Soal Puzzle: Satu entri per tanggal tayang (UNIQUE)
        Schema::create('puzzle_banks', function (Blueprint $table) {
            $table->id();
            $table->date('publish_date')->unique()
                  ->comment('Satu soal puzzle per hari — UNIQUE constraint mencegah konflik jadwal');

            // Target 1
            $table->text('target_text_1')->comment('Deskripsi fungsi organel ke-1');
            $table->string('correct_organelle_1', 100)->comment('Jawaban benar organel ke-1');

            // Target 2
            $table->text('target_text_2')->comment('Deskripsi fungsi organel ke-2');
            $table->string('correct_organelle_2', 100)->comment('Jawaban benar organel ke-2');

            // Target 3
            $table->text('target_text_3')->comment('Deskripsi fungsi organel ke-3');
            $table->string('correct_organelle_3', 100)->comment('Jawaban benar organel ke-3');

            // Target 4
            $table->text('target_text_4')->comment('Deskripsi fungsi organel ke-4');
            $table->string('correct_organelle_4', 100)->comment('Jawaban benar organel ke-4');

            $table->timestamps();

            // Index untuk efisiensi query harian "ambil soal hari ini"
            $table->index('publish_date');
        });

        // Rekap Skor Puzzle: Log per pengerjaan siswa
        Schema::create('puzzle_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('puzzle_bank_id')->constrained('puzzle_banks')->cascadeOnDelete();
            $table->integer('score')
                  ->comment('Nilai riil game: kelipatan 25 (0, 25, 50, 75, atau 100)');
            $table->integer('earned_points')
                  ->comment('Poin leaderboard: skor aktual (0-100) untuk percobaan ke-1, 0 untuk percobaan berikutnya');
            $table->timestamp('completed_at')
                  ->comment('Waktu penyelesaian puzzle untuk validasi daily constraint');
            $table->timestamps();

            // Composite index untuk kueri "apakah siswa ini sudah main hari ini?"
            $table->index(['user_id', 'completed_at'], 'user_daily_constraint');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puzzle_scores');
        Schema::dropIfExists('puzzle_banks');
    }
};
