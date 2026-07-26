<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

/**
 * Task 4.1 & 4.2: MaterialController
 * Menangani halaman materi sel interaktif dan teori alat mikroskop.
 */
class MaterialController extends Controller
{
    /**
     * Task 4.1: Halaman diagram sel interaktif.
     * Slug valid: 'sel-hewan' → type animal | 'sel-tumbuhan' → type plant
     * Data semua organel (animal + plant) di-load sekaligus agar tab switching
     * tidak memerlukan reload halaman (Alpine.js client-side filter).
     */
    public function show(string $slug): View
    {
        $type = match ($slug) {
            'sel-hewan'     => 'animal',
            'sel-tumbuhan'  => 'plant',
            default         => abort(404, 'Halaman materi tidak ditemukan.'),
        };

        // Phase 4: Eager-load hotspots agar koordinat klik dibaca dari DB (bukan hardcoded)
        $materials = Material::with('hotspots')
            ->whereIn('type', ['animal', 'plant'])
            ->select(['id', 'type', 'title', 'slug', 'description', 'youtube_id', 'history_founder', 'quran_verse', 'image_path'])
            ->ordered()
            ->get();

        return view('pages.materials.show', [
            'initialType' => $type,
            'materials'   => $materials,
        ]);
    }

    /**
     * Task 4.2: Halaman teori alat mikroskop.
     * Route: GET /materials/microscope
     * CATATAN: Route ini HARUS didaftarkan SEBELUM route /{slug} di web.php
     * agar 'microscope' tidak ditangkap sebagai slug.
     */
    public function microscope(): View
    {
        $sections = Material::where('type', 'microscope')
            ->select(['id', 'title', 'slug', 'description', 'history_founder', 'quran_verse', 'image_path'])
            ->get();

        return view('pages.materials.microscope', compact('sections'));
    }
}
