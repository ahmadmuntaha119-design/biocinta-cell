<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MaterialHotspot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * AdminMaterialController
 *
 * Phase 4 CRUD: Mengelola seluruh data organel dan koordinat hotspot
 * dari Admin Panel. Guru dapat menambah, mengedit, menghapus materi
 * beserta titik klik interaktif pada diagram sel.
 *
 * Route resource: GET/POST /admin/materials
 * Nama route:     admin.materials.*
 */
class AdminMaterialController extends Controller
{
    /**
     * Daftar semua materi — tabel dengan filter type.
     * GET /admin/materials
     */
    public function index(Request $request): View
    {
        $typeFilter = $request->get('type', 'all');

        $query = Material::withCount('hotspots')->orderBy('type')->orderBy('sort_order');

        if ($typeFilter !== 'all') {
            $query->where('type', $typeFilter);
        }

        $materials = $query->paginate(20)->withQueryString();

        return view('admin.materials.index', [
            'materials'  => $materials,
            'typeFilter' => $typeFilter,
        ]);
    }

    /**
     * Form tambah materi baru.
     * GET /admin/materials/create
     */
    public function create(): View
    {
        return view('admin.materials.create', [
            'material' => new Material(),
        ]);
    }

    /**
     * Simpan materi baru ke database.
     * POST /admin/materials
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateMaterial($request);

        // Auto-generate slug jika kosong
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }
        $validated['slug'] = Str::slug($validated['slug']);

        // Handle upload gambar (opsional)
        if ($request->hasFile('image')) {
            $validated['image_path'] = $this->processImageUpload($request->file('image'));
        }

        $material = Material::create($validated);

        // Simpan hotspot dari JSON picker
        $this->syncHotspots($material, $request->input('hotspots_json', '[]'));

        return redirect()
            ->route('admin.materials.index')
            ->with('success', "Materi \"{$material->title}\" berhasil ditambahkan beserta " . $material->hotspots()->count() . " hotspot.");
    }

    /**
     * Redirect show → edit (tidak ada halaman detail terpisah di admin).
     * GET /admin/materials/{material}
     */
    public function show(Material $material): RedirectResponse
    {
        return redirect()->route('admin.materials.edit', $material);
    }

    /**
     * Form edit materi + hotspot.
     * GET /admin/materials/{material}/edit
     */
    public function edit(Material $material): View
    {
        $material->load('hotspots');

        return view('admin.materials.edit', [
            'material' => $material,
        ]);
    }

    /**
     * Update data materi + sync hotspot.
     * PUT /admin/materials/{material}
     */
    public function update(Request $request, Material $material): RedirectResponse
    {
        $validated = $this->validateMaterial($request, $material->id);

        // Slug: regenerate dari title jika dikosongkan
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }
        $validated['slug'] = Str::slug($validated['slug']);

        // Handle upload gambar baru (opsional)
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($material->image_path && file_exists(public_path('storage/' . $material->image_path))) {
                unlink(public_path('storage/' . $material->image_path));
            }
            $validated['image_path'] = $this->processImageUpload($request->file('image'));
        }

        $material->update($validated);

        // Sync hotspot dari JSON picker
        $this->syncHotspots($material, $request->input('hotspots_json', '[]'));

        return redirect()
            ->route('admin.materials.index')
            ->with('success', "Materi \"{$material->title}\" berhasil diperbarui.");
    }

    /**
     * Hapus materi (hotspot ikut terhapus via CASCADE FK).
     * DELETE /admin/materials/{material}
     */
    public function destroy(Material $material): RedirectResponse
    {
        $title = $material->title;

        // Hapus file gambar dari storage jika ada
        if ($material->image_path && file_exists(public_path('storage/' . $material->image_path))) {
            unlink(public_path('storage/' . $material->image_path));
        }

        $material->delete(); // hotspot dihapus otomatis via ON DELETE CASCADE

        return redirect()
            ->route('admin.materials.index')
            ->with('success', "Materi \"{$title}\" berhasil dihapus.");
    }

    // ================================================================
    // PRIVATE HELPERS
    // ================================================================

    /**
     * Validasi input form material (dipakai store & update).
     */
    private function validateMaterial(Request $request, ?int $ignoreId = null): array
    {
        $slugUniqueRule = Rule::unique('materials', 'slug');
        if ($ignoreId) {
            $slugUniqueRule = $slugUniqueRule->ignore($ignoreId);
        }

        return $request->validate([
            'type'            => ['required', 'in:animal,plant,microscope'],
            'title'           => ['required', 'string', 'max:150'],
            'slug'            => ['nullable', 'string', 'max:150', $slugUniqueRule],
            'description'     => ['required', 'string'],
            'youtube_id'      => ['nullable', 'string', 'max:30', 'regex:/^[a-zA-Z0-9_\-]{8,30}$/'],
            'history_founder' => ['nullable', 'string'],
            'quran_verse'     => ['nullable', 'string'],
            'sort_order'      => ['nullable', 'integer', 'min:0'],
            'image'           => ['nullable', 'image', 'max:5120'], // max 5 MB
            'hotspots_json'   => ['nullable', 'string'],
        ], [
            'type.required'        => 'Tipe materi wajib dipilih.',
            'title.required'       => 'Judul materi wajib diisi.',
            'description.required' => 'Deskripsi/fungsi teknis wajib diisi.',
            'slug.unique'          => 'Slug ini sudah digunakan oleh materi lain.',
            'youtube_id.regex'     => 'Format YouTube ID tidak valid (11 karakter alfanumerik).',
            'image.image'          => 'File yang diunggah harus berupa gambar.',
            'image.max'            => 'Ukuran gambar maksimal 5 MB.',
        ]);
    }

    /**
     * Kompresi & simpan gambar ke WebP (kualitas 75%) via ekstensi GD PHP.
     * Sesuai Task 9.1: Responsive Image Compactor Service.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return string path relatif dari storage/app/public
     */
    private function processImageUpload($file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename  = 'materials/' . Str::uuid() . '.webp';
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
     * Sync hotspot dari JSON string (hasil visual clickpoint picker).
     * Format JSON: [{"left_percent":"49%","top_percent":"40%","bg_color":"#4f46e5","delay":"0s"}, ...]
     *
     * @param Material $material
     * @param string   $json
     */
    private function syncHotspots(Material $material, string $json): void
    {
        // Hapus semua hotspot lama
        $material->hotspots()->delete();

        $spots = json_decode($json, true);
        if (!is_array($spots) || empty($spots)) {
            return;
        }

        foreach ($spots as $spot) {
            // Sanitasi & validasi setiap entry
            $left  = preg_replace('/[^0-9.%]/', '', $spot['left_percent'] ?? '');
            $top   = preg_replace('/[^0-9.%]/', '', $spot['top_percent'] ?? '');
            $color = preg_replace('/[^#a-fA-F0-9]/', '', $spot['bg_color'] ?? '#064e3b');
            $delay = preg_replace('/[^0-9.s]/', '', $spot['delay'] ?? '0s');

            if (empty($left) || empty($top)) {
                continue; // skip entry invalid
            }

            $material->hotspots()->create([
                'left_percent' => $left,
                'top_percent'  => $top,
                'bg_color'     => $color ?: '#064e3b',
                'delay'        => $delay ?: '0s',
            ]);
        }
    }
}
