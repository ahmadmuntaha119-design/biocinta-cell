<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Task 1.2: Skema Tabel Konten Akademis
     */
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['animal', 'plant', 'microscope'])
                  ->comment('Jenis materi: sel hewan, sel tumbuhan, atau alat mikroskop');
            $table->string('title', 150);
            $table->string('slug', 150)->unique();
            $table->text('description');
            $table->string('youtube_id', 20)->nullable()
                  ->comment('ID video YouTube pendek (tanpa URL penuh)');
            $table->text('history_founder')->nullable()
                  ->comment('Sejarah singkat penemu organel/alat');
            $table->text('quran_verse')->nullable()
                  ->comment('Integrasi Nilai Islami: ayat Al-Quran terkait fungsi organel (Kurikulum CINTA)');
            $table->string('image_path', 500)->nullable()
                  ->comment('Path gambar organel/materi, disimpan dalam format WebP terkompresi');
            $table->integer('sort_order')->default(0)
                  ->comment('Urutan tampil pada diagram sel');
            $table->timestamps();

            // Index untuk query filter tipe materi
            $table->index('type');
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
