<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PuzzleBank;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * AdminPuzzleController — Phase 4
 *
 * CRUD penuh untuk Bank Soal Puzzle Harian.
 * Guru menetapkan 4 pasang (fungsi organel + nama organel benar)
 * dan tanggal tayang. Sistem otomatis menayangkan soal di hari yang tepat.
 *
 * Routes: Route::resource('puzzles', AdminPuzzleController::class)
 * Named:  admin.puzzles.*
 */
class AdminPuzzleController extends Controller
{
    /** GET /admin/puzzles */
    public function index(): View
    {
        $puzzles = PuzzleBank::orderByDesc('publish_date')->paginate(20);
        $todayHasPuzzle = PuzzleBank::todaysPuzzle() !== null;

        return view('admin.puzzles.index', compact('puzzles', 'todayHasPuzzle'));
    }

    /** GET /admin/puzzles/create */
    public function create(): View
    {
        return view('admin.puzzles.create', ['puzzle' => new PuzzleBank()]);
    }

    /** POST /admin/puzzles */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePuzzle($request);
        PuzzleBank::create($validated);

        return redirect()
            ->route('admin.puzzles.index')
            ->with('success', "Soal puzzle untuk tanggal {$validated['publish_date']} berhasil ditambahkan.");
    }

    /** GET /admin/puzzles/{puzzle} → redirect to edit */
    public function show(PuzzleBank $puzzle): RedirectResponse
    {
        return redirect()->route('admin.puzzles.edit', $puzzle);
    }

    /** GET /admin/puzzles/{puzzle}/edit */
    public function edit(PuzzleBank $puzzle): View
    {
        return view('admin.puzzles.edit', compact('puzzle'));
    }

    /** PUT /admin/puzzles/{puzzle} */
    public function update(Request $request, PuzzleBank $puzzle): RedirectResponse
    {
        $validated = $this->validatePuzzle($request, $puzzle->id);
        $puzzle->update($validated);

        return redirect()
            ->route('admin.puzzles.index')
            ->with('success', "Soal puzzle tanggal {$validated['publish_date']} berhasil diperbarui.");
    }

    /** DELETE /admin/puzzles/{puzzle} */
    public function destroy(PuzzleBank $puzzle): RedirectResponse
    {
        $date = $puzzle->publish_date->format('d M Y');
        $puzzle->delete();

        return redirect()
            ->route('admin.puzzles.index')
            ->with('success', "Soal puzzle tanggal {$date} berhasil dihapus.");
    }

    // ----------------------------------------------------------------
    // Private helpers
    // ----------------------------------------------------------------

    private function validatePuzzle(Request $request, ?int $ignoreId = null): array
    {
        $dateRule = ['required', 'date'];
        $dateRule[] = 'unique:puzzle_banks,publish_date' . ($ignoreId ? ',' . $ignoreId : '');

        return $request->validate([
            'publish_date'       => $dateRule,
            'target_text_1'      => ['required', 'string', 'min:10'],
            'correct_organelle_1'=> ['required', 'string', 'max:100'],
            'target_text_2'      => ['required', 'string', 'min:10'],
            'correct_organelle_2'=> ['required', 'string', 'max:100'],
            'target_text_3'      => ['required', 'string', 'min:10'],
            'correct_organelle_3'=> ['required', 'string', 'max:100'],
            'target_text_4'      => ['required', 'string', 'min:10'],
            'correct_organelle_4'=> ['required', 'string', 'max:100'],
        ], [
            'publish_date.required' => 'Tanggal tayang wajib diisi.',
            'publish_date.unique'   => 'Sudah ada soal puzzle untuk tanggal ini.',
            'target_text_1.required'=> 'Deskripsi fungsi organel ke-1 wajib diisi.',
            'target_text_2.required'=> 'Deskripsi fungsi organel ke-2 wajib diisi.',
            'target_text_3.required'=> 'Deskripsi fungsi organel ke-3 wajib diisi.',
            'target_text_4.required'=> 'Deskripsi fungsi organel ke-4 wajib diisi.',
        ]);
    }
}
