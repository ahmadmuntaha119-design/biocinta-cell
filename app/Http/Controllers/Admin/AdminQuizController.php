<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;

/**
 * Task 8.2: AdminQuizController — Panel Kontrol Kuis & Aktivasi Token
 * Mengelola pembuatan, modifikasi, penghapusan kuis, serta aktivasi token ujian.
 */
class AdminQuizController extends Controller
{
    /**
     * Tampilkan daftar kuis.
     */
    public function index()
    {
        $quizzes = Quiz::withCount('questions')
            ->latest()
            ->paginate(10);

        return view('admin.quizzes.index', compact('quizzes'));
    }

    /**
     * Tampilkan formulir pembuatan kuis baru.
     */
    public function create()
    {
        return view('admin.quizzes.create');
    }

    /**
     * Simpan kuis baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => ['required', 'string', 'min:3', 'max:200'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:600'],
            'timer_mode'       => ['required', 'string', 'in:quiz,question'],
        ], [
            'title.required'            => 'Judul kuis wajib diisi.',
            'title.min'                 => 'Judul kuis minimal 3 karakter.',
            'duration_minutes.required' => 'Durasi pengerjaan wajib diisi.',
            'duration_minutes.integer'  => 'Durasi harus berupa angka.',
            'duration_minutes.min'      => 'Durasi minimal 1 menit.',
            'timer_mode.required'       => 'Mode timer wajib dipilih.',
            'timer_mode.in'             => 'Mode timer tidak valid.',
        ]);

        Quiz::create([
            'title'            => $validated['title'],
            'duration_minutes' => $validated['duration_minutes'],
            'timer_mode'       => $validated['timer_mode'],
            'is_active'        => false,
            'token'            => null,
        ]);

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Kuis berhasil dibuat. Silakan tambahkan soal kuis.');
    }

    /**
     * Tampilkan detail kuis beserta daftar soal pilihan ganda di dalamnya.
     */
    public function show(Quiz $quiz)
    {
        $quiz->load(['questions' => function ($q) {
            $q->orderBy('sort_order')->orderBy('id');
        }]);

        return view('admin.quizzes.show', compact('quiz'));
    }

    /**
     * Tampilkan formulir edit kuis.
     */
    public function edit(Quiz $quiz)
    {
        return view('admin.quizzes.edit', compact('quiz'));
    }

    /**
     * Perbarui data kuis di database.
     */
    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title'            => ['required', 'string', 'min:3', 'max:200'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:600'],
            'timer_mode'       => ['required', 'string', 'in:quiz,question'],
        ], [
            'title.required'            => 'Judul kuis wajib diisi.',
            'title.min'                 => 'Judul kuis minimal 3 karakter.',
            'duration_minutes.required' => 'Durasi pengerjaan wajib diisi.',
            'duration_minutes.integer'  => 'Durasi harus berupa angka.',
            'duration_minutes.min'      => 'Durasi minimal 1 menit.',
            'timer_mode.required'       => 'Mode timer wajib dipilih.',
            'timer_mode.in'             => 'Mode timer tidak valid.',
        ]);

        $quiz->update([
            'title'            => $validated['title'],
            'duration_minutes' => $validated['duration_minutes'],
            'timer_mode'       => $validated['timer_mode'],
        ]);

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Detail kuis berhasil diperbarui.');
    }

    /**
     * Hapus kuis dari database beserta soal-soalnya (cascade delete).
     */
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Kuis berhasil dihapus.');
    }

    /**
     * Toggle status aktif kuis dan buat token 6-digit baru jika diaktifkan.
     */
    public function toggle(Quiz $quiz)
    {
        if ($quiz->is_active) {
            $quiz->deactivate();
            return redirect()->back()
                ->with('success', 'Kuis "' . $quiz->title . '" berhasil dinonaktifkan.');
        } else {
            $quiz->activate();
            return redirect()->back()
                ->with('success', 'Kuis "' . $quiz->title . '" berhasil diaktifkan. Token aktif: ' . $quiz->token);
        }
    }
}
