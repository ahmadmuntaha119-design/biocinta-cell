<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LabSpecimen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

/**
 * AdminLabController
 *
 * Phase 5 — Task 5.1 CRUD: Manajemen Spesimen Laboratorium Maya.
 *
 * Guru dapat menambah spesimen baru (dengan upload gambar makro & mikro),
 * mengedit konten (nama, struktur sel, tadabbur), toggle aktif/nonaktif,
 * dan menghapus spesimen yang tidak lagi digunakan.
 *
 * Gambar diproses otomatis ke format WebP (kualitas 75%) menggunakan
 * ekstensi GD PHP bawaan — konsisten dengan AdminMaterialController.
 *
 * Route resource: GET/POST /admin/lab
 * Nama route:     admin.lab.*
 */
class AdminLabController extends Controller
{
    /**
     * Dashboard + daftar semua spesimen (paginate 10).
     * GET /admin/lab
     */
    public function index(): View
    {
        $specimens = LabSpecimen::orderBy('sort_order')->orderBy('id')->paginate(10);

        $totalActive   = LabSpecimen::where('is_active', true)->count();
        $totalInactive = LabSpecimen::where('is_active', false)->count();

        return view('admin.lab.index', compact('specimens', 'totalActive', 'totalInactive'));
    }

    /**
     * Form tambah spesimen baru.
     * GET /admin/lab/create
     */
    public function create(): View
    {
        return view('admin.lab.create');
    }

    /**
     * Simpan spesimen baru ke database.
     * POST /admin/lab
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateSpecimen($request);

        // Proses structures dari form fields → array JSON
        $validated['structures'] = $this->buildStructuresArray($request);

        // Proses upload gambar Makro
        if ($request->hasFile('macro_image')) {
            $validated['macro_image_path'] = $this->processImageUpload(
                $request->file('macro_image'),
                'macro'
            );
        }

        // Proses upload gambar Mikro
        if ($request->hasFile('micro_image')) {
            $validated['micro_image_path'] = $this->processImageUpload(
                $request->file('micro_image'),
                'micro'
            );
        }

        $specimen = LabSpecimen::create($validated);

        return redirect()
            ->route('admin.lab.index')
            ->with('success', "Spesimen \"{$specimen->name}\" berhasil ditambahkan ke Lab Virtual!");
    }

    /**
     * Redirect show → edit.
     * GET /admin/lab/{lab}
     */
    public function show(LabSpecimen $lab): RedirectResponse
    {
        return redirect()->route('admin.lab.edit', $lab);
    }

    /**
     * Form edit spesimen.
     * GET /admin/lab/{lab}/edit
     */
    public function edit(LabSpecimen $lab): View
    {
        return view('admin.lab.edit', compact('lab'));
    }

    /**
     * Update data spesimen + gambar (jika diganti).
     * PUT /admin/lab/{lab}
     */
    public function update(Request $request, LabSpecimen $lab): RedirectResponse
    {
        $validated = $this->validateSpecimen($request, $lab->id);

        // Perbarui structures
        $validated['structures'] = $this->buildStructuresArray($request);

        // Proses gambar Makro baru (jika diunggah)
        if ($request->hasFile('macro_image')) {
            $this->deleteImageFile($lab->macro_image_path);
            $validated['macro_image_path'] = $this->processImageUpload(
                $request->file('macro_image'),
                'macro'
            );
        }

        // Proses gambar Mikro baru (jika diunggah)
        if ($request->hasFile('micro_image')) {
            $this->deleteImageFile($lab->micro_image_path);
            $validated['micro_image_path'] = $this->processImageUpload(
                $request->file('micro_image'),
                'micro'
            );
        }

        $lab->update($validated);

        return redirect()
            ->route('admin.lab.index')
            ->with('success', "Spesimen \"{$lab->name}\" berhasil diperbarui.");
    }

    /**
     * Hapus spesimen + file gambarnya dari storage.
     * DELETE /admin/lab/{lab}
     */
    public function destroy(LabSpecimen $lab): RedirectResponse
    {
        $name = $lab->name;

        // Hapus file gambar dari storage (bukan path legacy /images/lab/)
        $this->deleteImageFile($lab->macro_image_path);
        $this->deleteImageFile($lab->micro_image_path);

        $lab->delete();

        return redirect()
            ->route('admin.lab.index')
            ->with('success', "Spesimen \"{$name}\" berhasil dihapus.");
    }

    // ====================================================================
    // PRIVATE HELPERS
    // ====================================================================

    /**
     * Validasi input form spesimen (store & update).
     */
    private function validateSpecimen(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:150'],
            'icon'          => ['nullable', 'string', 'max:10'],
            'magnification' => ['required', 'string', 'max:20'],
            'quran_verse'   => ['nullable', 'string'],
            'quran_ref'     => ['nullable', 'string', 'max:100'],
            'reflection'    => ['nullable', 'string'],
            'is_active'     => ['nullable', 'boolean'],
            'sort_order'    => ['nullable', 'integer', 'min:0', 'max:9999'],
            'macro_image'   => ['nullable', 'image', 'max:5120'],
            'micro_image'   => ['nullable', 'image', 'max:5120'],
        ], [
            'name.required'          => 'Nama spesimen wajib diisi.',
            'magnification.required' => 'Label perbesaran wajib diisi (mis. 400×).',
            'macro_image.image'      => 'Gambar makro harus berupa file gambar.',
            'macro_image.max'        => 'Ukuran gambar makro maksimal 5 MB.',
            'micro_image.image'      => 'Gambar mikro harus berupa file gambar.',
            'micro_image.max'        => 'Ukuran gambar mikro maksimal 5 MB.',
        ]);

        // Mencegah error Database NOT NULL jika icon dikosongkan dari form
        $validated['icon'] = $validated['icon'] ?? '🔬';

        return $validated;
    }


    /**
     * Bangun array structures dari repeated form fields.
     *
     * Form mengirimkan:
     *   structures[0][name], structures[0][function], structures[0][icon]
     *   structures[1][name], structures[1][function], structures[1][icon]
     *   ...dst
     */
    private function buildStructuresArray(Request $request): array
    {
        $raw = $request->input('structures', []);

        return collect($raw)
            ->filter(fn($s) => !empty(trim($s['name'] ?? '')))
            ->map(fn($s) => [
                'name'     => strip_tags(trim($s['name'] ?? '')),
                'function' => strip_tags(trim($s['function'] ?? '')),
                'icon'     => mb_substr(trim($s['icon'] ?? '🔬'), 0, 5),
            ])
            ->values()
            ->toArray();
    }

    /**
     * Kompresi & simpan gambar ke WebP (kualitas 75%) via ekstensi GD PHP.
     * Sesuai Task 9.1: Responsive Image Compactor Service.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @param  string $type   'macro' | 'micro'
     * @return string         Path relatif dari storage/app/public
     */
    private function processImageUpload($file, string $type): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename  = 'lab-specimens/' . $type . '-' . Str::uuid() . '.webp';
        $destPath  = public_path('storage/' . $filename);

        // Pastikan direktori ada
        if (!file_exists(dirname($destPath))) {
            mkdir(dirname($destPath), 0755, true);
        }

        // Buat GD resource dari tipe file sumber
        $sourceImage = match ($extension) {
            'jpg', 'jpeg' => imagecreatefromjpeg($file->getRealPath()),
            'png'         => imagecreatefrompng($file->getRealPath()),
            'gif'         => imagecreatefromgif($file->getRealPath()),
            'webp'        => imagecreatefromwebp($file->getRealPath()),
            default       => imagecreatefromjpeg($file->getRealPath()),
        };

        // Pertahankan transparansi PNG
        if (in_array($extension, ['png', 'gif'])) {
            imagepalettetotruecolor($sourceImage);
            imagealphablending($sourceImage, true);
            imagesavealpha($sourceImage, true);
        }

        // Simpan sebagai WebP dengan kualitas 75%
        imagewebp($sourceImage, $destPath, 75);
        imagedestroy($sourceImage);

        return $filename;
    }

    /**
     * Hapus file gambar dari storage (hanya file di storage/lab-specimens/,
     * bukan path legacy /images/ dari public/).
     */
    private function deleteImageFile(?string $path): void
    {
        if (!$path) return;

        // Jangan hapus file legacy dari public/images/lab/
        if (str_starts_with($path, '/images/')) return;

        $fullPath = public_path('storage/' . $path);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}
