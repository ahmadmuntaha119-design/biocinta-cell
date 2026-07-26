<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Task 7.3: AdminLeaderboardController — Visibilitas Peringkat Penuh 100% Sisi Admin
 * Mengelola antarmuka penilaian peringkat untuk Guru, dengan fitur pencarian dan ekspor data CSV.
 */
class AdminLeaderboardController extends Controller
{
    /**
     * Tampilkan daftar peringkat 100% siswa dengan pagination dan search.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = User::query()
            ->where('role', 'student')
            ->select('users.id', 'users.nis', 'users.name')
            // Akumulasi skor kuis utama yang sudah dikirim
            ->selectRaw('COALESCE((SELECT SUM(score) FROM quiz_attempts WHERE quiz_attempts.user_id = users.id AND submitted_at IS NOT NULL), 0) as quiz_total')
            // Akumulasi poin puzzle
            ->selectRaw('COALESCE((SELECT SUM(earned_points) FROM puzzle_scores WHERE puzzle_scores.user_id = users.id), 0) as puzzle_total')
            // Total akumulatif kuis + puzzle
            ->selectRaw('COALESCE((SELECT SUM(score) FROM quiz_attempts WHERE quiz_attempts.user_id = users.id AND submitted_at IS NOT NULL), 0) + COALESCE((SELECT SUM(earned_points) FROM puzzle_scores WHERE puzzle_scores.user_id = users.id), 0) as grand_total');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        // Urutkan berdasarkan total akumulatif terbesar, lalu alfabet nama
        $students = $query->orderByDesc('grand_total')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.leaderboard.index', compact('students', 'search'));
    }

    /**
     * Ekspor seluruh data peringkat siswa ke berkas CSV native stream (tanpa library Excel).
     */
    public function export()
    {
        $students = User::query()
            ->where('role', 'student')
            ->select('users.id', 'users.nis', 'users.name')
            ->selectRaw('COALESCE((SELECT SUM(score) FROM quiz_attempts WHERE quiz_attempts.user_id = users.id AND submitted_at IS NOT NULL), 0) as quiz_total')
            ->selectRaw('COALESCE((SELECT SUM(earned_points) FROM puzzle_scores WHERE puzzle_scores.user_id = users.id), 0) as puzzle_total')
            ->selectRaw('COALESCE((SELECT SUM(score) FROM quiz_attempts WHERE quiz_attempts.user_id = users.id AND submitted_at IS NOT NULL), 0) + COALESCE((SELECT SUM(earned_points) FROM puzzle_scores WHERE puzzle_scores.user_id = users.id), 0) as grand_total')
            ->orderByDesc('grand_total')
            ->orderBy('name')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="leaderboard_biocinta_cell_' . date('Y-m-d_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () use ($students) {
            $file = fopen('php://output', 'w');
            
            // Kirim UTF-8 BOM agar Excel membaca karakter khusus / pemisah dengan benar
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header kolom CSV
            fputcsv($file, [
                'Peringkat',
                'NIS',
                'Nama Siswa',
                'Total Nilai Kuis',
                'Total Poin Puzzle',
                'Nilai Akumulatif (Total)'
            ]);

            // Isi baris data
            foreach ($students as $index => $student) {
                fputcsv($file, [
                    $index + 1,
                    $student->nis,
                    $student->name,
                    number_format($student->quiz_total, 2, ',', '.'),
                    $student->puzzle_total,
                    number_format($student->grand_total, 2, ',', '.')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
