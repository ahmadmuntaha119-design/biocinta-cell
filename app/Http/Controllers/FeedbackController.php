<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Simpan umpan balik siswa baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => ['required', 'string', 'in:bug,suggestion,appreciation'],
            'message'  => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'category.required' => 'Kategori umpan balik wajib dipilih.',
            'category.in'       => 'Kategori umpan balik tidak valid.',
            'message.required'  => 'Isi umpan balik wajib diisi.',
            'message.min'       => 'Umpan balik minimal 10 karakter agar lebih informatif.',
            'message.max'       => 'Umpan balik maksimal 2000 karakter.',
        ]);

        Feedback::create([
            'user_id'  => auth()->id(),
            'category' => $validated['category'],
            'message'  => $validated['message'],
        ]);

        return redirect()->back()
            ->with('success', 'Terima kasih atas umpan balik Anda! Masukan Anda sangat berarti bagi pengembangan BioCinta Cell. 🌟');
    }
}
