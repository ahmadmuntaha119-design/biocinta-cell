<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Optimisasi Database: Tambah indeks strategis pada tabel kuis
     * untuk mempercepat query pencarian soal dan kalkulasi leaderboard.
     */
    public function up(): void
    {
        // Composite index untuk pencarian soal berikutnya (mode per-soal)
        // Query: WHERE quiz_id = ? AND sort_order > ? ORDER BY sort_order
        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->index(['quiz_id', 'sort_order'], 'idx_questions_quiz_sort');
        });

        // Composite index untuk kalkulasi leaderboard
        // Query: WHERE user_id = ? AND submitted_at IS NOT NULL
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->index(['user_id', 'submitted_at'], 'idx_attempts_user_submitted');
        });
    }

    public function down(): void
    {
        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->dropIndex('idx_questions_quiz_sort');
        });

        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropIndex('idx_attempts_user_submitted');
        });
    }
};
