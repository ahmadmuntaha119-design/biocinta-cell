@extends('layouts.admin')
@section('title', 'Bank Soal Puzzle')

@section('content')

<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="font-serif font-bold text-2xl text-slate-900">Bank Soal Puzzle Harian</h1>
        <p class="text-slate-500 text-sm mt-0.5">Jadwalkan 4 soal cocok-organel per hari. Sistem otomatis menayangkan soal sesuai tanggal.</p>
    </div>
    <a
        href="{{ route('admin.puzzles.create') }}"
        class="flex items-center gap-2 bg-emerald-900 text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-emerald-800 transition-all shadow-sm min-h-[44px] flex-shrink-0"
    >
        <span class="material-symbols-outlined" style="font-size:20px;">add_circle</span>
        Tambah Soal Baru
    </a>
</div>

{{-- Today status banner --}}
@if($todayHasPuzzle)
    <div class="mb-5 bg-emerald-50 border border-emerald-200 rounded-xl px-5 py-3.5 flex items-center gap-3 text-sm text-emerald-800">
        <span class="text-emerald-600 text-lg">✅</span>
        <span><strong>Hari ini sudah ada soal puzzle</strong> yang aktif untuk siswa.</span>
    </div>
@else
    <div class="mb-5 bg-amber-50 border border-amber-200 rounded-xl px-5 py-3.5 flex items-center gap-3 text-sm text-amber-800">
        <span class="text-amber-600 text-lg">⚠️</span>
        <span><strong>Belum ada soal puzzle hari ini.</strong>
            <a href="{{ route('admin.puzzles.create') }}" class="underline font-semibold ml-1">Tambahkan sekarang</a>
        </span>
    </div>
@endif

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    @if($puzzles->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <span class="material-symbols-outlined text-slate-300" style="font-size:64px;">extension</span>
            <p class="text-slate-500 font-medium mt-3">Belum ada soal puzzle yang dijadwalkan.</p>
            <p class="text-slate-400 text-sm mt-1">Klik "Tambah Soal Baru" untuk mulai menjadwalkan game harian.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider">Tanggal Tayang</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider hidden md:table-cell">4 Organel</th>
                        <th class="text-center px-5 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider hidden sm:table-cell">Skor Dikumpulkan</th>
                        <th class="text-right px-5 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($puzzles as $puzzle)
                        @php
                            $isToday  = $puzzle->publish_date->isToday();
                            $isPast   = $puzzle->publish_date->isPast() && !$isToday;
                            $isFuture = $puzzle->publish_date->isFuture();
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors {{ $isToday ? 'bg-emerald-50/50' : '' }}">
                            {{-- Date --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="text-sm font-semibold text-slate-900">
                                        {{ $puzzle->publish_date->translatedFormat('l, d M Y') }}
                                    </div>
                                    @if($isToday)
                                        <span class="bg-emerald-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">HARI INI</span>
                                    @elseif($isFuture)
                                        <span class="bg-blue-100 text-blue-700 text-[10px] font-semibold px-2 py-0.5 rounded-full">Dijadwalkan</span>
                                    @else
                                        <span class="bg-slate-100 text-slate-500 text-[10px] font-semibold px-2 py-0.5 rounded-full">Selesai</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Organelle names --}}
                            <td class="px-5 py-4 hidden md:table-cell">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach([1,2,3,4] as $i)
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 text-xs rounded-lg font-medium">
                                            {{ $puzzle->{"correct_organelle_$i"} }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            {{-- Score count --}}
                            <td class="px-5 py-4 text-center hidden sm:table-cell">
                                @php $scoreCount = $puzzle->scores()->count(); @endphp
                                @if($scoreCount > 0)
                                    <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-lg">
                                        <span class="material-symbols-outlined" style="font-size:12px;">people</span>
                                        {{ $scoreCount }} siswa
                                    </span>
                                @else
                                    <span class="text-slate-300 text-xs">—</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.puzzles.edit', $puzzle) }}"
                                       class="w-9 h-9 rounded-lg bg-emerald-50 hover:bg-emerald-100 flex items-center justify-center transition-colors"
                                       title="Edit soal">
                                        <span class="material-symbols-outlined text-emerald-700" style="font-size:16px;">edit</span>
                                    </a>
                                    <form
                                        action="{{ route('admin.puzzles.destroy', $puzzle) }}"
                                        method="POST"
                                        onsubmit="return confirm('Hapus soal puzzle tanggal {{ $puzzle->publish_date->format('d M Y') }}? Semua skor siswa yang terkait juga akan dihapus.')"
                                    >
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="w-9 h-9 rounded-lg bg-red-50 hover:bg-red-100 flex items-center justify-center transition-colors"
                                                title="Hapus soal">
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
        @if($puzzles->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">{{ $puzzles->links() }}</div>
        @endif
    @endif
</div>
@endsection
