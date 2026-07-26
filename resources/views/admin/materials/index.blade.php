@extends('layouts.admin')
@section('title', 'Manajemen Materi')

@section('content')

{{-- ============================================================
     PAGE HEADER
     ============================================================ --}}
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="font-serif font-bold text-2xl text-slate-900">Manajemen Materi</h1>
        <p class="text-slate-500 text-sm mt-0.5">Kelola data organel sel hewan, sel tumbuhan, dan materi mikroskop beserta koordinat hotspot klik.</p>
    </div>
    <a
        href="{{ route('admin.materials.create') }}"
        id="btn-tambah-materi"
        class="flex items-center gap-2 bg-emerald-900 text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-emerald-800 transition-all shadow-sm min-h-[44px] flex-shrink-0"
    >
        <span class="material-symbols-outlined" style="font-size:20px;">add_circle</span>
        Tambah Materi Baru
    </a>
</div>

{{-- ============================================================
     FILTER TABS
     ============================================================ --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-1 flex gap-1 mb-6 w-fit">
    @php
        $filters = [
            'all'        => ['label' => 'Semua', 'icon' => 'grid_view'],
            'animal'     => ['label' => 'Sel Hewan', 'icon' => 'pets'],
            'plant'      => ['label' => 'Sel Tumbuhan', 'icon' => 'park'],
            'microscope' => ['label' => 'Mikroskop', 'icon' => 'biotech'],
        ];
    @endphp
    @foreach($filters as $key => $filter)
        <a
            href="{{ route('admin.materials.index', ['type' => $key]) }}"
            class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-all min-h-[40px]
                   {{ $typeFilter === $key ? 'bg-emerald-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}"
        >
            <span class="material-symbols-outlined" style="font-size:16px;">{{ $filter['icon'] }}</span>
            {{ $filter['label'] }}
            {{-- Badge count --}}
            @if($typeFilter === $key)
                <span class="bg-white/20 text-white text-xs px-1.5 py-0.5 rounded-lg">
                    {{ $materials->total() }}
                </span>
            @endif
        </a>
    @endforeach
</div>

{{-- ============================================================
     TABLE
     ============================================================ --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    @if($materials->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <span class="material-symbols-outlined text-slate-300" style="font-size:64px;">science</span>
            <p class="text-slate-500 font-medium mt-3">Belum ada materi yang ditambahkan.</p>
            <p class="text-slate-400 text-sm mt-1">Klik "Tambah Materi Baru" untuk mulai mengisi konten.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider">Tipe</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider">Judul Materi</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider hidden md:table-cell">Slug</th>
                        <th class="text-center px-5 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider hidden sm:table-cell">Hotspot</th>
                        <th class="text-center px-5 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider hidden md:table-cell">Urutan</th>
                        <th class="text-right px-5 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($materials as $material)
                        <tr class="hover:bg-slate-50 transition-colors group">
                            {{-- Type Badge --}}
                            <td class="px-5 py-4">
                                @php
                                    $typeBadge = match($material->type) {
                                        'animal'     => ['label' => 'Sel Hewan',   'class' => 'bg-orange-100 text-orange-700'],
                                        'plant'      => ['label' => 'Sel Tumbuhan','class' => 'bg-green-100 text-green-700'],
                                        'microscope' => ['label' => 'Mikroskop',   'class' => 'bg-blue-100 text-blue-700'],
                                        default      => ['label' => $material->type,'class' => 'bg-slate-100 text-slate-600'],
                                    };
                                @endphp
                                <span class="px-2 py-1 rounded-lg text-xs font-semibold {{ $typeBadge['class'] }}">
                                    {{ $typeBadge['label'] }}
                                </span>
                            </td>

                            {{-- Title --}}
                            <td class="px-5 py-4">
                                <div class="font-semibold text-slate-900">{{ $material->title }}</div>
                                @if($material->youtube_id)
                                    <span class="text-xs text-red-500 flex items-center gap-1 mt-0.5">
                                        <span class="material-symbols-outlined" style="font-size:12px;">play_circle</span>
                                        Video tersedia
                                    </span>
                                @endif
                            </td>

                            {{-- Slug --}}
                            <td class="px-5 py-4 hidden md:table-cell">
                                <code class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded-lg">{{ $material->slug }}</code>
                            </td>

                            {{-- Hotspot Count --}}
                            <td class="px-5 py-4 text-center hidden sm:table-cell">
                                @if($material->hotspots_count > 0)
                                    <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-lg">
                                        <span class="material-symbols-outlined" style="font-size:12px;">pin_drop</span>
                                        {{ $material->hotspots_count }}
                                    </span>
                                @else
                                    <span class="text-slate-300 text-xs">—</span>
                                @endif
                            </td>

                            {{-- Sort Order --}}
                            <td class="px-5 py-4 text-center hidden md:table-cell">
                                <span class="text-slate-500 text-xs font-mono">{{ $material->sort_order }}</span>
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Preview (buka halaman siswa) --}}
                                    @if(in_array($material->type, ['animal', 'plant']))
                                        <a
                                            href="{{ route('materials.show', $material->type === 'animal' ? 'sel-hewan' : 'sel-tumbuhan') }}"
                                            target="_blank"
                                            class="w-9 h-9 rounded-lg bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-colors"
                                            title="Preview di halaman siswa"
                                        >
                                            <span class="material-symbols-outlined text-slate-500" style="font-size:16px;">open_in_new</span>
                                        </a>
                                    @endif

                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('admin.materials.edit', $material) }}"
                                        class="w-9 h-9 rounded-lg bg-emerald-50 hover:bg-emerald-100 flex items-center justify-center transition-colors"
                                        title="Edit materi"
                                    >
                                        <span class="material-symbols-outlined text-emerald-700" style="font-size:16px;">edit</span>
                                    </a>

                                    {{-- Delete --}}
                                    <form
                                        action="{{ route('admin.materials.destroy', $material) }}"
                                        method="POST"
                                        onsubmit="return confirm('Hapus materi \"{{ addslashes($material->title) }}\" beserta {{ $material->hotspots_count }} hotspot-nya? Tindakan ini tidak dapat dibatalkan.')"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="w-9 h-9 rounded-lg bg-red-50 hover:bg-red-100 flex items-center justify-center transition-colors"
                                            title="Hapus materi"
                                        >
                                            <span class="material-symbols-outlined text-red-600" style="font-size:16px;">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($materials->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $materials->links() }}
            </div>
        @endif
    @endif
</div>

{{-- Stats Summary --}}
<div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-4">
    @php
        $stats = [
            ['type' => 'animal',     'label' => 'Organel Sel Hewan',   'icon' => 'pets',    'color' => 'bg-orange-50 border-orange-200 text-orange-700'],
            ['type' => 'plant',      'label' => 'Organel Sel Tumbuhan','icon' => 'park',    'color' => 'bg-green-50 border-green-200 text-green-700'],
            ['type' => 'microscope', 'label' => 'Materi Mikroskop',    'icon' => 'biotech', 'color' => 'bg-blue-50 border-blue-200 text-blue-700'],
        ];
    @endphp
    @foreach($stats as $stat)
        <div class="bg-white rounded-xl border border-slate-200 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-slate-500" style="font-size:20px;">{{ $stat['icon'] }}</span>
            </div>
            <div>
                <div class="text-2xl font-bold text-slate-900">
                    {{ \App\Models\Material::where('type', $stat['type'])->count() }}
                </div>
                <div class="text-xs text-slate-500">{{ $stat['label'] }}</div>
            </div>
        </div>
    @endforeach
    <div class="bg-white rounded-xl border border-slate-200 p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-amber-600" style="font-size:20px;">pin_drop</span>
        </div>
        <div>
            <div class="text-2xl font-bold text-slate-900">
                {{ \App\Models\MaterialHotspot::count() }}
            </div>
            <div class="text-xs text-slate-500">Total Hotspot</div>
        </div>
    </div>
</div>

@endsection
