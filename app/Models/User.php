<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nis',
        'name',
        'role',
        'password',
        'is_password_changed',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'password'             => 'hashed',
            'is_password_changed'  => 'boolean',
        ];
    }

    // ----------------------------------------------------------------
    // Helper Methods
    // ----------------------------------------------------------------

    /**
     * Cek apakah pengguna ini adalah admin/guru.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah pengguna ini adalah siswa.
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    // ----------------------------------------------------------------
    // Relationships
    // ----------------------------------------------------------------

    public function puzzleScores(): HasMany
    {
        return $this->hasMany(PuzzleScore::class);
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function reflections(): HasMany
    {
        return $this->hasMany(Reflection::class);
    }

    public function collaborativeProjects(): HasMany
    {
        return $this->hasMany(CollaborativeProject::class);
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }
}
