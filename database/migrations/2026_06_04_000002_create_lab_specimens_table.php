<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Phase 5 — Task 5.1 CRUD:
     * Tabel lab_specimens menyimpan data spesimen Lab Virtual yang dapat
     * dikelola guru secara mandiri dari Admin Panel tanpa mengubah kode.
     */
    public function up(): void
    {
        Schema::create('lab_specimens', function (Blueprint $table) {
            $table->id();

            $table->string('name', 150)
                  ->comment('Nama tampilan spesimen, mis. "Daun (Stomata & Kloroplas)"');

            $table->string('icon', 10)->default('🔬')
                  ->comment('Emoji ikon spesimen');

            $table->string('macro_image_path', 500)->nullable()
                  ->comment('Path relatif gambar makro (WebP terkompresi), relatif dari public/storage/');

            $table->string('micro_image_path', 500)->nullable()
                  ->comment('Path relatif gambar mikro (WebP terkompresi), relatif dari public/storage/');

            $table->string('magnification', 20)->default('400×')
                  ->comment('Label perbesaran untuk tampilan, mis. "400×"');

            $table->json('structures')
                  ->comment('Array JSON struktur sel terlihat: [{name, function, icon}]');

            $table->text('quran_verse')->nullable()
                  ->comment('Ayat Al-Quran untuk panel tadabbur (Kurikulum CINTA)');

            $table->string('quran_ref', 100)->nullable()
                  ->comment('Referensi surah:ayat, mis. "QS. Al-An\'an (6): 99"');

            $table->text('reflection')->nullable()
                  ->comment('Teks renungan/tadabbur terkait spesimen');

            $table->boolean('is_active')->default(true)
                  ->comment('Jika false, spesimen tidak ditampilkan ke siswa');

            $table->unsignedSmallInteger('sort_order')->default(0)
                  ->comment('Urutan tampil di Lab Virtual (ascending)');

            $table->timestamps();

            // Index untuk query performa
            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_specimens');
    }
};
