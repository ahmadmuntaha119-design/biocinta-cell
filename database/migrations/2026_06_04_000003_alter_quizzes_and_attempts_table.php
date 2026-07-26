<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Task 6.x: Penambahan Kolom Engine Kuis Per-Soal & Anti-Curang
     */
    public function up(): void
    {
        // Tambah mode timer kuis ke tabel quizzes
        Schema::table('quizzes', function (Blueprint $table) {
            $table->enum('timer_mode', ['quiz', 'question'])
                  ->default('quiz')
                  ->after('duration_minutes')
                  ->comment('quiz = timer global seluruh kuis | question = timer per-soal a la Quizizz');
        });

        // Tambah durasi per-soal ke quiz_questions
        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->unsignedSmallInteger('duration_seconds')
                  ->default(30)
                  ->after('sort_order')
                  ->comment('Batas waktu pengerjaan per soal (detik) — hanya aktif jika timer_mode = question');
        });

        // Tambah kolom tracking per-soal & anti-curang ke quiz_attempts
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->unsignedBigInteger('current_question_id')
                  ->nullable()
                  ->after('submitted_at')
                  ->comment('ID soal yang sedang aktif dikerjakan siswa (mode per-soal)');
            $table->timestamp('question_started_at')
                  ->nullable()
                  ->after('current_question_id')
                  ->comment('Waktu mulai mengerjakan soal aktif — untuk validasi anti-refresh exploit');
            $table->unsignedSmallInteger('cheating_warnings')
                  ->default(0)
                  ->after('question_started_at')
                  ->comment('Jumlah pelanggaran keluar/pindah layar yang tercatat selama ujian');

            // FK ke quiz_questions
            $table->foreign('current_question_id')
                  ->references('id')
                  ->on('quiz_questions')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropForeign(['current_question_id']);
            $table->dropColumn(['current_question_id', 'question_started_at', 'cheating_warnings']);
        });

        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->dropColumn('duration_seconds');
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('timer_mode');
        });
    }
};
