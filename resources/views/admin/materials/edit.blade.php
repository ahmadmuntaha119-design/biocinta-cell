@extends('layouts.admin')
@section('title', 'Edit: ' . $material->title)

@section('content')

{{-- Breadcrumb --}}
<div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
    <a href="{{ route('admin.materials.index') }}" class="hover:text-emerald-700 transition-colors font-medium">Manajemen Materi</a>
    <span class="material-symbols-outlined" style="font-size:16px;">chevron_right</span>
    <span class="text-slate-800 font-semibold">Edit: {{ $material->title }}</span>
</div>

{{-- Page Header --}}
<div class="mb-6">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="font-serif font-bold text-2xl text-slate-900 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-white" style="font-size:20px;">edit</span>
                </div>
                Edit Materi
            </h1>
            <p class="text-slate-500 text-sm mt-1 ml-[52px]">
                Memperbarui data untuk:
                <span class="font-semibold text-slate-800">{{ $material->title }}</span>
                —
                @php
                    $typeLabel = match($material->type) {
                        'animal'     => 'Sel Hewan',
                        'plant'      => 'Sel Tumbuhan',
                        'microscope' => 'Mikroskop',
                        default      => $material->type,
                    };
                @endphp
                <span class="text-emerald-700">{{ $typeLabel }}</span>
                | <span class="font-mono text-xs text-slate-400">{{ $material->slug }}</span>
            </p>
        </div>

        {{-- Quick info --}}
        <div class="hidden sm:flex items-center gap-3 flex-shrink-0">
            <div class="text-right">
                <p class="text-xs text-slate-400">Hotspot aktif</p>
                <p class="text-2xl font-bold text-amber-600">{{ $material->hotspots->count() }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                <span class="material-symbols-outlined text-amber-600" style="font-size:24px;">pin_drop</span>
            </div>
        </div>
    </div>
</div>

@include('admin.materials._form', [
    'formAction' => route('admin.materials.update', $material),
    'formMethod' => 'PUT',
])

@endsection
