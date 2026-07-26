<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

/**
 * Task 7.1 & 7.2: LeaderboardController — Papan Peringkat Global (Top 10)
 * Mengelola perolehan akumulasi nilai kuis + poin puzzle harian untuk 10 peringkat teratas.
 * Menerapkan caching 10 menit (Task 9.2) untuk performa optimal.
 */
class LeaderboardController extends Controller
{
    /**
     * Tampilkan 10 besar siswa dengan akumulasi skor tertinggi.
     */
    public function index(): View
    {
        $topStudentsRaw = Cache::remember('global_leaderboard_data', 600, function () {
            return User::query()
                ->where('role', 'student')
                ->select('users.id', 'users.nis', 'users.name')
                // Hitung total nilai kuis utama
                ->selectRaw('COALESCE((SELECT SUM(score) FROM quiz_attempts WHERE quiz_attempts.user_id = users.id AND submitted_at IS NOT NULL), 0) as quiz_total')
                // Hitung total poin puzzle harian
                ->selectRaw('COALESCE((SELECT SUM(earned_points) FROM puzzle_scores WHERE puzzle_scores.user_id = users.id), 0) as puzzle_total')
                // Hitung akumulasi grand_total
                ->selectRaw('COALESCE((SELECT SUM(score) FROM quiz_attempts WHERE quiz_attempts.user_id = users.id AND submitted_at IS NOT NULL), 0) + COALESCE((SELECT SUM(earned_points) FROM puzzle_scores WHERE puzzle_scores.user_id = users.id), 0) as grand_total')
                ->orderByDesc('grand_total')
                ->orderBy('name')
                ->take(10)
                ->get()
                ->toArray();
        });

        $topStudents = collect(json_decode(json_encode($topStudentsRaw)));

        return view('pages.leaderboard.index', compact('topStudents'));
    }
}
