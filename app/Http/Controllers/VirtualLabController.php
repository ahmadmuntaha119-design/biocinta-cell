<?php

namespace App\Http\Controllers;

use App\Models\LabSpecimen;
use Illuminate\View\View;

/**
 * VirtualLabController
 *
 * Phase 5 — Task 5.1: Laboratorium Maya (Macro-to-Micro Toggle)
 *
 * Menyajikan halaman simulasi lab virtual. Data spesimen kini diambil
 * dari database (tabel lab_specimens) yang dikelola guru via Admin Panel,
 * bukan lagi hardcoded di controller.
 *
 * Route: GET /virtual-lab  (name: labs.index)
 */
class VirtualLabController extends Controller
{
    /**
     * Tampilkan halaman Lab Virtual dengan spesimen aktif dari database.
     */
    public function index(): View
    {
        // Ambil semua spesimen aktif, urut sort_order — dari DB bukan hardcode
        $specimens = LabSpecimen::active()->get();

        return view('pages.lab.index', compact('specimens'));
    }
}
