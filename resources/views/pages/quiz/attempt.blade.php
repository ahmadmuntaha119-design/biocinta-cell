@extends('layouts.app')
@section('title', 'Kuis: ' . ($quiz->title ?? 'Sedang Berlangsung'))
@section('meta_description', 'Halaman pengerjaan kuis BioCinta Cell. Fokus dan kerjakan dengan jujur.')

@push('head')
<style>
    /* Sembunyikan navbar saat mode full-screen kuis */
    #top-nav { display: none !important; }
    body { padding-top: 0 !important; }

    /* Animasi timer merah berkedip */
    @keyframes timer-pulse {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.5; }
    }
    .timer-urgent { animation: timer-pulse 0.8s ease-in-out infinite; }

    /* Overlay darurat peringatan curang */
    @keyframes warning-shake {
        0%, 100% { transform: translateX(0); }
        25%      { transform: translateX(-8px); }
        75%      { transform: translateX(8px); }
    }
    .shake { animation: warning-shake 0.3s ease-in-out 3; }

    /* Radio button custom yang lebih besar */
    .option-label { cursor: pointer; transition: all 0.15s ease; }
    .option-label:has(input:checked) {
        background: rgb(6 78 59 / 0.08);
        border-color: rgb(6 78 59);
    }
    .option-label:has(input:checked) .option-indicator {
        background: rgb(6 78 59);
        border-color: rgb(6 78 59);
    }
    .option-label:has(input:checked) .option-indicator::after {
        content: '';
        display: block;
        width: 8px;
        height: 8px;
        background: white;
        border-radius: 50%;
        margin: auto;
    }
    .option-indicator {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid #cbd5e1;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
    }
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div
    x-data="quizEngine()"
    x-init="init()"
    class="min-h-screen bg-slate-50"
>

    {{-- ===============================================================
         STICKY QUIZ HEADER (Timer + Progress)
         =============================================================== --}}
    <header class="fixed top-0 w-full z-50 bg-emerald-950 text-white shadow-xl">
        <div class="max-w-3xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
            {{-- Judul Kuis --}}
            <div class="flex-1 min-w-0">
                <p class="text-emerald-400 text-xs font-semibold uppercase tracking-wider">Sedang Berlangsung</p>
                <h2 class="font-serif font-bold text-sm md:text-base text-white truncate">{{ $quiz->title }}</h2>
            </div>

            {{-- Timer --}}
            <div
                :class="remainingSeconds <= 120 ? 'text-red-300 timer-urgent' : 'text-amber-300'"
                class="flex items-center gap-2 flex-shrink-0"
            >
                <span class="material-symbols-outlined" style="font-size: 22px;">timer</span>
                <span x-text="formatTime(remainingSeconds)" class="font-mono text-2xl font-bold tracking-wider"></span>
            </div>

            @if($quiz->timer_mode === 'question')
            {{-- Progress Soal (Mode Per-Soal) --}}
            <div class="flex-shrink-0 text-center">
                <p class="text-emerald-400 text-xs">Soal</p>
                <p class="font-bold text-white text-sm">{{ $questionNumber ?? 1 }} / {{ $totalQuestions }}</p>
            </div>
            @endif
        </div>

        @if($quiz->timer_mode === 'question')
        {{-- Progress Bar --}}
        <div class="h-1 bg-emerald-800/60">
            <div
                class="h-full bg-amber-400 transition-all duration-300"
                :style="`width: ${(remainingSeconds / {{ $currentQuestion->duration_seconds ?? 30 }}) * 100}%`"
            ></div>
        </div>
        @endif
    </header>

    {{-- ===============================================================
         MAIN CONTENT — Padding untuk header fixed
         =============================================================== --}}
    <main class="pt-[80px] md:pt-[72px] pb-16">
        <div class="max-w-3xl mx-auto px-4 py-6">

            @if(session('success'))
                <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-xl text-sm flex items-center gap-2">
                    <span>✅</span><span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- ============================================================
                 MODE GLOBAL: Semua soal tampil sekaligus
                 ============================================================ --}}
            @if($quiz->timer_mode === 'quiz')
            <form
                id="quiz-form"
                action="{{ route('quiz.submit') }}"
                method="POST"
                @submit.prevent="handleGlobalSubmit($event)"
            >
                @csrf
                <div class="space-y-6">
                    @foreach($questions as $i => $question)
                        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                            {{-- Nomor & Teks Soal --}}
                            <div class="px-6 pt-6 pb-4 border-b border-slate-100">
                                <div class="flex items-start gap-3">
                                    <span class="w-8 h-8 rounded-full bg-emerald-900 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">{{ $i + 1 }}</span>
                                    <p class="text-slate-900 font-medium leading-relaxed text-base">{{ $question->question_text }}</p>
                                </div>
                            </div>

                            {{-- Pilihan Jawaban --}}
                            <div class="p-4 space-y-2">
                                @foreach(['a', 'b', 'c', 'd'] as $opt)
                                    <label class="option-label flex items-center gap-4 px-4 py-3.5 rounded-xl border border-slate-200 hover:border-emerald-200 hover:bg-emerald-50/40 min-h-[52px]">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $opt }}" class="sr-only">
                                        <div class="option-indicator"></div>
                                        <div class="flex items-center gap-3 flex-1">
                                            <span class="w-6 h-6 rounded-full border border-slate-300 flex items-center justify-center text-xs font-bold text-slate-500 flex-shrink-0">{{ strtoupper($opt) }}</span>
                                            <span class="text-slate-700 text-sm leading-relaxed">{{ $question->{'option_' . $opt} }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    {{-- Tombol Submit Global --}}
                    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-6 text-center">
                        <p class="text-sm text-slate-500 mb-4">Pastikan semua soal sudah dijawab sebelum mengirimkan jawaban.</p>
                        <button
                            type="submit"
                            x-bind:disabled="loading"
                            class="px-8 py-3.5 bg-emerald-900 hover:bg-emerald-800 text-white font-bold rounded-xl text-base shadow-md min-h-[52px] flex items-center gap-2 mx-auto transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span x-show="!loading" class="material-symbols-outlined" style="font-size: 22px;">check_circle</span>
                            <span x-show="loading" class="material-symbols-outlined animate-spin" style="font-size: 22px;">progress_activity</span>
                            <span x-text="loading ? 'Mengirim Jawaban...' : 'Kirim Semua Jawaban'"></span>
                        </button>
                    </div>
                </div>
            </form>

            {{-- ============================================================
                 MODE PER-SOAL: Satu soal per halaman
                 ============================================================ --}}
            @elseif(isset($currentQuestion))
            <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                {{-- Teks Soal --}}
                <div class="px-6 pt-8 pb-6 border-b border-slate-100">
                    <div class="flex items-start gap-4">
                        <span class="w-10 h-10 rounded-full bg-emerald-900 text-white font-bold flex items-center justify-center flex-shrink-0 shadow-md text-lg">{{ $questionNumber }}</span>
                        <p class="text-slate-900 font-semibold leading-relaxed text-lg">{{ $currentQuestion->question_text }}</p>
                    </div>
                </div>

                {{-- Pilihan Jawaban --}}
                <form
                    id="question-form"
                    action="{{ route('quiz.submit') }}"
                    method="POST"
                    @submit.prevent="handlePerQuestionSubmit($event)"
                    class="p-5 space-y-3"
                >
                    @csrf
                    @foreach(['a', 'b', 'c', 'd'] as $opt)
                        <label class="option-label flex items-center gap-4 px-5 py-4 rounded-xl border border-slate-200 hover:border-emerald-200 hover:bg-emerald-50/40 min-h-[60px]">
                            <input type="radio" name="answer" value="{{ $opt }}" class="sr-only">
                            <div class="option-indicator"></div>
                            <div class="flex items-center gap-3 flex-1">
                                <span class="w-8 h-8 rounded-full border-2 border-slate-300 flex items-center justify-center text-sm font-bold text-slate-500 flex-shrink-0">{{ strtoupper($opt) }}</span>
                                <span class="text-slate-700 text-base leading-relaxed">{{ $currentQuestion->{'option_' . $opt} }}</span>
                            </div>
                        </label>
                    @endforeach

                    {{-- Tombol Lanjut / Selesai --}}
                    <div class="pt-4 flex justify-end">
                        <button
                            type="submit"
                            x-bind:disabled="loading"
                            class="px-8 py-3.5 bg-emerald-900 hover:bg-emerald-800 text-white font-bold rounded-xl text-base shadow-md min-h-[52px] flex items-center gap-2 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span x-show="!loading" class="material-symbols-outlined" style="font-size: 22px;">
                                {{ $questionNumber < $totalQuestions ? 'arrow_forward' : 'check_circle' }}
                            </span>
                            <span x-show="loading" class="material-symbols-outlined animate-spin" style="font-size: 22px;">progress_activity</span>
                            <span x-text="loading ? 'Menyimpan...' : '{{ $questionNumber < $totalQuestions ? "Lanjut" : "Selesai" }}'"></span>
                        </button>
                    </div>
                </form>
            </div>
            @endif

        </div>
    </main>

    {{-- ===============================================================
         MODAL PERINGATAN CURANG (Overlay merah)
         =============================================================== --}}
    <div
        x-show="showWarning"
        x-cloak
        class="fixed inset-0 z-[100] bg-red-950/95 backdrop-blur-sm flex items-center justify-center px-6"
        style="display: none;"
    >
        <div class="text-center max-w-sm" :class="shake ? 'shake' : ''">
            <div class="w-20 h-20 rounded-full bg-red-500/30 border-4 border-red-400 flex items-center justify-center mx-auto mb-6 animate-pulse">
                <span class="material-symbols-outlined text-red-300" style="font-size: 48px;">warning</span>
            </div>
            <h2 class="font-serif font-bold text-2xl text-red-200 mb-2">⚠️ Peringatan!</h2>
            <p class="text-red-300 text-base mb-4 leading-relaxed">
                Anda terdeteksi <strong class="text-red-200">meninggalkan halaman kuis</strong>.<br>
                Pelanggaran ini telah dicatat ke server.
            </p>
            <div class="bg-red-900/50 border border-red-700/50 rounded-xl px-4 py-3 mb-6">
                <p class="text-red-300 text-xs">Jumlah Pelanggaran: <strong class="text-red-100 text-lg" x-text="warningCount"></strong></p>
            </div>
            <p class="text-red-400/80 text-xs mb-6">Modal ini akan tertutup otomatis dalam <strong x-text="closingIn"></strong> detik...</p>
            <div class="h-1 bg-red-900/50 rounded-full overflow-hidden">
                <div class="h-full bg-red-400 transition-all duration-1000" :style="`width: ${(closingIn / 3) * 100}%`"></div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function quizEngine() {
    return {
        remainingSeconds: {{ $remainingSeconds }},
        timerInterval: null,
        loading: false,
        submitted: false,
        showWarning: false,
        shake: false,
        warningCount: 0,
        closingIn: 3,
        warningTimeout: null,
        audioCtx: null,
        lastWarningTime: 0,

        init() {
            // Start countdown timer
            this.timerInterval = setInterval(() => {
                if (this.submitted) { clearInterval(this.timerInterval); return; }
                if (this.remainingSeconds > 0) {
                    this.remainingSeconds--;
                } else {
                    clearInterval(this.timerInterval);
                    this.autoSubmit();
                }
            }, 1000);

            // Anti-cheat: Tab/Window visibility change
            document.addEventListener('visibilitychange', () => {
                if (this.loading || this.submitted) return; 
                if (document.hidden) {
                    this.triggerCheatWarning();
                }
            });

            // Anti-cheat: Window blur (pindah ke layar lain)
            window.addEventListener('blur', () => {
                if (this.loading || this.submitted) return; 
                this.triggerCheatWarning();
            });
        },

        // -------------------------------------------------------------------
        // FORMAT TIMER
        // -------------------------------------------------------------------
        formatTime(seconds) {
            const s = Math.max(0, seconds);
            const m = Math.floor(s / 60);
            const sec = s % 60;
            return `${String(m).padStart(2, '0')}:${String(sec).padStart(2, '0')}`;
        },

        // -------------------------------------------------------------------
        // ALARM SIRENE (Web Audio API — tidak perlu file eksternal)
        // -------------------------------------------------------------------
        playAlarm() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();

                // Alarm peringatan darurat/gempa (Sangat berisik & volume 100%)
                const playTone = (freq, startTime, duration) => {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();

                    osc.connect(gain);
                    gain.connect(ctx.destination);

                    osc.type = 'square'; // Suara kasar yang tajam
                    osc.frequency.setValueAtTime(freq, startTime);

                    // Volume 1.0 (Maksimal)
                    gain.gain.setValueAtTime(1.0, startTime);
                    gain.gain.setValueAtTime(1.0, startTime + duration - 0.05);
                    gain.gain.linearRampToValueAtTime(0, startTime + duration);

                    osc.start(startTime);
                    osc.stop(startTime + duration);
                };

                const now = ctx.currentTime;
                // Pola melengking bergantian cepat (Neee-Nooo-Neee-Nooo) selama ~3 detik
                for (let i = 0; i < 12; i++) {
                    playTone(900, now + (i * 0.25), 0.12);
                    playTone(600, now + (i * 0.25) + 0.12, 0.12);
                }
            } catch (e) {
                console.warn('Web Audio API tidak tersedia:', e);
            }
        },

        // -------------------------------------------------------------------
        // TRIGGER PERINGATAN CURANG
        // -------------------------------------------------------------------
        triggerCheatWarning() {
            // Sentralisasi throttle: hanya catat 1 pelanggaran per 3 detik
            const now = Date.now();
            if (now - this.lastWarningTime < 3000) return;
            this.lastWarningTime = now;

            this.warningCount++;
            this.playAlarm();

            // Kirim ke server via Fetch API
            fetch('{{ route("quiz.record-warning") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({}),
            }).catch(() => {}); // Silent fail jika offline sebentar

            // Tampilkan modal peringatan
            this.showWarning = true;
            this.shake = true;
            this.closingIn = 3;

            // Reset shake setelah animasi selesai
            setTimeout(() => { this.shake = false; }, 1000);

            // Countdown penutup modal 3 detik
            clearTimeout(this.warningTimeout);
            const countdown = setInterval(() => {
                this.closingIn--;
                if (this.closingIn <= 0) {
                    clearInterval(countdown);
                    this.showWarning = false;
                }
            }, 1000);
        },

        // -------------------------------------------------------------------
        // AUTO-SUBMIT (saat timer habis) — langsung submit tanpa cek loading
        // -------------------------------------------------------------------
        autoSubmit() {
            if (this.submitted) return; // Guard: cegah submit berulang
            this.submitted = true;
            this.loading = true;
            @if($quiz->timer_mode === 'quiz')
                const form = document.getElementById('quiz-form');
                if (form) form.submit();
            @elseif(isset($currentQuestion))
                const form = document.getElementById('question-form');
                if (form) form.submit();
            @endif
        },

        // -------------------------------------------------------------------
        // HANDLE SUBMIT GLOBAL (mengumpulkan semua jawaban)
        // -------------------------------------------------------------------
        handleGlobalSubmit(event) {
            if (this.submitted) return;
            this.submitted = true;
            this.loading = true;
            clearInterval(this.timerInterval);
            event.target.submit();
        },

        // -------------------------------------------------------------------
        // HANDLE SUBMIT PER-SOAL
        // -------------------------------------------------------------------
        handlePerQuestionSubmit(event) {
            if (this.submitted) return;
            this.submitted = true;
            this.loading = true;
            clearInterval(this.timerInterval);
            event.target.submit();
        },
    };
}
</script>
@endpush
