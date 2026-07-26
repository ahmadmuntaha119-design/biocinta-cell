@extends('layouts.admin')
@section('title', 'Pengaturan Situs')

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="font-serif font-bold text-2xl text-slate-900">Pengaturan Situs</h1>
        <p class="text-slate-500 text-sm mt-0.5">Kelola konfigurasi link Google Form dan pengaturan lainnya.</p>
    </div>
</div>

{{-- Flash messages --}}
@if(session('success'))
<div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-xl text-sm flex items-center gap-2">
    <span class="material-symbols-outlined text-emerald-600" style="font-size:20px;">check_circle</span>
    {{ session('success') }}
</div>
@endif

{{-- Card: Google Form --}}
<div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden max-w-2xl">
    <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-100 bg-slate-50/60">
        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-blue-600" style="font-size:22px;">assignment</span>
        </div>
        <div>
            <h2 class="font-semibold text-slate-800 text-base">Link Google Form Angket Siswa</h2>
            <p class="text-slate-500 text-xs">Tempelkan URL Google Form di sini. Tombol akan otomatis muncul di halaman utama siswa.</p>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" class="p-6 space-y-5">
        @csrf
        @method('PUT')

        {{-- Preview status --}}
        @if($gformUrl)
        <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
            <span class="material-symbols-outlined text-emerald-600 flex-shrink-0" style="font-size:20px;">check_circle</span>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-semibold text-emerald-800 mb-0.5">Aktif — Tombol sedang ditampilkan ke siswa</p>
                <a href="{{ $gformUrl }}" target="_blank" rel="noopener noreferrer"
                   class="text-xs text-emerald-700 hover:underline truncate block">{{ $gformUrl }}</a>
            </div>
            <a href="{{ $gformUrl }}" target="_blank" rel="noopener noreferrer"
               class="flex-shrink-0 text-emerald-600 hover:text-emerald-800 transition-colors">
                <span class="material-symbols-outlined" style="font-size:18px;">open_in_new</span>
            </a>
        </div>
        @else
        <div class="flex items-center gap-3 p-4 bg-amber-50 border border-amber-200 rounded-xl">
            <span class="material-symbols-outlined text-amber-500 flex-shrink-0" style="font-size:20px;">info</span>
            <p class="text-xs text-amber-700">Belum ada link Google Form. Tombol tidak akan ditampilkan ke siswa.</p>
        </div>
        @endif

        {{-- Input URL --}}
        <div>
            <label for="gform_url" class="block text-sm font-semibold text-slate-700 mb-2">
                URL Google Form
            </label>
            <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2">
                    <span class="material-symbols-outlined text-slate-400" style="font-size:18px;">link</span>
                </span>
                <input
                    type="url"
                    id="gform_url"
                    name="gform_url"
                    value="{{ old('gform_url', $gformUrl) }}"
                    placeholder="https://docs.google.com/forms/d/e/..."
                    class="w-full pl-10 pr-4 py-3 rounded-xl border @error('gform_url') border-red-400 bg-red-50 @else border-slate-200 @enderror
                           text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                >
            </div>
            @error('gform_url')
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @else
            <p class="mt-1.5 text-xs text-slate-400">
                Salin URL dari Google Form Anda. Pastikan form sudah dipublikasikan (Anyone with the link can respond).
            </p>
            @enderror
        </div>

        {{-- Panduan singkat --}}
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-xs text-blue-700 space-y-1.5">
            <p class="font-semibold text-blue-800 mb-2">📋 Cara mendapatkan URL Google Form:</p>
            <p>1. Buka Google Form Anda di <strong>docs.google.com/forms</strong></p>
            <p>2. Klik tombol <strong>"Kirim"</strong> di pojok kanan atas</p>
            <p>3. Pilih tab ikon <strong>tautan/link (🔗)</strong></p>
            <p>4. Salin URL yang muncul, lalu tempel di kolom di atas</p>
        </div>

        {{-- Tombol Simpan & Hapus --}}
        <div class="flex gap-3 pt-2">
            <button
                type="submit"
                class="flex items-center gap-2 px-6 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold rounded-xl transition-all shadow-sm min-h-[44px]"
            >
                <span class="material-symbols-outlined" style="font-size:18px;">save</span>
                Simpan Perubahan
            </button>
            @if($gformUrl)
            <button
                type="submit"
                name="gform_url"
                value=""
                onclick="return confirm('Yakin ingin menghapus link Google Form? Tombol akan hilang dari halaman siswa.')"
                class="flex items-center gap-2 px-5 py-2.5 border border-red-200 text-red-600 hover:bg-red-50 text-sm font-semibold rounded-xl transition-all min-h-[44px]"
            >
                <span class="material-symbols-outlined" style="font-size:18px;">link_off</span>
                Hapus Link
            </button>
            @endif
        </div>
    </form>
</div>

@endsection
