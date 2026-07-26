<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PuzzleController;
use App\Http\Controllers\ReflectionController;
use App\Http\Controllers\VirtualLabController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\Admin\AdminMaterialController;
use App\Http\Controllers\Admin\AdminProjectController;
use App\Http\Controllers\Admin\AdminPuzzleController;
use App\Http\Controllers\Admin\AdminLabController;
use App\Http\Controllers\Admin\AdminStudentController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\Admin\AdminLeaderboardController;
use App\Http\Controllers\Admin\AdminQuizController;
use App\Http\Controllers\Admin\AdminQuizQuestionController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Admin\AdminFeedbackController;
use Illuminate\Support\Facades\Route;

// ============================================================
// ROUTE PUBLIK: Hanya untuk tamu (belum login)
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ============================================================
// ROUTE PROTEKSI TAHAP 1: Wajib Login (Ubah Password Default)
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/change-password', [PasswordController::class, 'edit'])->name('password.change');
    Route::post('/change-password', [PasswordController::class, 'update'])->name('password.update');
});

// ============================================================
// ROUTE PROTEKSI TAHAP 2: Wajib Login + Sudah Ganti Password
// ============================================================
Route::middleware(['auth', 'verified.password'])->group(function () {

    // MODULE 2: Central Homepage Dashboard
    Route::get('/', [PageController::class, 'home'])->name('home');

    // MODULE 3: Interactive Material (Sel & Mikroskop)
    Route::get('/materials/microscope', [MaterialController::class, 'microscope'])->name('materials.microscope');
    Route::get('/materials/{slug}', [MaterialController::class, 'show'])->name('materials.show');
    Route::post('/materials/{id}/feedback', [MaterialController::class, 'storeFeedback'])->name('materials.feedback');

    // MODULE 4: Virtual Lab — Phase 5 Task 5.1 ✅
    Route::get('/virtual-lab', [VirtualLabController::class, 'index'])->name('labs.index');

    // MODULE 5: Collaborative Project Space
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');

    // MODULE 6: Islamic Reflection
    Route::get('/reflection', [ReflectionController::class, 'create'])->name('reflection.create');
    Route::post('/reflection', [ReflectionController::class, 'store'])->name('reflection.store');

    // MODULE 7: Daily Puzzle Game
    Route::get('/puzzle', [PuzzleController::class, 'index'])->name('puzzle.index');
    Route::post('/api/puzzle/submit', [PuzzleController::class, 'submitScore'])->name('puzzle.submit');

    // MODULE 8: Leaderboard — Fase 7
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');

    // MODULE: Quiz — Fase 6
    Route::get('/quiz/join', [QuizController::class, 'showJoin'])->name('quiz.join');
    Route::post('/quiz/join', [QuizController::class, 'join']);
    Route::get('/quiz/attempt', [QuizController::class, 'showAttempt'])->name('quiz.attempt');
    Route::post('/quiz/submit', [QuizController::class, 'submitAnswer'])->name('quiz.submit');
    Route::post('/api/quiz/record-warning', [QuizController::class, 'recordWarning'])->name('quiz.record-warning');
    Route::get('/quiz/result/{quizAttempt}', [QuizController::class, 'showResult'])->name('quiz.result');

    // MODULE: Umpan Balik / Feedback Website
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

    // Halaman Tentang Kami / About Us
    Route::get('/about', [PageController::class, 'about'])->name('about');
});

// ============================================================
// AREA ADMIN: auth + verified.password + role:admin
// ============================================================
Route::middleware(['auth', 'verified.password', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // Manajemen Materi — Phase 4 ✅
    Route::resource('materials', AdminMaterialController::class);

    // Penilaian Projek Kolaboratif — Phase 4 ✅
    Route::get('projects', [AdminProjectController::class, 'index'])->name('projects.index');
    Route::post('projects/lkpd', [AdminProjectController::class, 'updateLkpd'])->name('projects.lkpd.update');
    Route::post('projects/{project}/grade', [AdminProjectController::class, 'grade'])->name('projects.grade');
    Route::delete('projects/{project}', [AdminProjectController::class, 'destroy'])->name('projects.destroy');

    // Bank Soal Puzzle — Phase 4 ✅
    Route::resource('puzzles', AdminPuzzleController::class);

    // Jurnal Refleksi Siswa — Phase 4 UX Improvement
    Route::get('reflections', [\App\Http\Controllers\Admin\AdminReflectionController::class, 'index'])->name('reflections.index');
    Route::get('reflections/export', [\App\Http\Controllers\Admin\AdminReflectionController::class, 'export'])->name('reflections.export');

    // Lab Virtual — Phase 5 Task 5.1 CRUD ✅
    Route::resource('lab', AdminLabController::class)->names([
        'index'   => 'lab.index',
        'create'  => 'lab.create',
        'store'   => 'lab.store',
        'show'    => 'lab.show',
        'edit'    => 'lab.edit',
        'update'  => 'lab.update',
        'destroy' => 'lab.destroy',
    ]);

    // Manajemen Siswa — Fase 8
    Route::get('students/template', [AdminStudentController::class, 'downloadTemplate'])->name('students.template');
    Route::post('students/import', [AdminStudentController::class, 'import'])->name('students.import');
    Route::post('students/{user}/reset-password', [AdminStudentController::class, 'resetPassword'])->name('students.reset-password');
    Route::delete('students/bulk', [AdminStudentController::class, 'bulkDestroy'])->name('students.bulk-destroy');
    Route::resource('students', AdminStudentController::class)->except(['show']);

    // Leaderboard Full + Export — Fase 7
    Route::get('leaderboard', [AdminLeaderboardController::class, 'index'])->name('leaderboard.index');
    Route::get('leaderboard/export', [AdminLeaderboardController::class, 'export'])->name('leaderboard.export');

    // Panel Kuis — Fase 8
    Route::post('quizzes/{quiz}/toggle', [AdminQuizController::class, 'toggle'])->name('quizzes.toggle');
    Route::resource('quizzes', AdminQuizController::class);
    Route::resource('quizzes.questions', AdminQuizQuestionController::class)->shallow();

    // Umpan Balik Siswa — Fitur Baru
    Route::resource('feedbacks', AdminFeedbackController::class)->only(['index', 'destroy']);

    // Pengaturan Situs (Google Form, dsb.)
    Route::get('settings', [\App\Http\Controllers\Admin\AdminSettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [\App\Http\Controllers\Admin\AdminSettingController::class, 'update'])->name('settings.update');
});
