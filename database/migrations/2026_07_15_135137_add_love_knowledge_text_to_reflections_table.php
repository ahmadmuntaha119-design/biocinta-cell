<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reflections', function (Blueprint $table) {
            $table->text('love_knowledge_text')
                  ->nullable()
                  ->after('implementation_text')
                  ->comment('Cinta pada ilmu pengetahuan — refleksi keempat Kurikulum CINTA');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reflections', function (Blueprint $table) {
            $table->dropColumn('love_knowledge_text');
        });
    }
};
