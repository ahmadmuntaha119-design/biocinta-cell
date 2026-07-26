<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;

class AdminFeedbackController extends Controller
{
    /**
     * Tampilkan seluruh umpan balik dari siswa (terbaru dahulu).
     */
    public function index()
    {
        $feedbacks = Feedback::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.feedbacks.index', compact('feedbacks'));
    }

    /**
     * Hapus masukan umpan balik siswa.
     */
    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return redirect()->route('admin.feedbacks.index')
            ->with('success', 'Umpan balik berhasil dihapus.');
    }
}
