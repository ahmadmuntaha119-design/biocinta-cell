@extends('layouts.admin')
@section('title', 'Lab Virtual — Manajemen Spesimen')

@section('content')

{{-- ============================================================
     PAGE HEADER
     ============================================================ --}}
<div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-8">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-emerald-900 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-white" style="font-size:24px;">science</span>
        </div>
        <div>
            <h1 class="font-serif font-bold text-2xl text-slate-900">Lab Virtual</h1>
            <p class="text-slate-500 text-sm mt-0.5">Kelola spesimen yang ditampilkan di modul Laboratorium Maya siswa</p>
        </div>
    </div>
    <div class="flex items-center gap-3 flex-shrink-0">
        <a href="{{ route('labs.index') }}" target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600
                  font-semibold text-sm hover:bg-slate-50 transition-colors min-h-[44px]">
            <span class="material-symbols-outlined" style="font-size:16px;">open_in_new</span>
            Tampilan Siswa
        </a>
        <a href="{{ route('admin.lab.create') }}"
           id="btn-tambah-spesimen"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-900 text-white
                  font-semibold text-sm hover:bg-emerald-800 transition-colors min-h-[44px] shadow-sm">
            <span class="material-symbols-outlined" style="font-size:18px;">add</span>
            Tambah Spesimen
        </a>
    </div>
</div>

{{-- ============================================================
     STATS
     ============================================================ --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
        <p class="text-xs text-slate-400 font-medium mb-1">Total Spesimen</p>
        <p class="text-3xl font-bold text-slate-900">{{ $specimens->total() }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
        <p class="text-xs text-slate-400 font-medium mb-1">Aktif (Tampil)</p>
        <p class="text-3xl font-bold text-emerald-700">{{ $totalActive }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
        <p class="text-xs text-slate-400 font-medium mb-1">Nonaktif</p>
        <p class="text-3xl font-bold text-slate-400">{{ $totalInactive }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
        <p class="text-xs text-slate-400 font-medium mb-1">Total Gambar</p>
        <p class="text-3xl font-bold text-teal-700">{{ $specimens->total() * 2 }}</p>
    </div>
</div>

{{-- ============================================================
     SPECIMEN LIST
     ============================================================ --}}
@if($specimens->isEmpty())
    <div class="bg-white rounded-2xl border border-dashed border-slate-200 p-16 text-center">
        <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-slate-400" style="font-size:32px;">science</span>
        </div>
        <h3 class="font-serif font-semibold text-slate-700 mb-2">Belum Ada Spesimen</h3>
        <p class="text-slate-400 text-sm mb-6">Tambahkan spesimen pertama untuk Lab Virtual siswa.</p>
        <a href="{{ route('admin.lab.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-900 text-white font-semibold text-sm hover:bg-emerald-800 transition-colors">
            <span class="material-symbols-outlined" style="font-size:18px;">add</span>
            Tambah Spesimen Pertama
        </a>
    </div>
@else
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
        @foreach($specimens as $specimen)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden
                    {{ !$specimen->is_active ? 'opacity-60' : '' }}"
             x-data="{ confirmDelete: false }">

            {{-- Card Header --}}
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">{{ $specimen->icon }}</span>
                    <div>
                        <h3 class="font-semibold text-slate-800 text-sm leading-tight">{{ $specimen->name }}</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Perbesaran: {{ $specimen->magnification }} · Urutan: #{{ $specimen->sort_order }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if($specimen->is_active)
                        <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">Aktif</span>
                    @else
                        <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-500 text-xs font-bold border border-slate-200">Nonaktif</span>
                    @endif
                </div>
            </div>

            {{-- Image side-by-side --}}
            <div class="grid grid-cols-2 gap-0">
                <div class="relative overflow-hidden bg-slate-100" style="aspect-ratio: 4/3;">
                    <img src="{{ $specimen->macro_image_url }}"
                         alt="Makro {{ $specimen->name }}"
                         class="w-full h-full object-cover">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent px-2.5 py-2">
                        <p class="text-white text-[10px] font-semibold">📷 MAKRO</p>
                    </div>
                </div>
                <div class="relative overflow-hidden bg-slate-900 border-l border-slate-200" style="aspect-ratio: 4/3;">
                    <img src="{{ $specimen->micro_image_url }}"
                         alt="Mikro {{ $specimen->name }}"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0"
                         style="background: radial-gradient(ellipse 80% 80% at 50% 50%, transparent 55%, rgba(0,0,0,0.6) 100%);"></div>
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent px-2.5 py-2">
                        <p class="text-white text-[10px] font-semibold">🔬 MIKRO · {{ $specimen->magnification }}</p>
                    </div>
                </div>
            </div>

            {{-- Structures preview --}}
            <div class="px-5 py-3.5 border-t border-slate-100">
                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Struktur Sel</p>
                <div class="flex flex-wrap gap-1.5">
                    @foreach($specimen->structures ?? [] as $structure)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-50 border border-slate-200 text-xs text-slate-600">
                        {{ $structure['icon'] ?? '🔬' }} {{ $structure['name'] }}
                    </span>
                    @endforeach
                </div>

                @if($specimen->quran_ref)
                <div class="mt-3 flex items-center gap-2 text-xs text-amber-700">
                    <span>📖</span>
                    <span class="font-medium">{{ $specimen->quran_ref }}</span>
                </div>
                @endif
            </div>

            {{-- Action buttons --}}
            <div class="flex items-center justify-between gap-3 px-5 py-3 bg-slate-50 border-t border-slate-100">
                <p class="text-xs text-slate-400 font-mono">ID: {{ $specimen->id }}</p>
                <div class="flex items-center gap-2">
                    {{-- Edit button --}}
                    <a href="{{ route('admin.lab.edit', $specimen) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold
                              text-slate-600 bg-white border border-slate-200 hover:border-emerald-300
                              hover:text-emerald-700 transition-all min-h-[36px]">
                        <span class="material-symbols-outlined" style="font-size:14px;">edit</span>
                        Edit
                    </a>

                    {{-- Delete button --}}
                    <button @click="confirmDelete = true"
                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold
                                   text-red-600 bg-red-50 border border-red-200 hover:bg-red-100
                                   transition-all min-h-[36px]">
                        <span class="material-symbols-outlined" style="font-size:14px;">delete</span>
                        Hapus
                    </button>
                </div>
            </div>

            {{-- Confirm Delete Modal --}}
            <div x-show="confirmDelete"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                 style="display:none;">
                <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm"
                     @click.stop>
                    <div class="w-12 h-12 rounded-2xl bg-red-100 flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-red-600" style="font-size:24px;">delete_forever</span>
                    </div>
                    <h3 class="font-serif font-bold text-slate-900 text-center mb-2">Hapus Spesimen?</h3>
                    <p class="text-slate-500 text-sm text-center mb-6">
                        Spesimen <strong class="text-slate-800">{{ $specimen->name }}</strong> akan dihapus permanen
                        beserta file gambarnya. Tindakan ini tidak dapat dibatalkan.
                    </p>
                    <div class="flex gap-3">
                        <button @click="confirmDelete = false"
                                class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600
                                       font-semibold text-sm hover:bg-slate-50 transition-colors min-h-[44px]">
                            Batal
                        </button>
                        <form action="{{ route('admin.lab.destroy', $specimen) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full px-4 py-2.5 rounded-xl bg-red-600 text-white font-bold
                                           text-sm hover:bg-red-700 transition-colors min-h-[44px]">
                                Ya, Hapus!
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($specimens->hasPages())
    <div class="mt-6">
        {{ $specimens->links() }}
    </div>
    @endif
@endif

@endsection
