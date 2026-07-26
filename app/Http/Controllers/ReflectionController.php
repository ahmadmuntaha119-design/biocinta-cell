<?php

namespace App\Http\Controllers;

use App\Models\Reflection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Task 4.3: ReflectionController — Jurnal Refleksi Islami (Kurikulum CINTA)
 * Mengelola formulir esai 4 pilar:
 *   1. Cinta kepada Allah dan Rasul (Connection)
 *   2. Cinta pada diri sendiri (Wisdom)
 *   3. Cinta pada lingkungan (Implementation)
 *   4. Cinta pada ilmu pengetahuan (Love of Knowledge)
 */
class ReflectionController extends Controller
{
    /**
     * Tampilkan form refleksi + riwayat refleksi siswa sebelumnya.
     */
    public function create(): View
    {
        // Tampilkan 5 refleksi terbaru siswa sebagai riwayat
        $pastReflections = Reflection::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        return view('pages.reflection.create', compact('pastReflections'));
    }

    /**
     * Simpan refleksi baru ke database.
     * Validasi: setiap kolom min 20 karakter agar siswa benar-benar berefleksi,
     * bukan mengisi asal-asalan.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'connection'      => ['required', 'string', 'min:20', 'max:2000'],
            'wisdom'          => ['required', 'string', 'min:20', 'max:2000'],
            'implementation'  => ['required', 'string', 'min:20', 'max:2000'],
            'love_knowledge'  => ['required', 'string', 'min:20', 'max:2000'],
        ], [
            'connection.required'     => 'Kolom "Cinta kepada Allah dan Rasul" wajib diisi.',
            'connection.min'          => 'Kolom "Cinta kepada Allah dan Rasul" minimal 20 karakter. Tuliskan refleksimu lebih mendalam.',
            'wisdom.required'         => 'Kolom "Cinta pada diri sendiri" wajib diisi.',
            'wisdom.min'              => 'Kolom "Cinta pada diri sendiri" minimal 20 karakter. Tuliskan hikmahmu lebih mendalam.',
            'implementation.required' => 'Kolom "Cinta pada lingkungan" wajib diisi.',
            'implementation.min'      => 'Kolom "Cinta pada lingkungan" minimal 20 karakter. Tuliskan rencanamu lebih spesifik.',
            'love_knowledge.required' => 'Kolom "Cinta pada ilmu pengetahuan" wajib diisi.',
            'love_knowledge.min'      => 'Kolom "Cinta pada ilmu pengetahuan" minimal 20 karakter. Tuliskan komitmenmu lebih spesifik.',
        ]);

        Reflection::create([
            'user_id'              => auth()->id(),
            'connection_text'      => $validated['connection'],
            'wisdom_text'          => $validated['wisdom'],
            'implementation_text'  => $validated['implementation'],
            'love_knowledge_text'  => $validated['love_knowledge'],
        ]);

        return redirect()->route('reflection.create')
            ->with('success', 'MasyaAllah! Jurnal refleksimu berhasil disimpan. 🌿 Semoga menjadi amal ilmu yang bermanfaat.');
    }
}
