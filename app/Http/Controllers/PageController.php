<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Task 3.2: Halaman Beranda — Navigation Hub 6 Core Cards.
     * Menampilkan dashboard utama siswa dengan sambutan personal dan 6 kartu navigasi.
     */
    public function home(): View
    {
        $gformUrl = Setting::get('gform_url');
        return view('pages.home', compact('gformUrl'));
    }

    /**
     * Halaman Tentang Kami / About Us
     */
    public function about(): View
    {
        return view('pages.about');
    }
}

