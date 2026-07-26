<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollaborativeProject;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * AdminProjectController — Phase 4
 *
 * Menampilkan daftar semua proyek kolaboratif siswa dan memungkinkan
 * guru memberikan nilai (0-100) + catatan evaluasi per proyek.
 * Juga mengelola upload file LKPD (Lembar Kerja Peserta Didik).
 *
 * Routes: GET /admin/projects | POST /admin/projects/{project}/grade
 *         POST /admin/projects/lkpd
 */
class AdminProjectController extends Controller
{
    /**
     * Tampilkan semua proyek kolaboratif dari seluruh siswa.
     * GET /admin/projects
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->get('status', 'all');

        $query = CollaborativeProject::with('user')
            ->latest();

        if ($statusFilter === 'graded') {
            $query->whereNotNull('grade');
        } elseif ($statusFilter === 'ungraded') {
            $query->whereNull('grade');
        }

        $projects = $query->paginate(20)->withQueryString();

        $ungradedCount = CollaborativeProject::whereNull('grade')->count();
        $totalCount    = CollaborativeProject::count();

        // Data LKPD dari settings
        $lkpdFile  = Setting::get('project_lkpd_file');
        $lkpdTitle = Setting::get('project_lkpd_title');
        $lkpdDesc  = Setting::get('project_lkpd_desc');

        return view('admin.projects.index', compact(
            'projects', 'ungradedCount', 'totalCount', 'statusFilter',
            'lkpdFile', 'lkpdTitle', 'lkpdDesc'
        ));
    }

    /**
     * Upload/update file LKPD (PDF) beserta judul dan deskripsi.
     * POST /admin/projects/lkpd
     */
    public function updateLkpd(Request $request): RedirectResponse
    {
        $request->validate([
            'lkpd_title' => ['required', 'string', 'max:200'],
            'lkpd_desc'  => ['nullable', 'string', 'max:2000'],
            'lkpd_file'  => ['nullable', 'file', 'mimes:pdf', 'max:10240'], // max 10 MB
        ], [
            'lkpd_title.required' => 'Judul LKPD wajib diisi.',
            'lkpd_file.mimes'     => 'File LKPD harus berformat PDF.',
            'lkpd_file.max'       => 'Ukuran file LKPD maksimal 10 MB.',
        ]);

        // Simpan judul & deskripsi
        Setting::set('project_lkpd_title', $request->input('lkpd_title'));
        Setting::set('project_lkpd_desc', $request->input('lkpd_desc'));

        // Proses file PDF jika diunggah
        if ($request->hasFile('lkpd_file')) {
            // Hapus file lama jika ada
            $oldFile = Setting::get('project_lkpd_file');
            if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                Storage::disk('public')->delete($oldFile);
            }

            // Simpan file baru
            $path = $request->file('lkpd_file')->store('lkpd', 'public');
            Setting::set('project_lkpd_file', $path);
        }

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'LKPD & Petunjuk Projek berhasil diperbarui.');
    }

    /**
     * Simpan penilaian guru (grade + teacher_feedback) untuk satu proyek.
     * POST /admin/projects/{project}/grade
     */
    public function grade(Request $request, CollaborativeProject $project): RedirectResponse
    {
        $validated = $request->validate([
            'grade'            => ['required', 'integer', 'min:0', 'max:100'],
            'teacher_feedback' => ['nullable', 'string', 'max:1000'],
        ], [
            'grade.required' => 'Nilai wajib diisi.',
            'grade.min'      => 'Nilai minimal 0.',
            'grade.max'      => 'Nilai maksimal 100.',
        ]);

        $project->update([
            'grade'            => $validated['grade'],
            'teacher_feedback' => $validated['teacher_feedback'] ?? null,
        ]);

        return redirect()
            ->route('admin.projects.index')
            ->with('success', "Proyek \"{$project->title}\" milik {$project->user->name} berhasil dinilai: {$validated['grade']}/100.");
    }

    /**
     * Hapus proyek kolaboratif siswa.
     * DELETE /admin/projects/{project}
     */
    public function destroy(CollaborativeProject $project): RedirectResponse
    {
        $title    = $project->title;
        $student  = $project->user->name ?? 'Siswa';
        $project->delete();

        return redirect()
            ->route('admin.projects.index')
            ->with('success', "Proyek \"{$title}\" milik {$student} berhasil dihapus.");
    }
}

