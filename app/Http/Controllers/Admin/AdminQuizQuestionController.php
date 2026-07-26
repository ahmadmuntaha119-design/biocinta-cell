<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;

/**
 * Task 8.2: AdminQuizQuestionController — Manajemen Soal Pilihan Ganda
 * Mengelola pembuatan, modifikasi, dan penghapusan soal kuis di bawah suatu kuis (nested).
 */
class AdminQuizQuestionController extends Controller
{
    /**
     * Tampilkan formulir tambah soal baru untuk kuis tertentu.
     */
    public function create(Quiz $quiz)
    {
        return view('admin.questions.create', compact('quiz'));
    }

    /**
     * Simpan soal baru ke database.
     */
    public function store(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'question_text'    => ['required', 'string', 'min:5'],
            'option_a'         => ['required', 'string', 'max:500'],
            'option_b'         => ['required', 'string', 'max:500'],
            'option_c'         => ['required', 'string', 'max:500'],
            'option_d'         => ['required', 'string', 'max:500'],
            'correct_option'   => ['required', 'string', 'in:a,b,c,d'],
            'sort_order'       => ['nullable', 'integer', 'min:0'],
            'duration_seconds' => ['nullable', 'integer', 'min:5', 'max:3600'],
        ], [
            'question_text.required' => 'Pertanyaan wajib diisi.',
            'question_text.min'      => 'Pertanyaan minimal 5 karakter.',
            'option_a.required'      => 'Pilihan A wajib diisi.',
            'option_b.required'      => 'Pilihan B wajib diisi.',
            'option_c.required'      => 'Pilihan C wajib diisi.',
            'option_d.required'      => 'Pilihan D wajib diisi.',
            'correct_option.required'=> 'Kunci jawaban wajib dipilih.',
            'correct_option.in'      => 'Kunci jawaban tidak valid.',
            'sort_order.integer'     => 'Urutan harus berupa angka.',
            'duration_seconds.min'   => 'Batas waktu minimal 5 detik.',
            'duration_seconds.max'   => 'Batas waktu maksimal 3600 detik (60 menit).',
        ]);

        QuizQuestion::create([
            'quiz_id'          => $quiz->id,
            'question_text'    => $validated['question_text'],
            'option_a'         => $validated['option_a'],
            'option_b'         => $validated['option_b'],
            'option_c'         => $validated['option_c'],
            'option_d'         => $validated['option_d'],
            'correct_option'   => $validated['correct_option'],
            'sort_order'       => $validated['sort_order'] ?? 0,
            'duration_seconds' => $validated['duration_seconds'] ?? 30,
        ]);

        return redirect()->route('admin.quizzes.show', $quiz)
            ->with('success', 'Soal baru berhasil ditambahkan.');
    }

    /**
     * Tampilkan formulir edit soal.
     */
    public function edit(QuizQuestion $question)
    {
        $question->load('quiz');
        return view('admin.questions.edit', compact('question'));
    }

    /**
     * Perbarui data soal di database.
     */
    public function update(Request $request, QuizQuestion $question)
    {
        $validated = $request->validate([
            'question_text'    => ['required', 'string', 'min:5'],
            'option_a'         => ['required', 'string', 'max:500'],
            'option_b'         => ['required', 'string', 'max:500'],
            'option_c'         => ['required', 'string', 'max:500'],
            'option_d'         => ['required', 'string', 'max:500'],
            'correct_option'   => ['required', 'string', 'in:a,b,c,d'],
            'sort_order'       => ['nullable', 'integer', 'min:0'],
            'duration_seconds' => ['nullable', 'integer', 'min:5', 'max:3600'],
        ], [
            'question_text.required' => 'Pertanyaan wajib diisi.',
            'question_text.min'      => 'Pertanyaan minimal 5 karakter.',
            'option_a.required'      => 'Pilihan A wajib diisi.',
            'option_b.required'      => 'Pilihan B wajib diisi.',
            'option_c.required'      => 'Pilihan C wajib diisi.',
            'option_d.required'      => 'Pilihan D wajib diisi.',
            'correct_option.required'=> 'Kunci jawaban wajib dipilih.',
            'correct_option.in'      => 'Kunci jawaban tidak valid.',
            'sort_order.integer'     => 'Urutan harus berupa angka.',
            'duration_seconds.min'   => 'Batas waktu minimal 5 detik.',
            'duration_seconds.max'   => 'Batas waktu maksimal 3600 detik (60 menit).',
        ]);

        $question->update([
            'question_text'    => $validated['question_text'],
            'option_a'         => $validated['option_a'],
            'option_b'         => $validated['option_b'],
            'option_c'         => $validated['option_c'],
            'option_d'         => $validated['option_d'],
            'correct_option'   => $validated['correct_option'],
            'sort_order'       => $validated['sort_order'] ?? 0,
            'duration_seconds' => $validated['duration_seconds'] ?? 30,
        ]);

        return redirect()->route('admin.quizzes.show', $question->quiz_id)
            ->with('success', 'Soal berhasil diperbarui.');
    }

    /**
     * Hapus soal dari database.
     */
    public function destroy(QuizQuestion $question)
    {
        $quizId = $question->quiz_id;
        $question->delete();

        return redirect()->route('admin.quizzes.show', $quizId)
            ->with('success', 'Soal berhasil dihapus.');
    }
}
