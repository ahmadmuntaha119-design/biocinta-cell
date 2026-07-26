@extends('layouts.app')
@section('title', 'Puzzle Cocok Organel')
@section('meta_description', 'Game harian cocok-organel BioCinta Cell — Cocokkan fungsi organel sel dengan nama yang benar untuk mendapatkan poin!')

@push('head')
@php
    $puzzleJson = $puzzle ? [
        'id'         => $puzzle->id,
        'targets'    => $puzzle->getTargetsArray(),
        'organelles' => $puzzle->getShuffledOrganellesArray(),
        'date'       => $puzzle->publish_date->format('d M Y'),
    ] : null;
    $todayScoreVal = $todayScore ? $todayScore->score : null;
@endphp
<script>
    window.__puzzleData    = {!! json_encode($puzzleJson) !!};
    window.__alreadyPlayed = {{ $alreadyPlayed ? 'true' : 'false' }};
    window.__todayScore    = {!! json_encode($todayScoreVal) !!};
</script>
@endpush

@section('content')

<div class="bg-[#f7f9fb] min-h-[calc(100vh-80px)] pb-16">
    {{-- Hero --}}
    <header class="relative overflow-hidden bg-gradient-to-br from-emerald-950 via-emerald-900 to-emerald-950 py-16 md:py-20 shadow-md">
        <div class="absolute inset-0 pointer-events-none opacity-[0.04]">
            <svg class="absolute w-full h-full" viewBox="0 0 800 400" xmlns="http://www.w3.org/2000/svg">
                <!-- Geometric motif for Madrasah Digital contemporary feel -->
                <path d="M 0,0 L 800,400 M 0,400 L 800,0" stroke="#ffffff" stroke-width="1"/>
                <circle cx="400" cy="200" r="150" fill="none" stroke="#ffffff" stroke-width="1.5"/>
                <circle cx="400" cy="200" r="250" fill="none" stroke="#ffffff" stroke-width="1"/>
                <rect x="250" y="50" width="300" height="300" fill="none" stroke="#ffffff" stroke-width="1" transform="rotate(45 400 200)"/>
            </svg>
        </div>
        <div class="relative z-10 px-4 md:px-16 max-w-[1440px] mx-auto text-center">
            <h1 class="font-serif font-bold text-white text-3xl md:text-4xl lg:text-5xl tracking-tight mb-4 drop-shadow-sm">
                Puzzle Cocok Organel
            </h1>
            <p class="text-emerald-100/90 text-sm md:text-base max-w-xl mx-auto leading-relaxed">
                Uji pemahaman selmu hari ini! Cocokkan 4 fungsi organel dengan nama yang tepat dan dapatkan hingga <span class="text-amber-300 font-semibold underline decoration-amber-400 decoration-wavy underline-offset-4">100 poin</span> untuk leaderboard harian.
            </p>
        </div>
        {{-- Asymmetric wave divider --}}
        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-[0]">
            <svg class="relative block w-full h-[40px] md:h-[60px]" viewBox="0 0 1440 80" preserveAspectRatio="none">
                <path fill="#f7f9fb" d="M0,32 C320,72 960,8 1440,48 L1440,80 L0,80 Z"/>
            </svg>
        </div>
    </header>

    {{-- Main Game Area --}}
    <main class="px-4 md:px-16 py-8 max-w-[1440px] mx-auto"
          x-data="puzzleGame(window.__puzzleData, window.__alreadyPlayed, window.__todayScore)"
    >

        {{-- ─── NO PUZZLE TODAY ─── --}}
        @if(!$puzzle)
            <div class="max-w-xl mx-auto text-center py-16 bg-white border border-slate-100 rounded-3xl shadow-sm p-8 hover:shadow-md transition-all duration-300">
                <div class="w-20 h-20 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center mx-auto mb-6 shadow-inner">
                    <span class="material-symbols-outlined text-slate-400" style="font-size:36px;">event_busy</span>
                </div>
                <h2 class="font-serif font-bold text-2xl text-slate-800 mb-3">Belum ada tantangan hari ini</h2>
                <p class="text-slate-500 text-sm leading-relaxed mb-8">
                    Guru Biologimu belum menjadwalkan soal puzzle untuk hari ini. Pantau terus dan coba lagi besok!
                </p>
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-2 bg-emerald-900 text-white px-6 py-3 rounded-xl font-semibold text-sm hover:bg-emerald-800 hover:shadow-lg hover:-translate-y-0.5 transition-all min-h-[44px]">
                    <span class="material-symbols-outlined" style="font-size:18px;">home</span>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>

        {{-- ─── ALREADY PLAYED & SUBMITTED ─── --}}
        @elseif($alreadyPlayed)
            <div class="max-w-xl mx-auto text-center py-10 bg-white border border-slate-100 rounded-3xl shadow-sm p-8 hover:shadow-md transition-all duration-300">
                <div class="w-20 h-20 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center mx-auto mb-6 shadow-inner">
                    <span class="material-symbols-outlined text-emerald-600" style="font-size:36px;">check_circle</span>
                </div>
                <h2 class="font-serif font-bold text-2xl text-emerald-950 mb-3">Kamu sudah bermain hari ini!</h2>
                <p class="text-slate-600 text-sm leading-relaxed mb-6">
                    Skormu: <strong class="text-2xl text-amber-600 font-serif font-bold px-2 py-0.5 bg-amber-50 rounded-lg border border-amber-200">{{ $todayScore->score }}</strong> poin sudah tersimpan di leaderboard.
                </p>

                {{-- Replay option --}}
                <div class="bg-emerald-50/50 rounded-2xl border border-emerald-100/70 p-5 text-left mb-8">
                    <p class="text-sm font-semibold text-emerald-900 flex items-center gap-1.5 mb-1.5">
                        <span class="material-symbols-outlined text-emerald-700" style="font-size:18px;">lightbulb</span>
                        Mode Latihan
                    </p>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Kamu tetap bisa bermain kembali untuk melatih kemampuanmu. Namun, perlu diingat bahwa skor latihan <strong>tidak akan</strong> mengubah skor Anda di leaderboard.
                    </p>
                </div>

                <div class="flex gap-4 justify-center flex-wrap">
                    <button
                        @click="startPracticeMode()"
                        class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 hover:shadow-md hover:-translate-y-0.5 text-white px-6 py-3 rounded-xl font-bold text-sm transition-all min-h-[44px]">
                        <span class="material-symbols-outlined" style="font-size:18px;">replay</span>
                        <span>Coba Lagi (Mode Latihan)</span>
                    </button>
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center gap-2 bg-slate-100 text-slate-700 px-6 py-3 rounded-xl font-semibold text-sm hover:bg-slate-200 hover:-translate-y-0.5 transition-all min-h-[44px]">
                        <span class="material-symbols-outlined" style="font-size:18px;">home</span>
                        <span>Beranda</span>
                    </a>
                </div>
            </div>

            {{-- The actual game below (hidden until practice mode) --}}
            <div x-show="practiceMode" x-transition style="display:none;" class="mt-8">
                @include('pages.puzzle._game')
            </div>

        {{-- ─── ACTIVE GAME ─── --}}
        @else
            @include('pages.puzzle._game')
        @endif

    </main>
</div>

@push('scripts')
<script>
function puzzleGame(puzzleData, alreadyPlayed, todayScore) {
    return {
        // State
        practiceMode:        false,
        selectedTargetIndex: null,
        submitted:           false,
        submitting:          false,
        resultMessage:       null,

        // Game data (populated from window.__puzzleData)
        targets:    puzzleData ? puzzleData.targets.map((t, i) => ({
            ...t,
            matchedWith: null,
        })) : [],
        organelles: puzzleData ? puzzleData.organelles.map(o => ({
            ...o,
            isUsed: false,
        })) : [],

        startPracticeMode() {
            this.practiceMode = true;
            this.resetPuzzle();
        },

        selectTarget(index) {
            if (this.targets[index].matchedWith) {
                // Allow re-selection: unassign current answer
                let prev = this.targets[index].matchedWith;
                this.organelles.find(o => o.id === prev.id).isUsed = false;
                this.targets[index].matchedWith = null;
            }
            this.selectedTargetIndex = index;
        },

        matchOrganelle(organelle) {
            if (this.submitted) return;
            if (this.selectedTargetIndex === null) {
                // Pulse the targets to hint user
                return;
            }
            let target = this.targets[this.selectedTargetIndex];
            if (target.matchedWith) {
                this.organelles.find(o => o.id === target.matchedWith.id).isUsed = false;
            }
            target.matchedWith = organelle;
            organelle.isUsed   = true;
            this.selectedTargetIndex = null;
        },

        calculateCorrectCount() {
            return this.targets.filter(t => t.matchedWith && t.matchedWith.name === t.correctName).length;
        },

        calculateScore() {
            return this.calculateCorrectCount() * 25;
        },

        isGameFinished() {
            return this.targets.every(t => t.matchedWith !== null);
        },

        async submitPuzzleScore() {
            if (this.submitting || this.submitted) return;

            if (this.practiceMode) {
                // Mode latihan: evaluasi skor secara lokal tanpa kirim ke server
                this.submitted     = true;
                this.resultMessage = `Latihan selesai! Skor Anda: ${this.calculateScore()} PTS (${this.calculateCorrectCount()} / 4 benar).`;
                return;
            }

            this.submitting = true;
            const finalScore = this.calculateScore();

            try {
                const res = await fetch('/api/puzzle/submit', {
                    method:  'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        score:          finalScore,
                        puzzle_bank_id: window.__puzzleData.id,
                    }),
                });

                const data = await res.json();
                this.submitted     = true;
                this.resultMessage = data.message;

                if (data.earned_points > 0) {
                    setTimeout(() => { window.location.href = '/'; }, 4000);
                }
            } catch (err) {
                this.resultMessage = 'Terjadi kesalahan jaringan. Coba lagi.';
            } finally {
                this.submitting = false;
            }
        },

        resetPuzzle() {
            this.selectedTargetIndex = null;
            this.submitted           = false;
            this.resultMessage       = null;
            this.targets.forEach(t  => t.matchedWith = null);
            this.organelles.forEach(o => o.isUsed = false);
            // Re-shuffle organelles
            for (let i = this.organelles.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [this.organelles[i], this.organelles[j]] = [this.organelles[j], this.organelles[i]];
            }
        },
    };
}
</script>
@endpush

@endsection
