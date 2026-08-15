@extends('layouts.admin')
@section('title', 'Papan Peringkat Global')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif font-bold text-2xl text-slate-900">Papan Peringkat Global</h1>
            <p class="text-sm text-slate-500">Pantau seluruh perolehan akumulasi nilai akademik dan game puzzle siswa kelas XI.</p>
        </div>
        
        {{-- Ekspor Button --}}
        <div>
            <a href="{{ route('admin.leaderboard.export') }}"
               class="inline-flex items-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm hover:shadow transition-all min-h-[44px]">
                <span class="material-symbols-outlined" style="font-size:20px;">download</span>
                Ekspor Dokumen Excel (.xlsx)
            </a>
        </div>
    </div>

    {{-- Dashboard Card Info --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white border border-slate-200/60 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-700">
                <span class="material-symbols-outlined" style="font-size:28px;">groups</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Total Siswa Terdaftar</p>
                <p class="text-xl font-bold text-slate-800">{{ \App\Models\User::where('role', 'student')->count() }} Siswa</p>
            </div>
        </div>
        <div class="bg-white border border-slate-200/60 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-700">
                <span class="material-symbols-outlined" style="font-size:28px;">emoji_events</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Puncak Peringkat</p>
                <p class="text-xl font-bold text-slate-800">
                    @php
                        $top1 = \App\Models\User::where('role', 'student')
                            ->select('users.id', 'users.name')
                            ->selectRaw('COALESCE((SELECT SUM(score) FROM quiz_attempts WHERE quiz_attempts.user_id = users.id AND submitted_at IS NOT NULL), 0) + COALESCE((SELECT SUM(earned_points) FROM puzzle_scores WHERE puzzle_scores.user_id = users.id), 0) as grand_total')
                            ->orderByDesc('grand_total')
                            ->first();
                    @endphp
                    {{ $top1 ? $top1->name : '-' }}
                </p>
            </div>
        </div>
        <div class="bg-white border border-slate-200/60 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-700">
                <span class="material-symbols-outlined" style="font-size:28px;">quiz</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Kuis Aktif</p>
                <p class="text-xl font-bold text-slate-800">
                    {{ \App\Models\Quiz::where('is_active', true)->count() }} Kuis Sesi
                </p>
            </div>
        </div>
    </div>

    {{-- Tabel Data --}}
    <div class="bg-white border border-slate-200/60 rounded-2xl overflow-hidden shadow-sm">
        
        {{-- Search Filter --}}
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
            <form action="{{ route('admin.leaderboard.index') }}" method="GET" class="w-full sm:max-w-md flex items-center gap-2">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" style="font-size:18px;">search</span>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari nama atau NIS siswa..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/10 min-h-[44px] bg-white"
                    />
                </div>
                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors min-h-[44px]">
                    Saring
                </button>
                @if($search)
                    <a href="{{ route('admin.leaderboard.index') }}" class="text-xs text-red-600 hover:underline px-2">Batal</a>
                @endif
            </form>
        </div>

        {{-- Table Container --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-100 uppercase tracking-wider text-[11px]">
                        <th class="px-6 py-4 text-center w-20">Peringkat</th>
                        <th class="px-6 py-4 w-40">NIS</th>
                        <th class="px-6 py-4">Nama Lengkap</th>
                        <th class="px-6 py-4 text-right w-44">Total Nilai Kuis</th>
                        <th class="px-6 py-4 text-right w-44">Total Poin Puzzle</th>
                        <th class="px-6 py-4 text-right w-44">Nilai Akumulatif</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @if($students->isEmpty())
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <span class="material-symbols-outlined block mb-2 text-slate-300" style="font-size:36px;">search_off</span>
                                Tidak ditemukan data siswa yang cocok dengan kueri Anda.
                            </td>
                        </tr>
                    @else
                        @foreach($students as $index => $student)
                            @php
                                // Hitung peringkat absolut berdasarkan page pagination
                                $absoluteRank = ($students->currentPage() - 1) * $students->perPage() + $index + 1;
                            @endphp
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="px-6 py-4.5 text-center font-bold">
                                    @if($absoluteRank === 1)
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-100 text-amber-800 font-bold border border-amber-300">1</span>
                                    @elseif($absoluteRank === 2)
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-slate-100 text-slate-800 font-bold border border-slate-300">2</span>
                                    @elseif($absoluteRank === 3)
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-orange-100 text-orange-850 font-bold border border-orange-300">3</span>
                                    @else
                                        <span class="text-slate-500 font-semibold">#{{ $absoluteRank }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4.5 font-mono text-xs text-slate-500 uppercase tracking-wide">{{ $student->nis }}</td>
                                <td class="px-6 py-4.5 font-semibold text-slate-850">{{ $student->name }}</td>
                                <td class="px-6 py-4.5 text-right font-medium text-slate-600">{{ number_format($student->quiz_total, 2, ',', '.') }}</td>
                                <td class="px-6 py-4.5 text-right font-medium text-slate-600">{{ $student->puzzle_total }}</td>
                                <td class="px-6 py-4.5 text-right">
                                    <span class="text-base font-bold text-emerald-800">{{ number_format($student->grand_total, 2, ',', '.') }}</span>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($students->hasPages())
            <div class="px-6 py-4.5 border-t border-slate-100 bg-slate-50/30">
                {{ $students->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
