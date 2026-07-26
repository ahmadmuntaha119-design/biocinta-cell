@extends('layouts.app')
@section('title', 'Papan Peringkat Kelas')
@section('meta_description', 'Papan Peringkat Global BioCinta Cell — Akumulasi nilai kuis utama dan poin harian game puzzle siswa.')

@section('content')
<div>

{{-- Breadcrumb --}}
<div class="bg-white border-b border-outline-variant/20">
    <div class="max-w-[1440px] mx-auto px-4 md:px-16 py-3 flex items-center gap-2 text-xs text-on-surface-variant">
        <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
        <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
        <span class="text-primary font-semibold">Papan Peringkat</span>
    </div>
</div>

{{-- ============================================================
     HERO: Leaderboard Header (Clean & Centered)
     ============================================================ --}}
<header class="py-12 px-4 text-center bg-gradient-to-b from-sky-100/50 via-sky-50/20 to-white">
    <div class="max-w-2xl mx-auto space-y-3">
        <h1 class="font-serif font-bold text-2xl md:text-3xl text-emerald-950 flex items-center justify-center gap-2">
            🏆 LEADERBOARD KUIS
        </h1>
        <p class="text-on-surface-variant text-xs md:text-sm leading-relaxed max-w-lg mx-auto">
            Peringkat skor kuis tertinggi kelas XI. Terus belajar dan raih puncak prestasi harianmu!
        </p>
    </div>
</header>

<div class="max-w-[1440px] mx-auto px-4 md:px-16 pt-4 pb-2">
    {{-- Sub-Navbar Tab --}}
    <div class="flex border-b border-outline-variant/30 max-w-md">
        <a href="{{ route('reflection.create') }}" 
           class="flex-1 text-center py-3 text-sm font-semibold border-b-2 transition-all duration-200
                  {{ request()->routeIs('reflection.create') ? 'border-primary text-primary' : 'border-transparent text-on-surface-variant hover:text-primary' }}">
            Tulis Jurnal Refleksi
        </a>
        <a href="{{ route('leaderboard.index') }}" 
           class="flex-1 text-center py-3 text-sm font-semibold border-b-2 transition-all duration-200
                  {{ request()->routeIs('leaderboard.index') ? 'border-primary text-primary' : 'border-transparent text-on-surface-variant hover:text-primary' }}">
            Papan Peringkat Kelas
        </a>
    </div>
</div>

<div class="max-w-[1440px] mx-auto px-4 md:px-16 py-6">
    <div class="max-w-4xl mx-auto space-y-8">

        {{-- Info Caching --}}
        <div class="flex items-center gap-2.5 bg-slate-50 border border-slate-200 text-slate-600 px-4 py-3 rounded-xl text-xs justify-center lg:justify-start">
            <span class="material-symbols-outlined text-slate-400" style="font-size:16px;">info</span>
            <span>Data diperbarui secara berkala setiap 10 menit untuk menjaga stabilitas jaringan server.</span>
        </div>

        @if($topStudents->isEmpty())
            {{-- Empty State --}}
            <div class="glass-card rounded-2xl p-12 text-center">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <span class="material-symbols-outlined" style="font-size:32px;">leaderboard</span>
                </div>
                <h3 class="font-serif font-bold text-lg text-primary mb-1">Papan Peringkat Masih Kosong</h3>
                <p class="text-sm text-on-surface-variant max-w-md mx-auto">
                    Belum ada siswa yang menyelesaikan kuis kelas atau puzzle harian hari ini. Jadilah yang pertama mencapai puncak!
                </p>
            </div>
        @else
            {{-- Podium / Top 3 --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end pt-4">
                
                {{-- Peringkat 2 --}}
                @if($topStudents->count() >= 2)
                    @php $p2 = $topStudents[1]; @endphp
                    <div class="order-2 md:order-1 flex flex-col items-center">
                        <div class="w-full bg-white border border-slate-200/60 border-t-4 border-t-slate-400 rounded-2xl p-6 shadow-sm hover:shadow transition-shadow relative overflow-hidden text-center flex flex-col justify-center items-center min-h-[190px]">
                            {{-- Medal Badge --}}
                            <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-300 flex items-center justify-center shadow-inner mb-3">
                                <span class="text-xs font-black text-slate-700">2</span>
                            </div>
                            
                            <h3 class="font-bold text-slate-800 text-sm md:text-base line-clamp-1 mb-1">{{ $p2->name }}</h3>
                            <p class="font-black text-primary text-sm">{{ number_format($p2->grand_total, 0) }} PTS</p>
                        </div>
                    </div>
                @endif

                {{-- Peringkat 1 --}}
                @if($topStudents->count() >= 1)
                    @php $p1 = $topStudents[0]; @endphp
                    <div class="order-1 md:order-2 flex flex-col items-center">
                        <div class="w-full bg-white border border-slate-200/60 border-t-4 border-t-amber-500 rounded-2xl p-8 shadow-md hover:shadow-lg transition-all relative overflow-hidden text-center flex flex-col justify-center items-center min-h-[210px] transform -translate-y-2">
                            {{-- Medal Badge --}}
                            <div class="w-12 h-12 rounded-full bg-amber-50 border border-amber-300 flex items-center justify-center shadow-inner mb-3">
                                <span class="text-sm font-black text-amber-600">1</span>
                            </div>
                            
                            <h3 class="font-bold text-slate-900 text-base md:text-lg line-clamp-1 mb-1">{{ $p1->name }}</h3>
                            <p class="font-black text-primary text-base">{{ number_format($p1->grand_total, 0) }} PTS</p>
                        </div>
                    </div>
                @endif

                {{-- Peringkat 3 --}}
                @if($topStudents->count() >= 3)
                    @php $p3 = $topStudents[2]; @endphp
                    <div class="order-3 md:order-3 flex flex-col items-center">
                        <div class="w-full bg-white border border-slate-200/60 border-t-4 border-t-orange-400 rounded-2xl p-6 shadow-sm hover:shadow transition-shadow relative overflow-hidden text-center flex flex-col justify-center items-center min-h-[190px]">
                            {{-- Medal Badge --}}
                            <div class="w-10 h-10 rounded-full bg-orange-50 border border-orange-300 flex items-center justify-center shadow-inner mb-3">
                                <span class="text-xs font-black text-orange-700">3</span>
                            </div>
                            
                            <h3 class="font-bold text-slate-850 text-sm md:text-base line-clamp-1 mb-1">{{ $p3->name }}</h3>
                            <p class="font-black text-primary text-sm">{{ number_format($p3->grand_total, 0) }} PTS</p>
                        </div>
                    </div>
                @endif

            </div>

            {{-- List Ranks 1 - 10 --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/60 mt-8 space-y-4">
                {{-- Title list --}}
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                    <span class="material-symbols-outlined text-amber-500" style="font-variation-settings: 'FILL' 1;">bolt</span>
                    <h3 class="font-serif font-bold text-slate-800 text-base">Peringkat Seluruh Siswa</h3>
                </div>
                
                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="text-slate-400 font-bold text-[11px] uppercase tracking-wider border-b border-slate-100">
                                <th class="py-3 w-20 text-center">Rank</th>
                                <th class="py-3">Nama Siswa</th>
                                <th class="py-3 text-right">Skor Akhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($topStudents as $index => $student)
                                <tr class="hover:bg-slate-50/30 transition-colors">
                                    {{-- Rank --}}
                                    <td class="py-4 text-center">
                                        @if($index === 0)
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-500 text-white font-bold text-xs">1</span>
                                        @elseif($index === 1)
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-400 text-white font-bold text-xs">2</span>
                                        @elseif($index === 2)
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-orange-400 text-white font-bold text-xs">3</span>
                                        @else
                                            <span class="font-bold text-slate-400">{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    
                                    {{-- Name --}}
                                    <td class="py-4 font-semibold text-slate-800">
                                        {{ $student->name }}
                                    </td>
                                    
                                    {{-- Score --}}
                                    <td class="py-4 text-right font-bold text-primary">
                                        {{ number_format($student->grand_total, 0) }} PTS
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Privacy Statement --}}
            <div class="text-center text-xs text-on-surface-variant mt-6 italic">
                Sesuai dengan komitmen BioCinta Cell dalam menjaga kenyamanan dan motivasi belajar siswa, papan peringkat ini hanya menampilkan peringkat 10 besar saja. Peringkat penuh secara menyeluruh dikelola oleh Guru Biologi.
            </div>

        @endif

    </div>
</div>

</div>
@endsection
