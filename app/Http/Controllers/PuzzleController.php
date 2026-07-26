<?php

namespace App\Http\Controllers;

use App\Models\PuzzleBank;
use App\Models\PuzzleScore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

/**
 * PuzzleController — Module 7: Gamification
 *
 * Menampilkan game puzzle Click-to-Match harian dan menyimpan skor.
 * Mengimplementasikan aturan bisnis:
 *   - Hanya percobaan pertama hari ini yang mencetak earned_points
 *   - Siswa tetap bisa bermain ulang untuk latihan (skor tidak tersimpan)
 */
class PuzzleController extends Controller
{
    /**
     * Tampilkan halaman game puzzle hari ini.
     * GET /puzzle
     */
    public function index(): View
    {
        $puzzle = PuzzleBank::todaysPuzzle();

        // Cek apakah siswa ini sudah bermain hari ini (untuk tampilan state)
        $alreadyPlayed = false;
        $todayScore    = null;

        if ($puzzle) {
            $todayScore = PuzzleScore::where('user_id', auth()->id())
                ->where('puzzle_bank_id', $puzzle->id)
                ->first();
            $alreadyPlayed = $todayScore !== null;
        }

        return view('pages.puzzle.index', [
            'puzzle'        => $puzzle,
            'alreadyPlayed' => $alreadyPlayed,
            'todayScore'    => $todayScore,
        ]);
    }

    /**
     * Terima dan simpan skor puzzle dari Alpine.js.
     * POST /api/puzzle/submit
     *
     * Business rule (spec §2.7.3):
     *   - Percobaan ke-1: earned_points = nilai riil (score)
     *   - Percobaan ke-2+: earned_points = 0 (latihan mandiri)
     */
    public function submitScore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'score'          => ['required', 'integer', 'in:0,25,50,75,100'],
            'puzzle_bank_id' => ['required', 'integer', 'exists:puzzle_banks,id'],
        ]);

        $puzzle = PuzzleBank::findOrFail($validated['puzzle_bank_id']);

        // Validasi: puzzle harus untuk hari ini
        if (!$puzzle->publish_date->isToday()) {
            return response()->json([
                'success' => false,
                'message' => 'Soal puzzle ini bukan untuk hari ini.',
            ], 422);
        }

        // Cek apakah sudah pernah main hari ini
        $existingAttempt = PuzzleScore::where('user_id', auth()->id())
            ->where('puzzle_bank_id', $puzzle->id)
            ->first();

        // Hitung earned_points berdasarkan urutan percobaan
        $earnedPoints = ($existingAttempt === null) ? $validated['score'] : 0;

        PuzzleScore::create([
            'user_id'        => auth()->id(),
            'puzzle_bank_id' => $puzzle->id,
            'score'          => $validated['score'],
            'earned_points'  => $earnedPoints,
            'completed_at'   => Carbon::now(),
        ]);

        if ($earnedPoints > 0) {
            $message = "Selamat! Kamu mendapat {$validated['score']} poin dari tantangan hari ini! 🎉 Poin sudah ditambahkan ke papan peringkat.";
        } elseif ($validated['score'] === 100) {
            $message = "Sempurna! Namun kamu sudah bermain hari ini, jadi skor ini tidak menambah poin leaderboard. Tetap semangat! 💪";
        } else {
            $message = "Skor kamu {$validated['score']} poin. Ini adalah percobaan latihan — skor pertama tadi sudah disimpan di leaderboard.";
        }

        return response()->json([
            'success'       => true,
            'message'       => $message,
            'score'         => $validated['score'],
            'earned_points' => $earnedPoints,
        ]);
    }
}
