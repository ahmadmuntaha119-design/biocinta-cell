@extends('layouts.admin')
@section('title', 'Jurnal Refleksi Siswa')

@section('content')

{{-- Page Header --}}
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="font-serif font-bold text-2xl text-slate-900">Jurnal Refleksi Siswa</h1>
        <p class="text-slate-500 text-sm mt-0.5">
            Tinjau hasil refleksi CINTA (Connection, Wisdom, Implementation) yang ditulis siswa.
        </p>
    </div>
    
    {{-- Action & Count --}}
    <div class="flex items-center gap-3 w-full sm:w-auto">
        <div class="bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-2 text-center hidden md:block">
            <div class="text-xl font-bold text-emerald-800">{{ $totalCount }}</div>
            <div class="text-[10px] text-emerald-600 uppercase font-semibold">Total Refleksi</div>
        </div>
        <a 
            href="{{ route('admin.reflections.export', ['search' => $search]) }}"
            class="inline-flex items-center justify-center gap-2 bg-emerald-950 text-white font-bold px-5 py-2.5 rounded-xl text-sm hover:bg-emerald-900 hover:shadow-md transition-all duration-300 min-h-[44px] w-full sm:w-auto shadow-sm"
        >
            <span class="material-symbols-outlined" style="font-size:18px;">download</span>
            <span>Unduh Laporan CSV</span>
        </a>
    </div>
</div>

{{-- Search Filter --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-4 mb-6">
    <form action="{{ route('admin.reflections.index') }}" method="GET" class="flex gap-3">
        <div class="relative flex-1">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 flex items-center">
                <span class="material-symbols-outlined text-slate-400" style="font-size:18px;">search</span>
            </span>
            <input 
                type="text" 
                name="search" 
                value="{{ $search }}"
                placeholder="Cari berdasarkan nama siswa atau NIS..."
                class="w-full pl-9 pr-4 py-2 text-sm border border-slate-250 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all bg-slate-50/50"
            >
        </div>
        <button 
            type="submit"
            class="bg-emerald-950 text-white font-semibold px-5 py-2 rounded-xl text-sm hover:bg-emerald-900 hover:shadow-sm transition-all flex items-center justify-center min-h-[38px]"
        >
            Cari
        </button>
        @if($search)
            <a 
                href="{{ route('admin.reflections.index') }}"
                class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold px-4 py-2 rounded-xl text-sm transition-all flex items-center justify-center min-h-[38px]"
            >
                Reset
            </a>
        @endif
    </form>
</div>

{{-- Reflections Feed --}}
<div class="space-y-5">
    @forelse($reflections as $reflection)
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 overflow-hidden">
            <div class="p-5 md:p-6">
                
                {{-- Header info --}}
                <div class="flex items-center gap-3 pb-3.5 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-800 font-bold text-sm flex items-center justify-center flex-shrink-0">
                        {{ strtoupper(substr($reflection->user->name ?? '?', 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-bold text-slate-800 text-sm md:text-base leading-tight">
                            {{ $reflection->user->name ?? 'Siswa Tidak Diketahui' }}
                        </p>
                        <p class="text-xs text-slate-450 mt-1.5 flex items-center gap-2">
                            <span>NIS: <strong class="text-slate-600 font-semibold">{{ $reflection->user->nis ?? '—' }}</strong></span>
                            <span class="text-slate-200">•</span>
                            <span>Ditulis {{ $reflection->created_at->translatedFormat('d F Y, H:i') }} ({{ $reflection->created_at->diffForHumans() }})</span>
                        </p>
                    </div>
                </div>

                {{-- The 3 CINTA pillars content --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    
                    {{-- Connection --}}
                    <div class="bg-slate-50/70 border border-slate-100 p-4 rounded-xl flex flex-col justify-between">
                        <div>
                            <span class="inline-flex items-center gap-1.5 bg-blue-50 border border-blue-100 text-blue-700 font-bold text-[9px] tracking-wider uppercase px-2 py-0.5 rounded-md mb-2.5">
                                <span class="w-1 h-1 rounded-full bg-blue-500"></span>
                                Connection (C)
                            </span>
                            <p class="text-xs md:text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                                {{ $reflection->connection_text }}
                            </p>
                        </div>
                    </div>

                    {{-- Wisdom --}}
                    <div class="bg-slate-50/70 border border-slate-100 p-4 rounded-xl flex flex-col justify-between">
                        <div>
                            <span class="inline-flex items-center gap-1.5 bg-amber-50 border border-amber-100 text-amber-700 font-bold text-[9px] tracking-wider uppercase px-2 py-0.5 rounded-md mb-2.5">
                                <span class="w-1 h-1 rounded-full bg-amber-500"></span>
                                Wisdom (W)
                            </span>
                            <p class="text-xs md:text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                                {{ $reflection->wisdom_text }}
                            </p>
                        </div>
                    </div>

                    {{-- Implementation --}}
                    <div class="bg-slate-50/70 border border-slate-100 p-4 rounded-xl flex flex-col justify-between">
                        <div>
                            <span class="inline-flex items-center gap-1.5 bg-emerald-50 border border-emerald-100 text-emerald-700 font-bold text-[9px] tracking-wider uppercase px-2 py-0.5 rounded-md mb-2.5">
                                <span class="w-1 h-1 rounded-full bg-emerald-500"></span>
                                Implementation (I)
                            </span>
                            <p class="text-xs md:text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                                {{ $reflection->implementation_text }}
                            </p>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm py-16 text-center">
            <span class="material-symbols-outlined text-slate-300" style="font-size:56px;">edit_note</span>
            <p class="text-slate-500 font-medium mt-3">Belum ada jurnal refleksi.</p>
            <p class="text-slate-400 text-sm mt-1">Jurnal refleksi dari siswa akan muncul di sini setelah mereka mengirimkan.</p>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($reflections->hasPages())
    <div class="mt-6">
        {{ $reflections->links() }}
    </div>
@endif

@endsection
