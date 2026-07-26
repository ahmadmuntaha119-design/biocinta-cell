<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('Nama konfigurasi, e.g. gform_url');
            $table->text('value')->nullable()->comment('Nilai konfigurasi');
            $table->string('label')->nullable()->comment('Label tampilan untuk panel admin');
            $table->timestamps();
        });

        // Seed nilai default
        DB::table('settings')->insert([
            'key'        => 'gform_url',
            'value'      => null,
            'label'      => 'Link Google Form Angket Siswa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
