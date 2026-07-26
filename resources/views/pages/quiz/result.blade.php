@extends('layouts.app')
@section('title', 'Hasil Kuis: {{ $quiz->title }}')
@section('meta_description', 'Lihat hasil akhir kuis ujian Anda di BioCinta Cell.')

@push('head')
<style>
    @keyframes score-reveal {
        0%   { transform: scale(0.5); opacity: 0; }
        70%  { transform: scale(1.1); }
        100% { transform: scale(1); opacity: 1; }
    }
    .score-animate {
        animation: score-reveal 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }
    @keyframes fade-up {
        from { transform: translateY(16px); opacity: 0; }
        to   { transform: translateY(0); opacity: 1; }
    }
    .fade-up { animation: fade-up 0.5s ease forwards; }
    .fade-up-1 { animation-delay: 0.2s; opacity: 0; }
    .fade-up-2 { animation-delay: 0.4s; opacity: 0; }
    .fade-up-3 { animation-delay: 0.6s; opacity: 0; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-emerald-950 to-slate-900 flex items-start justify-center px-4 py-12 pt-20">

    {{-- Decorative blobs --}}
    <div class="fixed top-40 left-1/3 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="fixed bottom-20 right-1/4 w-60 h-60 bg-amber-400/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-lg relative z-10">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-6 bg-emerald-500/20 border border-emerald-400/40 text-emerald-200 px-5 py-3.5 rounded-xl text-sm flex items-center gap-3 backdrop-blur-sm">
                <span class="material-symbols-outlined" style="font-size: 20px;">check_circle</span>
                {{ session('success') }}
            </div>
        @endif
        @if(session('info'))
            <div class="mb-6 bg-blue-500/20 border border-blue-400/40 text-blue-200 px-5 py-3.5 rounded-xl text-sm flex items-center gap-3 backdrop-blur-sm">
                <span class="material-symbols-outlined" style="font-size: 20px;">info</span>
                {{ session('info') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="text-center mb-6 fade-up fade-up-1">
            <p class="text-emerald-400 text-xs font-bold uppercase tracking-widest mb-1">Kuis Selesai</p>
            <h1 class="font-serif font-bold text-xl md:text-2xl text-white">{{ $quiz->title }}</h1>
        </div>

        {{-- Score Card --}}
        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl p-8 shadow-2xl text-center mb-6 fade-up fade-up-1">

            {{-- Score Circle --}}
            @php
                $scoreInt = (int) round($score);
                if ($scoreInt >= 85)      { $grade = 'A'; $color = 'emerald'; $label = 'Luar Biasa!'; }
                elseif ($scoreInt >= 70)  { $grade = 'B'; $color = 'sky';     $label = 'Bagus! Pertahankan!'; }
                elseif ($scoreInt >= 55)  { $grade = 'C'; $color = 'amber';   $label = 'Cukup Baik, Terus Belajar!'; }
                else                      { $grade = 'D'; $color = 'red';     $label = 'Jangan Menyerah! Ulangi Belajar!'; }
            @endphp

            <div class="relative w-40 h-40 mx-auto mb-6">
                {{-- Outer Ring --}}
                <svg class="w-full h-full -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="42" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="10"/>
                    <circle
                        cx="50" cy="50" r="42" fill="none"
                        stroke="{{ $color === 'emerald' ? '#34d399' : ($color === 'sky' ? '#38bdf8' : ($color === 'amber' ? '#fbbf24' : '#f87171')) }}"
                        stroke-width="10"
                        stroke-linecap="round"
                        stroke-dasharray="{{ round(2 * M_PI * 42 * ($scoreInt / 100), 2) }} {{ round(2 * M_PI * 42, 2) }}"
                        class="transition-all duration-1000"
                    />
                </svg>
                {{-- Center Content --}}
                <div class="absolute inset-0 flex flex-col items-center justify-center score-animate">
                    <span class="font-mono font-black text-4xl text-white leading-none">{{ $scoreInt }}</span>
                    <span class="text-white/60 text-xs font-semibold">/100</span>
                </div>
            </div>

            {{-- Grade & Label --}}
            <div class="mb-2">
                <span class="inline-block px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest
                    {{ $color === 'emerald' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-400/40' :
                       ($color === 'sky'     ? 'bg-sky-500/20 text-sky-300 border border-sky-400/40' :
                       ($color === 'amber'   ? 'bg-amber-500/20 text-amber-300 border border-amber-400/40' :
                                              'bg-red-500/20 text-red-300 border border-red-400/40')) }}">
                    Nilai {{ $grade }}
                </span>
            </div>
            <p class="text-white font-semibold text-lg mb-1">{{ $label }}</p>
        </div>

        {{-- Statistik Cards --}}
        <div class="grid grid-cols-3 gap-3 mb-6 fade-up fade-up-2">
            {{-- Jawaban Benar --}}
            <div class="bg-white/10 backdrop-blur-sm border border-white/15 rounded-2xl p-4 text-center">
                <p class="text-emerald-300 font-black text-2xl">{{ $correctCount }}</p>
                <p class="text-emerald-200/60 text-xs mt-0.5">Benar</p>
            </div>
            {{-- Jawaban Salah --}}
            <div class="bg-white/10 backdrop-blur-sm border border-white/15 rounded-2xl p-4 text-center">
                <p class="text-red-300 font-black text-2xl">{{ $totalQuestions - $correctCount }}</p>
                <p class="text-red-200/60 text-xs mt-0.5">Salah</p>
            </div>
            {{-- Pelanggaran --}}
            <div class="bg-white/10 backdrop-blur-sm border {{ $quizAttempt->cheating_warnings > 0 ? 'border-red-400/40' : 'border-white/15' }} rounded-2xl p-4 text-center">
                <p class="{{ $quizAttempt->cheating_warnings > 0 ? 'text-red-300' : 'text-slate-300' }} font-black text-2xl">{{ $quizAttempt->cheating_warnings }}</p>
                <p class="{{ $quizAttempt->cheating_warnings > 0 ? 'text-red-200/60' : 'text-slate-300/60' }} text-xs mt-0.5">Pelanggaran</p>
            </div>
        </div>

        {{-- Detail --}}
        <div class="bg-white/10 backdrop-blur-md border border-white/15 rounded-2xl p-5 mb-6 fade-up fade-up-2 space-y-3">
            <div class="flex justify-between items-center text-sm">
                <span class="text-white/60">Kuis</span>
                <span class="text-white font-medium">{{ $quiz->title }}</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-white/60">Total Soal</span>
                <span class="text-white font-medium">{{ $totalQuestions }} butir</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-white/60">Waktu Selesai</span>
                <span class="text-white font-medium">{{ $quizAttempt->submitted_at?->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span>
            </div>
            @if($quizAttempt->cheating_warnings > 0)
            <div class="pt-2 mt-2 border-t border-red-400/30">
                <p class="text-red-300 text-xs text-center">
                    ⚠️ Terdeteksi {{ $quizAttempt->cheating_warnings }} kali meninggalkan halaman kuis. Guru dapat melihat catatan ini.
                </p>
            </div>
            @endif
        </div>

        {{-- Pesan Tadabbur (Kurikulum CINTA) --}}
        <div class="bg-amber-500/10 border border-amber-400/30 rounded-2xl p-5 mb-6 fade-up fade-up-3">
            <div class="flex items-start gap-3">
                <div>
                    <p class="text-amber-300 font-semibold text-sm mb-1">Tadabbur & Refleksi</p>
                    @if($scoreInt >= 85)
                        <p class="text-amber-200/80 text-sm leading-relaxed">"Dan Allah mengeluarkan kamu dari perut ibumu dalam keadaan tidak mengetahui sesuatu pun, dan Dia memberimu pendengaran, penglihatan, dan hati nurani." (QS. An-Nahl: 78) — Ilmu adalah anugerah. Teruslah bersyukur dan bagikan manfaatmu!</p>
                    @elseif($scoreInt >= 55)
                        <p class="text-amber-200/80 text-sm leading-relaxed">"Maka sesungguhnya bersama kesulitan ada kemudahan." (QS. Al-Insyirah: 6) — Setiap usaha yang sungguh-sungguh tidak pernah sia-sia. Teruslah berjuang dan pelajari kembali materi yang belum dikuasai.</p>
                    @else
                        <p class="text-amber-200/80 text-sm leading-relaxed">"Barangsiapa menempuh suatu jalan untuk mencari ilmu, maka Allah mudahkan baginya jalan menuju surga." (HR. Muslim) — Kegagalan adalah guru terbaik. Ulangi materi, minta bimbingan guru, dan coba lagi dengan tekad lebih kuat!</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Aksi --}}
        <div class="flex gap-3 fade-up fade-up-3">
            <a
                href="{{ route('home') }}"
                class="flex-1 flex items-center justify-center gap-2 px-5 py-3.5 rounded-xl border border-white/20 text-white/80 hover:bg-white/10 font-semibold text-sm transition-all min-h-[52px]"
            >
                <span class="material-symbols-outlined" style="font-size: 20px;">home</span>
                Kembali ke Beranda
            </a>
            <a
                href="{{ route('quiz.join') }}"
                class="flex-1 flex items-center justify-center gap-2 px-5 py-3.5 rounded-xl bg-emerald-800/60 border border-emerald-600/40 text-emerald-200 hover:bg-emerald-700/60 font-semibold text-sm transition-all min-h-[52px]"
            >
                Kuis Lainnya
            </a>
        </div>

    </div>
</div>
@endsection
