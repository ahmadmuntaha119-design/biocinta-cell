<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Task 1.4: Skema Tabel Kuis Ber-Token & Evaluasi
     */
    public function up(): void
    {
        // Kuis Utama: Dibuat guru, dilindungi token 6-digit alphanumeric
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200)->comment('Judul kuis, misal: Ulangan Harian Bab Sel');
            $table->string('token', 6)->nullable()
                  ->comment('Token 6-digit alphanumeric UPPERCASE: dihasilkan saat guru mengaktifkan kuis');
            $table->boolean('is_active')->default(false)
                  ->comment('Saklar akses kuis: true = siswa bisa masuk dengan token');
            $table->unsignedSmallInteger('duration_minutes')->default(30)
                  ->comment('Durasi pengerjaan kuis dalam menit (untuk countdown timer)');
            $table->timestamps();

            $table->index('token');
            $table->index('is_active');
        });

        // Soal Pilihan Ganda per Kuis
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete();
            $table->text('question_text')->comment('Teks pertanyaan');
            $table->string('option_a', 500)->comment('Pilihan jawaban A');
            $table->string('option_b', 500)->comment('Pilihan jawaban B');
            $table->string('option_c', 500)->comment('Pilihan jawaban C');
            $table->string('option_d', 500)->comment('Pilihan jawaban D');
            $table->enum('correct_option', ['a', 'b', 'c', 'd'])->comment('Kunci jawaban yang benar');
            $table->integer('sort_order')->default(0)->comment('Urutan tampil soal');
            $table->timestamps();

            $table->index('quiz_id');
        });

        // Rekap Pengerjaan Kuis per Siswa
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete();
            $table->decimal('score', 5, 2)->default(0)
                  ->comment('Nilai akhir kuis skala 0-100 untuk leaderboard');
            $table->json('answers')->nullable()
                  ->comment('Snapshot jawaban siswa dalam format JSON {question_id: pilihan}');
            $table->timestamp('started_at')->nullable()->comment('Waktu mulai mengerjakan kuis');
            $table->timestamp('submitted_at')->nullable()->comment('Waktu submit jawaban (null = belum submit)');
            $table->timestamps();

            // Constraint: satu siswa hanya bisa mengerjakan satu kuis sekali
            $table->unique(['user_id', 'quiz_id'], 'unique_student_quiz_attempt');
            $table->index(['user_id', 'quiz_id']);
        });

        // Jurnal Refleksi Islami (Kurikulum CINTA)
        Schema::create('reflections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('connection_text')
                  ->comment('Struktur sel apa yang paling membuatmu takjub akan kebesaran Sang Pencipta?');
            $table->text('wisdom_text')
                  ->comment('Hikmah spiritual apa yang kamu dapatkan setelah mempelajari keteraturan sel ini?');
            $table->text('implementation_text')
                  ->comment('Apa langkah nyata sebagai perwujudan rasa syukur atas keteraturan sel?');
            $table->timestamps();

            $table->index('user_id');
        });

        // Proyek Kolaboratif Kelompok/Individu
        Schema::create('collaborative_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title', 200)->comment('Judul proyek video/model sel');
            $table->text('description')->comment('Deskripsi naratif proyek kelompok');
            $table->string('video_url', 500)->comment('URL video YouTube atau Google Drive yang sudah diunggah');
            $table->unsignedTinyInteger('grade')->nullable()
                  ->comment('Nilai angka 0-100 yang diberikan guru');
            $table->text('teacher_feedback')->nullable()
                  ->comment('Catatan evaluasi kritis dari guru');
            $table->timestamps();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collaborative_projects');
        Schema::dropIfExists('reflections');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
    }
};
