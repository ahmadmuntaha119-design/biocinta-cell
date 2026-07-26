<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'score',
        'answers',
        'started_at',
        'submitted_at',
        'current_question_id',
        'question_started_at',
        'cheating_warnings',
    ];

    protected function casts(): array
    {
        return [
            'answers'             => 'array',
            'score'               => 'decimal:2',
            'started_at'          => 'datetime',
            'submitted_at'        => 'datetime',
            'question_started_at' => 'datetime',
            'cheating_warnings'   => 'integer',
        ];
    }

    // ----------------------------------------------------------------
    // Helper
    // ----------------------------------------------------------------

    /**
     * Hitung skor kuis berdasarkan jawaban siswa vs kunci jawaban soal.
     * Skor = (jumlah benar / total soal) * 100
     */
    public function calculateScore(): float
    {
        $questions   = $this->quiz->questions;
        $totalSoal   = $questions->count();

        if ($totalSoal === 0) {
            return 0;
        }

        $answers     = $this->answers ?? [];
        $benar       = 0;

        foreach ($questions as $question) {
            $jawabanSiswa = $answers[$question->id] ?? null;
            if ($jawabanSiswa === $question->correct_option) {
                $benar++;
            }
        }

        return round(($benar / $totalSoal) * 100, 2);
    }

    /**
     * Total skor kuis yang berkontribusi ke leaderboard untuk satu siswa.
     */
    public static function totalQuizScore(int $userId): float
    {
        return (float) static::where('user_id', $userId)
            ->whereNotNull('submitted_at')
            ->sum('score');
    }

    // ----------------------------------------------------------------
    // Relationships
    // ----------------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
}
