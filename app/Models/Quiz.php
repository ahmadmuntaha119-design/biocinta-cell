<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'token',
        'is_active',
        'duration_minutes',
        'timer_mode',
    ];

    protected function casts(): array
    {
        return [
            'is_active'  => 'boolean',
            'timer_mode' => 'string',
        ];
    }

    // ----------------------------------------------------------------
    // Token Management
    // ----------------------------------------------------------------

    /**
     * Hasilkan token kuis: 6-digit alphanumeric UPPERCASE.
     * Contoh output: XC79W1, AB3K9Z
     */
    public static function generateToken(): string
    {
        return strtoupper(Str::random(6));
    }

    /**
     * Aktifkan kuis dan buat token baru secara atomis.
     */
    public function activate(): void
    {
        $this->update([
            'is_active' => true,
            'token'     => static::generateToken(),
        ]);
    }

    /**
     * Tutup akses kuis (tidak menghapus token).
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    // ----------------------------------------------------------------
    // Relationships
    // ----------------------------------------------------------------

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('sort_order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
