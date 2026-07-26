<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class PuzzleScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'puzzle_bank_id',
        'score',
        'earned_points',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
        ];
    }

    // ----------------------------------------------------------------
    // Static Helper: Daily Guard
    // ----------------------------------------------------------------

    /**
     * Cek apakah siswa sudah pernah mengerjakan puzzle hari ini.
     * Digunakan di PuzzleController::submitScore() sebagai gatekeeper.
     */
    public static function hasAttemptedToday(int $userId): bool
    {
        return static::where('user_id', $userId)
            ->whereDate('completed_at', Carbon::today())
            ->exists();
    }

    /**
     * Hitung total earned_points yang berkontribusi ke leaderboard untuk satu siswa.
     * (Hanya earned_points > 0 yang dihitung, alias hanya percobaan pertama per hari)
     */
    public static function totalEarnedPoints(int $userId): int
    {
        return (int) static::where('user_id', $userId)->sum('earned_points');
    }

    // ----------------------------------------------------------------
    // Relationships
    // ----------------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function puzzleBank(): BelongsTo
    {
        return $this->belongsTo(PuzzleBank::class);
    }
}
