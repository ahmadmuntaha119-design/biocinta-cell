<?php

namespace App\Http\Controllers;

use App\Models\CollaborativeProject;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Task 4.3: ProjectController — Pengumpulan Projek Kolaboratif
 * Mengelola form upload link video projek kelompok (YouTube / Google Drive).
 */
class ProjectController extends Controller
{
    /**
     * Tampilkan form + daftar projek yang sudah dikumpulkan siswa ini.
     */
    public function index(): View
    {
        $projects = CollaborativeProject::where('user_id', auth()->id())
            ->latest()
            ->get();

        // Semua proyek dari seluruh siswa untuk ditampilkan di tab Galeri
        $galleryProjects = CollaborativeProject::with('user')
            ->latest()
            ->get();

        // Data LKPD dari settings
        $lkpdFile  = Setting::get('project_lkpd_file');
        $lkpdTitle = Setting::get('project_lkpd_title');
        $lkpdDesc  = Setting::get('project_lkpd_desc');

        return view('pages.projects.index', compact(
            'projects', 'galleryProjects',
            'lkpdFile', 'lkpdTitle', 'lkpdDesc'
        ));
    }

    /**
     * Simpan projek baru.
     * Validasi URL ketat: hanya menerima domain YouTube dan Google Drive
     * sesuai spec: regex pattern untuk youtube.com, youtu.be, drive.google.com
     */
    public function store(Request $request): RedirectResponse
    {
        $youtubePattern  = 'regex:/^https?:\/\/(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/shorts\/)[a-zA-Z0-9_\-]+/';
        $drivePattern    = 'regex:/^https?:\/\/drive\.google\.com\/(file\/d\/|open\?id=|drive\/folders\/)[a-zA-Z0-9_\-]+/';

        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:150', 'min:5'],
            'description' => ['required', 'string', 'min:20', 'max:1000'],
            'video_url'   => [
                'required',
                'url',
                "regex:/^https?:\/\/(www\.)?(youtube\.com|youtu\.be|drive\.google\.com)\//",
            ],
        ], [
            'title.required'       => 'Judul projek wajib diisi.',
            'title.min'            => 'Judul projek minimal 5 karakter.',
            'description.required' => 'Deskripsi projek wajib diisi.',
            'description.min'      => 'Deskripsi minimal 20 karakter — ceritakan proyekmu dengan detail!',
            'video_url.required'   => 'Link video projek wajib diisi.',
            'video_url.url'        => 'Format URL tidak valid. Pastikan dimulai dengan https://',
            'video_url.regex'      => 'URL hanya diterima dari YouTube (youtube.com / youtu.be) atau Google Drive (drive.google.com). Periksa kembali link videomu.',
        ]);

        CollaborativeProject::create([
            'user_id'     => auth()->id(),
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'video_url'   => $validated['video_url'],
            'grade'            => null,   // Akan diisi guru via Admin Dashboard
            'teacher_feedback' => null,   // Akan diisi guru via Admin Dashboard
        ]);

        return redirect()->route('projects.index')
            ->with('success', 'Projek kelompokmu berhasil dikumpulkan!');
    }
}
