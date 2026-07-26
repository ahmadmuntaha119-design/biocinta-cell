@extends('layouts.app')
@section('title', 'Dashboard')
@section('meta_description', 'Dashboard utama BioCinta Cell — Platform Belajar Biologi Interaktif Berbasis Kurikulum CINTA MAN 2 Kebumen')

@section('content')

    {{-- ============================================================
    HERO SECTION — Premium
    ============================================================ --}}
    <header class="relative overflow-hidden bg-gradient-to-br from-emerald-950 via-emerald-900 to-emerald-950">

        {{-- Decorative Background Pattern Overlay --}}
        <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden">
            <svg class="absolute top-10 left-10 w-96 h-96 opacity-5 text-emerald-100 fill-current" viewBox="0 0 200 200"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M41.3,-71.5C53.7,-64.5,64,-53.1,71.2,-39.9C78.4,-26.7,82.5,-11.7,81.4,2.9C80.3,17.4,74.1,31.6,65.3,43.5C56.6,55.5,45.3,65.1,32.3,71.6C19.3,78.1,4.6,81.4,-10.1,80.1C-24.9,78.8,-39.8,72.9,-51.9,64.2C-64,55.5,-73.2,43.9,-78.9,30.5C-84.6,17.1,-86.7,1.8,-84.6,-13.2C-82.5,-28.3,-76.3,-43.2,-66,-53.8C-55.7,-64.4,-41.3,-70.7,-27.6,-76.9C-13.9,-83.1,-0.9,-89.1,12.1,-88.1C25.1,-87.1,30.1,-78.5,41.3,-71.5Z"
                    transform="translate(100 100)" />
            </svg>
            <svg class="absolute bottom-10 right-10 w-80 h-80 opacity-5 text-emerald-100 fill-current" viewBox="0 0 200 200"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M44.4,-77.2C56.9,-70.3,66.2,-56.3,73.1,-41.5C80,-26.8,84.5,-11.3,83.9,3.8C83.2,18.8,77.4,33.5,68.9,46C60.4,58.4,49.2,68.7,35.8,74.9C22.4,81,-9.6,83.1,-24.1,78C-38.6,73,-55.6,60.8,-66.4,45.9C-77.2,31,-81.8,13.4,-81.2,-3.6C-80.5,-20.5,-74.6,-36.8,-64.5,-48.9C-54.4,-61,-40.1,-68.9,-26.5,-75.2C-12.9,-81.4,1.4,-86,15.6,-84.8C29.8,-83.5,31.9,-84,44.4,-77.2Z"
                    transform="translate(100 100)" />
            </svg>
        </div>

        {{-- Content Layer --}}
        <div class="px-4 md:px-16 pt-24 pb-32 md:pb-40 max-w-[1440px] mx-auto text-center relative z-10">
            {{-- Headline --}}
            <h1 class="font-serif font-bold text-white mb-6 max-w-4xl mx-auto leading-tight
                       text-3xl sm:text-4xl md:text-5xl lg:text-6xl">
                Mengeksplorasi Keajaiban Sel<br class="hidden sm:block">
                dengan Kedalaman Spiritual
            </h1>
        </div>

        {{-- Premium Asymmetric Wave Divider (z-20) --}}
        <div class="absolute z-20 bottom-0 left-0 w-full overflow-hidden leading-[0]">
            <svg class="relative block w-full h-[50px] md:h-[90px]" viewBox="0 0 1440 100" preserveAspectRatio="none"
                xmlns="http://www.w3.org/2000/svg">
                <path fill="#f7f9fb" d="M0,40 C420,115 980,-15 1440,55 L1440,100 L0,100 Z"></path>
            </svg>
        </div>
    </header>

    {{-- ============================================================
    MAIN CONTENT
    ============================================================ --}}
    <main class="px-4 md:px-16 py-12 max-w-[1440px] mx-auto -mt-4 relative z-20">

        {{-- Section Header --}}
        <div class="mb-10 text-center md:text-left">
            <h2 class="font-serif font-bold text-2xl md:text-3xl text-primary mb-2">Menu Pembelajaran</h2>
            <p class="text-on-surface-variant text-sm md:text-base">Pilih aktivitas belajarmu hari ini</p>
        </div>

        {{-- ============================================================
        BENTO GRID — 6 Core Cards
        ============================================================ --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- CARD 1: Materi Interaktif Sel --}}
            <a href="{{ route('materials.show', 'sel-hewan') }}" id="card-materi-sel" class="glass-card rounded-2xl p-6 flex flex-col justify-between
                       hover:-translate-y-1 transition-all duration-300 group cursor-pointer"
                aria-label="Buka Materi Interaktif Sel Hewan dan Tumbuhan">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-secondary-container flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined" style="font-size: 28px;">magnification_small</span>
                        </div>
                        <span
                            class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors">
                            arrow_forward
                        </span>
                    </div>
                    <h3 class="font-serif font-semibold text-xl text-primary mb-2">Materi Interaktif Sel</h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed mb-6">
                        Jelajahi struktur Sel Hewan &amp; Tumbuhan melalui diagram organel yang dapat diklik.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span
                        class="px-3 py-1 rounded-full bg-secondary-fixed text-on-secondary-fixed-variant text-[11px] font-semibold uppercase tracking-wider">Sel
                        Hewan</span>
                    <span
                        class="px-3 py-1 rounded-full bg-secondary-fixed text-on-secondary-fixed-variant text-[11px] font-semibold uppercase tracking-wider">Sel
                        Tumbuhan</span>
                </div>
            </a>

            {{-- CARD 2: Alat Mikroskop --}}
            <a href="{{ route('materials.microscope') }}" id="card-mikroskop" class="glass-card rounded-2xl p-6 flex flex-col justify-between
                       hover:-translate-y-1 transition-all duration-300 group cursor-pointer"
                aria-label="Buka materi Pengenalan Alat Mikroskop">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-tertiary-container flex items-center justify-center text-tertiary-fixed">
                            <span class="material-symbols-outlined" style="font-size: 28px;">satellite</span>
                        </div>
                        <span
                            class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors">
                            arrow_forward
                        </span>
                    </div>
                    <h3 class="font-serif font-semibold text-xl text-primary mb-2">Alat Mikroskop</h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed mb-6">
                        Pelajari jenis-jenis mikroskop, perbedaan Cahaya vs Elektron, dan fungsi setiap komponen.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span
                        class="px-3 py-1 rounded-full bg-tertiary-fixed text-on-tertiary-fixed-variant text-[11px] font-semibold uppercase tracking-wider">Cahaya</span>
                    <span
                        class="px-3 py-1 rounded-full bg-tertiary-fixed text-on-tertiary-fixed-variant text-[11px] font-semibold uppercase tracking-wider">Elektron</span>
                    <span
                        class="px-3 py-1 rounded-full bg-tertiary-fixed text-on-tertiary-fixed-variant text-[11px] font-semibold uppercase tracking-wider">Binokuler</span>
                </div>
            </a>

            {{-- CARD 3: Laboratorium Maya --}}
            <a href="{{ route('labs.index') }}" id="card-lab-maya" class="glass-card rounded-2xl p-6 flex flex-col justify-between
                       hover:-translate-y-1 transition-all duration-300 group cursor-pointer"
                aria-label="Buka Laboratorium Maya Virtual">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-primary-container flex items-center justify-center text-primary-fixed">
                            <span class="material-symbols-outlined" style="font-size: 28px;">biotech</span>
                        </div>
                        <span
                            class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors">
                            arrow_forward
                        </span>
                    </div>
                    <h3 class="font-serif font-semibold text-xl text-primary mb-2">Laboratorium Maya</h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed mb-6">
                        Simulasi observasi sel dari tampilan makro ke mikro seperti pengamatan mikroskop nyata.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span
                        class="px-3 py-1 rounded-full bg-primary-fixed text-on-primary-fixed-variant text-[11px] font-semibold uppercase tracking-wider">Makro
                        → Mikro</span>
                    <span
                        class="px-3 py-1 rounded-full bg-primary-fixed text-on-primary-fixed-variant text-[11px] font-semibold uppercase tracking-wider">Simulasi</span>
                </div>
            </a>

            {{-- CARD 4: Puzzle Cocok Organel --}}
            <a href="{{ route('puzzle.index') }}" id="card-puzzle" class="glass-card rounded-2xl p-6 flex flex-col justify-between
                       hover:-translate-y-1 transition-all duration-300 group cursor-pointer"
                aria-label="Buka Puzzle Cocok Organel Harian">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-error-container flex items-center justify-center text-on-error-container">
                            <span class="material-symbols-outlined" style="font-size: 28px;">extension</span>
                        </div>
                        <span
                            class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors">
                            arrow_forward
                        </span>
                    </div>
                    <h3 class="font-serif font-semibold text-xl text-primary mb-2">Puzzle Cocok Organel</h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed mb-6">
                        Cocokkan 4 fungsi organel dengan nama yang tepat. Dapatkan hingga 100 poin!
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span
                        class="px-3 py-1 rounded-full bg-surface-container text-on-surface-variant text-[11px] font-semibold uppercase tracking-wider">Maks.
                        100 Poin</span>
                    <span
                        class="px-3 py-1 rounded-full bg-surface-container text-on-surface-variant text-[11px] font-semibold uppercase tracking-wider">Harian</span>
                </div>
            </a>

            {{-- CARD 5: Projek Kolaboratif --}}
            <a href="{{ route('projects.index') }}" id="card-projek" class="glass-card rounded-2xl p-6 flex flex-col justify-between
                       hover:-translate-y-1 transition-all duration-300 group cursor-pointer"
                aria-label="Buka halaman Projek Kolaboratif">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-secondary-container flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined" style="font-size: 28px;">video_library</span>
                        </div>
                        <span
                            class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors">
                            arrow_forward
                        </span>
                    </div>
                    <h3 class="font-serif font-semibold text-xl text-primary mb-2">Projek Kolaboratif</h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed mb-6">
                        Kumpulkan link video proyek kelompokmu dan lihat evaluasi langsung dari Guru Biologi.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span
                        class="px-3 py-1 rounded-full bg-error-container text-on-error-container text-[11px] font-semibold uppercase tracking-wider">YouTube</span>
                    <span
                        class="px-3 py-1 rounded-full bg-primary-fixed text-on-primary-fixed-variant text-[11px] font-semibold uppercase tracking-wider">Google
                        Drive</span>
                </div>
            </a>

            {{-- CARD 6: Ruang Tadabbur & Peringkat --}}
            <a href="{{ route('reflection.create') }}" id="card-tadabbur" class="glass-card rounded-2xl p-6 flex flex-col justify-between
                       hover:-translate-y-1 transition-all duration-300 group cursor-pointer"
                aria-label="Buka Ruang Tadabbur dan Papan Peringkat">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <img src="{{ asset('images/lab/KBC.png') }}" class="w-12 h-12 rounded-xl bg-error-container flex items-center justify-center text-on-error-container" alt="KBC">
                        </div>
                        <span
                            class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors">
                            arrow_forward
                        </span>
                    </div>
                    <h3 class="font-serif font-semibold text-xl text-primary mb-2">Kurikulum Berbasis CINTA dan Peringkat</h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed mb-6">
                        Isi jurnal refleksi spiritual Kurikulum Berbasis CINTA dan lihat posisimu di papan peringkat kelas.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span
                        class="px-3 py-1 rounded-full bg-tertiary-fixed text-on-tertiary-fixed-variant text-[11px] font-semibold uppercase tracking-wider">Refleksi
                        Islami</span>
                    <span
                        class="px-3 py-1 rounded-full bg-secondary-fixed text-on-secondary-fixed-variant text-[11px] font-semibold uppercase tracking-wider">Top
                        10</span>
                </div>
            </a>

        </div>{{-- End Bento Grid --}}

        {{-- ============================================================
        WIDE QUIZ CARD
        ============================================================ --}}
        <section class="mt-20 glass-card rounded-2xl p-8 md:p-12 relative overflow-hidden group">
            {{-- Decorative blob --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary-fixed/20 rounded-full blur-3xl
                        -mr-32 -mt-32 transition-transform duration-700 group-hover:scale-125 pointer-events-none"
                aria-hidden="true"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6 md:gap-8">
                {{-- Info --}}
                <div class="flex flex-col md:flex-row items-center md:items-start gap-5 text-center md:text-left">
                    <div
                        class="w-16 h-16 rounded-2xl bg-primary-container flex items-center justify-center text-primary-fixed shadow-lg flex-shrink-0">
                        <span class="material-symbols-outlined"
                            style="font-size: 36px; font-variation-settings: 'FILL' 1;">quiz</span>
                    </div>
                    <div class="max-w-xl">
                        <h2 class="font-serif font-semibold text-2xl md:text-3xl text-primary mb-3">Kuis Kelas</h2>
                        <p class="text-on-surface-variant text-sm md:text-base leading-relaxed">
                            Kuis dibuka oleh Guru Biologi dengan token 6 digit. Pantau pengumuman dari gurumu untuk memulai
                            sesi evaluasi hari ini.
                        </p>
                    </div>
                </div>

                {{-- CTA Button --}}
                <a href="{{ route('quiz.join') }}" id="btn-masuk-kuis" class="flex-shrink-0 bg-amber-500 hover:bg-amber-600 text-white
                           px-8 py-4 rounded-full font-semibold text-sm shadow-md
                           hover:shadow-xl hover:-translate-y-0.5 active:scale-95
                           transition-all flex items-center gap-2 min-h-[52px]">
                    Masuk Kuis
                    <span class="material-symbols-outlined" style="font-size: 20px;">login</span>
                </a>
            </div>
        </section>

        {{-- ============================================================
        GOOGLE FORM ANGKET — Tampil hanya jika admin sudah set URL
        ============================================================ --}}
        @if($gformUrl)
            <section class="mt-12">
                <div
                    class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-800 to-emerald-950 p-6 md:p-8 shadow-lg border border-emerald-700/30">
                    {{-- Decorative blob --}}
                    <div class="absolute -right-8 -top-8 w-40 h-40 bg-white/5 rounded-full pointer-events-none"></div>
                    <div class="absolute -right-4 -bottom-8 w-24 h-24 bg-white/5 rounded-full pointer-events-none"></div>

                    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center gap-5">
                        {{-- Text --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-emerald-200 text-xs font-semibold uppercase tracking-wider mb-1">Angket Penelitian
                            </p>
                            <p class="text-emerald-200 text-xs md:text-sm leading-relaxed">
                                Pendapatmu sangat berarti untuk meningkatkan kualitas pembelajaran. Isi angket singkat dari guru
                                biologimu sekarang!
                            </p>
                        </div>

                        {{-- CTA Button --}}
                        <a href="{{ $gformUrl }}" target="_blank" rel="noopener noreferrer"
                            class="flex-shrink-0 flex items-center gap-2 px-6 py-3 bg-amber-500 text-white hover:bg-amber-600 font-bold text-sm rounded-xl shadow-md transition-all hover:scale-105 active:scale-95 min-h-[48px] w-full sm:w-auto justify-center">
                            Isi Angket Sekarang
                        </a>
                    </div>
                </div>
            </section>
        @endif

        {{-- ============================================================
        FEEDBACK WEBSITE CARD — Premium
        ============================================================ --}}
        <section class="mt-12 glass-card rounded-2xl p-6 md:p-8 relative overflow-hidden group border border-primary/10"
            x-data="feedbackForm()">
            <div class="relative z-10 space-y-6">
                <div class="flex items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-xl bg-tertiary-container flex items-center justify-center text-tertiary-fixed shadow-md flex-shrink-0">
                        <span class="material-symbols-outlined" style="font-size: 26px;">rate_review</span>
                    </div>
                    <div>
                        <h2 class="font-serif font-semibold text-lg md:text-xl text-primary">Umpan Balik BioCinta Cell</h2>
                        <p class="text-on-surface-variant text-xs md:text-sm">Bagikan pengalaman belajarmu, saran
                            pelajaran, atau laporkan kendala sistem.</p>
                    </div>
                </div>

                <form action="{{ route('feedback.store') }}" method="POST" class="space-y-4" @submit="submitStart()">
                    @csrf

                    {{-- Kategori (Pill Selector) --}}
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-primary block">Kategori Masukan</label>
                        <div class="grid grid-cols-3 gap-3">
                            <label
                                class="relative flex flex-col items-center justify-center p-3 rounded-xl border-2 cursor-pointer transition-all duration-205 min-h-[64px]"
                                :class="category === 'bug' ? 'bg-red-50 border-red-500 text-red-800' : 'bg-surface-container-low border-slate-200 text-slate-600 hover:bg-slate-50'">
                                <input type="radio" name="category" value="bug" x-model="category"
                                    class="absolute opacity-0" required>
                                <span class="material-symbols-outlined"
                                    :class="category === 'bug' ? 'text-red-600' : 'text-slate-400'"
                                    style="font-size:22px;">bug_report</span>
                                <span class="text-[10px] font-bold mt-1 uppercase tracking-wider">Error</span>
                            </label>

                            <label
                                class="relative flex flex-col items-center justify-center p-3 rounded-xl border-2 cursor-pointer transition-all duration-205 min-h-[64px]"
                                :class="category === 'suggestion' ? 'bg-blue-50 border-blue-500 text-blue-800' : 'bg-surface-container-low border-slate-200 text-slate-600 hover:bg-slate-50'">
                                <input type="radio" name="category" value="suggestion" x-model="category"
                                    class="absolute opacity-0" required>
                                <span class="material-symbols-outlined"
                                    :class="category === 'suggestion' ? 'text-blue-600' : 'text-slate-400'"
                                    style="font-size:22px;">lightbulb</span>
                                <span class="text-[10px] font-bold mt-1 uppercase tracking-wider">Saran</span>
                            </label>

                            <label
                                class="relative flex flex-col items-center justify-center p-3 rounded-xl border-2 cursor-pointer transition-all duration-205 min-h-[64px]"
                                :class="category === 'appreciation' ? 'bg-amber-50 border-amber-500 text-amber-800' : 'bg-surface-container-low border-slate-200 text-slate-600 hover:bg-slate-50'">
                                <input type="radio" name="category" value="appreciation" x-model="category"
                                    class="absolute opacity-0" required>
                                <span class="material-symbols-outlined"
                                    :class="category === 'appreciation' ? 'text-amber-600' : 'text-slate-400'"
                                    style="font-size:22px;">sentiment_very_satisfied</span>
                                <span class="text-[10px] font-bold mt-1 uppercase tracking-wider">Apresiasi</span>
                            </label>
                        </div>
                    </div>

                    {{-- Textarea --}}
                    <div class="space-y-1.5">
                        <label for="feedback_message" class="text-xs font-bold text-primary block">Tulis Masukan
                            Anda</label>
                        <textarea id="feedback_message" name="message" rows="3" x-model="message" required
                            placeholder="Contoh: Aplikasi ini sangat membantu belajar sel! Saran saya agar gambar stomata bisa diperbesar saat di-klik..."
                            class="w-full text-xs px-4 py-3 rounded-xl border border-slate-200 bg-surface-container-low resize-none outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all"></textarea>
                        <div class="flex justify-between text-[10px] text-slate-400">
                            <span x-text="message.length < 10 ? 'Min. 10 karakter' : ''"
                                :class="message.length >= 10 ? 'text-emerald-600 font-medium' : 'text-red-500'"></span>
                            <span><span x-text="message.length"></span> / 2000 karakter</span>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" :disabled="message.length < 10 || submitting" class="w-full bg-slate-800 hover:bg-slate-900 disabled:opacity-50 disabled:cursor-not-allowed text-white
                               px-5 py-3 rounded-full font-semibold text-xs shadow-md
                               hover:shadow-lg transition-all flex items-center justify-center gap-2 min-h-[48px]">
                        <span x-show="!submitting" class="flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined" style="font-size: 16px;">send</span>
                            Kirim Umpan Balik
                        </span>
                        <span x-show="submitting" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-3.5 w-3.5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
                            </svg>
                            Mengirim...
                        </span>
                    </button>

                </form>
            </div>
        </section>

        {{-- Spacer bawah --}}
        <div class="h-16"></div>

    </main>

@endsection

@push('scripts')
    <script>
        function feedbackForm() {
            return {
                category: 'suggestion',
                message: '',
                submitting: false,
                submitStart() {
                    if (this.message.length >= 10) {
                        this.submitting = true;
                    }
                }
            };
        }
    </script>
@endpush