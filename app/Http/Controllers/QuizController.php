<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    // ===========================================================================
    // 1. HALAMAN INPUT TOKEN
    // ===========================================================================

    /**
     * Tampilkan halaman gerbang token kuis.
     */
    public function showJoin()
    {
        // Jika ada attempt aktif (belum selesai) yang tersimpan di session, redirect langsung
        $activeAttemptId = session('quiz_attempt_id');
        if ($activeAttemptId) {
            $attempt = QuizAttempt::find($activeAttemptId);
            if ($attempt && $attempt->user_id === Auth::id() && is_null($attempt->submitted_at)) {
                return redirect()->route('quiz.attempt');
            }
        }

        return view('pages.quiz.join');
    }

    /**
     * Proses validasi token dan mulai pengerjaan kuis.
     */
    public function join(Request $request)
    {
        $request->validate([
            'token' => 'required|string|size:6',
        ], [
            'token.required' => 'Token kuis wajib diisi.',
            'token.size'     => 'Token kuis harus tepat 6 karakter.',
        ]);

        $token = strtoupper(trim($request->input('token')));

        // Cari kuis aktif dengan token tersebut
        $quiz = Quiz::where('token', $token)->where('is_active', true)->first();

        if (!$quiz) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['token' => 'Token kuis tidak valid atau kuis belum diaktifkan oleh guru. Periksa kembali token yang Anda masukkan.']);
        }

        $userId = Auth::id();

        // Cek apakah siswa sudah pernah mengerjakan kuis ini
        $existingAttempt = QuizAttempt::where('user_id', $userId)
            ->where('quiz_id', $quiz->id)
            ->first();

        // Jika sudah submit → redirect ke hasil
        if ($existingAttempt && !is_null($existingAttempt->submitted_at)) {
            return redirect()->route('quiz.result', $existingAttempt)
                ->with('info', 'Anda sudah menyelesaikan kuis ini sebelumnya. Berikut hasil yang Anda peroleh.');
        }

        // Jika sedang dalam pengerjaan (belum submit)
        if ($existingAttempt && is_null($existingAttempt->submitted_at)) {
            session(['quiz_attempt_id' => $existingAttempt->id]);
            return redirect()->route('quiz.attempt');
        }

        // Buat attempt baru
        $firstQuestion = $quiz->questions()->orderBy('sort_order')->first();

        $attempt = QuizAttempt::create([
            'user_id'             => $userId,
            'quiz_id'             => $quiz->id,
            'score'               => 0,
            'answers'             => [],
            'started_at'          => now(),
            'current_question_id' => $quiz->timer_mode === 'question' ? ($firstQuestion?->id) : null,
            'question_started_at' => $quiz->timer_mode === 'question' ? now() : null,
            'cheating_warnings'   => 0,
        ]);

        session(['quiz_attempt_id' => $attempt->id]);

        return redirect()->route('quiz.attempt');
    }

    // ===========================================================================
    // 2. HALAMAN LEMBAR UJIAN
    // ===========================================================================

    /**
     * Tampilkan lembar kuis yang sedang dikerjakan siswa.
     */
    public function showAttempt()
    {
        $attempt = $this->getActiveAttempt();
        if (!$attempt) {
            return redirect()->route('quiz.join')
                ->with('error', 'Sesi kuis tidak ditemukan. Silakan masukkan token kuis untuk memulai.');
        }

        $quiz = $attempt->quiz;
        $questions = $quiz->questions; // ordered by sort_order

        // -----------------------------------------------------------------------
        // MODE GLOBAL: Semua soal tampil sekaligus, satu timer global
        // -----------------------------------------------------------------------
        if ($quiz->timer_mode === 'quiz') {
            // Hitung sisa detik berbasis waktu server
            $expiredAt       = $attempt->started_at->addMinutes($quiz->duration_minutes);
            $remainingSeconds = (int) now()->diffInSeconds($expiredAt, false);

            // Jika waktu sudah habis → auto submit
            if ($remainingSeconds <= 0) {
                return $this->autoSubmit($attempt);
            }

            return view('pages.quiz.attempt', compact('attempt', 'quiz', 'questions', 'remainingSeconds'));
        }

        // -----------------------------------------------------------------------
        // MODE PER-SOAL: Satu soal per halaman, timer per soal
        // -----------------------------------------------------------------------
        $currentQuestion = $attempt->current_question_id
            ? QuizQuestion::find($attempt->current_question_id)
            : $questions->first();

        if (!$currentQuestion) {
            // Semua soal sudah selesai → submit
            return $this->autoSubmit($attempt);
        }

        // Hitung sisa detik untuk soal aktif berdasarkan waktu server
        $questionStartedAt   = $attempt->question_started_at ?? now();
        $remainingSeconds     = (int) ($currentQuestion->duration_seconds - now()->diffInSeconds($questionStartedAt, false));

        // Jika waktu per-soal sudah habis di sisi server → skip soal ini
        if ($remainingSeconds <= 0) {
            return $this->advanceToNextQuestion($attempt, $currentQuestion, null);
        }

        // Hitung nomor soal saat ini (posisi dari keseluruhan soal)
        $questionNumber = $questions->search(fn($q) => $q->id === $currentQuestion->id) + 1;
        $totalQuestions = $questions->count();

        return view('pages.quiz.attempt', compact(
            'attempt', 'quiz', 'currentQuestion',
            'questionNumber', 'totalQuestions', 'remainingSeconds'
        ));
    }

    /**
     * Submit jawaban (satu soal, atau seluruh kuis).
     */
    public function submitAnswer(Request $request)
    {
        $attempt = $this->getActiveAttempt();
        if (!$attempt) {
            return redirect()->route('quiz.join')->with('error', 'Sesi kuis tidak valid.');
        }

        // Guard: jika kuis sudah pernah disubmit (double-submit), langsung ke halaman hasil
        if (!is_null($attempt->submitted_at)) {
            session()->forget('quiz_attempt_id');
            return redirect()->route('quiz.result', $attempt);
        }

        $quiz = $attempt->quiz;

        // -----------------------------------------------------------------------
        // MODE GLOBAL: Submit semua jawaban sekaligus
        // -----------------------------------------------------------------------
        if ($quiz->timer_mode === 'quiz') {
            $rawAnswers = $request->input('answers', []);
            // Validasi batas waktu di server (grace period 20 detik)
            $expiredAt = $attempt->started_at->addMinutes($quiz->duration_minutes)->addSeconds(20);
            if (now()->greaterThan($expiredAt)) {
                // Waktu sudah melewati grace period — simpan jawaban apa adanya
            }
            return $this->finalizeAttempt($attempt, $rawAnswers);
        }

        // -----------------------------------------------------------------------
        // MODE PER-SOAL: Submit jawaban satu soal, pindah ke soal berikutnya
        // -----------------------------------------------------------------------
        $currentQuestion = QuizQuestion::find($attempt->current_question_id);
        if (!$currentQuestion) {
            return $this->finalizeAttempt($attempt, $attempt->answers ?? []);
        }

        $selectedOption = $request->input('answer'); // 'a', 'b', 'c', atau 'd'
        return $this->advanceToNextQuestion($attempt, $currentQuestion, $selectedOption);
    }

    // ===========================================================================
    // 3. ENDPOINT AJAX: CATAT PELANGGARAN CURANG
    // ===========================================================================

    /**
     * Menambah counter peringatan curang secara real-time via AJAX.
     */
    public function recordWarning(Request $request)
    {
        $attempt = $this->getActiveAttempt();
        if (!$attempt || !is_null($attempt->submitted_at)) {
            return response()->json(['status' => 'ignored'], 400);
        }

        DB::table('quiz_attempts')
            ->where('id', $attempt->id)
            ->increment('cheating_warnings');

        return response()->json([
            'status'   => 'recorded',
            'warnings' => $attempt->fresh()->cheating_warnings,
        ]);
    }

    // ===========================================================================
    // 4. HALAMAN HASIL KUIS
    // ===========================================================================

    /**
     * Tampilkan halaman hasil akhir kuis.
     */
    public function showResult(QuizAttempt $quizAttempt)
    {
        // Keamanan: pastikan attempt milik user yang sedang login
        abort_if($quizAttempt->user_id !== Auth::id(), 403);

        // Pastikan kuis sudah diselesaikan
        if (is_null($quizAttempt->submitted_at)) {
            return redirect()->route('quiz.attempt');
        }

        $quiz          = $quizAttempt->quiz;
        $totalQuestions = $quiz->questions->count();
        $score         = (float) $quizAttempt->score;

        // Hitung jumlah jawaban benar dari snapshot answers
        $correctCount = 0;
        $answers      = $quizAttempt->answers ?? [];
        foreach ($quiz->questions as $question) {
            if (($answers[$question->id] ?? null) === $question->correct_option) {
                $correctCount++;
            }
        }

        return view('pages.quiz.result', compact(
            'quizAttempt', 'quiz', 'score', 'totalQuestions', 'correctCount'
        ));
    }

    // ===========================================================================
    // PRIVATE HELPERS
    // ===========================================================================

    /**
     * Ambil attempt aktif milik user yang sedang login dari session/DB.
     */
    private function getActiveAttempt(): ?QuizAttempt
    {
        $attemptId = session('quiz_attempt_id');
        if (!$attemptId) return null;

        $attempt = QuizAttempt::with(['quiz.questions'])->find($attemptId);
        if (!$attempt || $attempt->user_id !== Auth::id()) return null;

        return $attempt;
    }

    /**
     * Simpan jawaban soal saat ini, pindah ke soal berikutnya.
     * Jika tidak ada soal berikutnya → finalize kuis.
     */
    private function advanceToNextQuestion(QuizAttempt $attempt, QuizQuestion $current, ?string $selectedOption)
    {
        // Simpan jawaban soal saat ini
        $answers = $attempt->answers ?? [];
        if (!is_null($selectedOption)) {
            $answers[$current->id] = $selectedOption;
        }

        // Cari soal berikutnya berdasarkan sort_order
        $nextQuestion = QuizQuestion::where('quiz_id', $attempt->quiz_id)
            ->where('sort_order', '>', $current->sort_order)
            ->orderBy('sort_order')
            ->first();

        if ($nextQuestion) {
            $attempt->update([
                'answers'             => $answers,
                'current_question_id' => $nextQuestion->id,
                'question_started_at' => now(),
            ]);
            return redirect()->route('quiz.attempt');
        }

        // Tidak ada soal berikutnya → finalize
        return $this->finalizeAttempt($attempt, $answers);
    }

    /**
     * Finalize attempt: hitung skor, simpan ke DB, bersihkan session.
     */
    private function finalizeAttempt(QuizAttempt $attempt, array $answers)
    {
        // Simpan semua jawaban
        $attempt->answers = $answers;
        $attempt->save();

        // Hitung skor
        $score = $attempt->calculateScore();

        $attempt->update([
            'score'        => $score,
            'submitted_at' => now(),
        ]);

        // Bersihkan session kuis
        session()->forget('quiz_attempt_id');

        return redirect()->route('quiz.result', $attempt)
            ->with('success', 'Kuis berhasil diselesaikan! Lihat nilai Anda di bawah.');
    }

    /**
     * Auto-submit saat waktu habis (dipanggil dari server).
     */
    private function autoSubmit(QuizAttempt $attempt)
    {
        return $this->finalizeAttempt($attempt, $attempt->answers ?? []);
    }
}
