<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Phase 4 CRUD: Tabel koordinat hotspot klik organel pada diagram sel.
     * Setiap baris mewakili satu titik klik yang dapat dikonfigurasi guru
     * dari Admin Panel (visual clickpoint picker).
     */
    public function up(): void
    {
        Schema::create('material_hotspots', function (Blueprint $table) {
            $table->id();

            $table->foreignId('material_id')
                  ->constrained('materials')
                  ->cascadeOnDelete()
                  ->comment('FK ke tabel materials — hotspot ikut terhapus jika organel dihapus');

            $table->string('left_percent', 10)
                  ->comment('Posisi horizontal hotspot dalam persen, misal: 49%');

            $table->string('top_percent', 10)
                  ->comment('Posisi vertikal hotspot dalam persen, misal: 40%');

            $table->string('bg_color', 20)->default('#064e3b')
                  ->comment('Warna latar tombol hotspot, misal: #4f46e5');

            $table->string('delay', 10)->default('0s')
                  ->comment('Delay animasi ping dalam CSS, misal: 0.4s');

            $table->timestamps();

            // Index untuk mempercepat query eager-load per material
            $table->index('material_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_hotspots');
    }
};
