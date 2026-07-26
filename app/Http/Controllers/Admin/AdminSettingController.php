<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * AdminSettingController — Kelola konfigurasi situs (Google Form URL, dsb.)
 */
class AdminSettingController extends Controller
{
    public function index(): View
    {
        $gformUrl = Setting::get('gform_url');
        return view('admin.settings.index', compact('gformUrl'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'gform_url' => ['nullable', 'url', 'max:1000'],
        ], [
            'gform_url.url' => 'Format URL tidak valid. Pastikan dimulai dengan https://',
        ]);

        Setting::set('gform_url', $request->input('gform_url') ?: null);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Link Google Form berhasil disimpan!');
    }
}
