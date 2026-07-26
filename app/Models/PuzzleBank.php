<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class PuzzleBank extends Model
{
    use HasFactory;

    protected $fillable = [
        'publish_date',
        'target_text_1',
        'correct_organelle_1',
        'target_text_2',
        'correct_organelle_2',
        'target_text_3',
        'correct_organelle_3',
        'target_text_4',
        'correct_organelle_4',
    ];

    protected function casts(): array
    {
        return [
            'publish_date' => 'date',
        ];
    }

    // ----------------------------------------------------------------
    // Static Helper
    // ----------------------------------------------------------------

    /**
     * Ambil soal puzzle yang aktif hari ini berdasarkan tanggal server.
     * Mengembalikan null jika guru belum menjadwalkan soal untuk hari ini.
     */
    public static function todaysPuzzle(): ?self
    {
        return static::where('publish_date', Carbon::today()->toDateString())->first();
    }

    /**
     * Kembalikan 4 target sebagai array terstruktur untuk konsumsi Alpine.js / Blade.
     */
    public function getTargetsArray(): array
    {
        return [
            ['id' => 1, 'text' => $this->target_text_1, 'correctName' => $this->correct_organelle_1],
            ['id' => 2, 'text' => $this->target_text_2, 'correctName' => $this->correct_organelle_2],
            ['id' => 3, 'text' => $this->target_text_3, 'correctName' => $this->correct_organelle_3],
            ['id' => 4, 'text' => $this->target_text_4, 'correctName' => $this->correct_organelle_4],
        ];
    }

    /**
     * Kembalikan 4 organel (acak urutan) sebagai array untuk pilihan jawaban.
     */
    public function getShuffledOrganellesArray(): array
    {
        $organelles = [
            ['id' => 101, 'name' => $this->correct_organelle_1],
            ['id' => 102, 'name' => $this->correct_organelle_2],
            ['id' => 103, 'name' => $this->correct_organelle_3],
            ['id' => 104, 'name' => $this->correct_organelle_4],
        ];
        shuffle($organelles);
        return $organelles;
    }

    // ----------------------------------------------------------------
    // Relationships
    // ----------------------------------------------------------------

    public function scores(): HasMany
    {
        return $this->hasMany(PuzzleScore::class);
    }
}
