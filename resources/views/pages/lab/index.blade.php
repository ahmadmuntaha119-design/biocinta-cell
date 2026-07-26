@extends('layouts.app')
@section('title', 'Laboratorium Maya')
@section('meta_description', 'Simulasi Lab Virtual BioCinta Cell — Amati sel dari tampilan makro ke mikro seperti pengamatan mikroskop nyata.')

@section('content')

{{-- ============================================================
     HERO SECTION
     ============================================================ --}}
<header class="relative overflow-hidden bg-gradient-to-br from-emerald-950 via-teal-900 to-emerald-950">

    {{-- Decorative orbs --}}
    <div class="absolute inset-0 pointer-events-none z-0">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl"></div>
        {{-- Microscope circles motif --}}
        <div class="absolute top-8 right-8 w-32 h-32 border border-emerald-700/30 rounded-full"></div>
        <div class="absolute top-12 right-12 w-24 h-24 border border-emerald-600/20 rounded-full"></div>
        <div class="absolute top-16 right-16 w-16 h-16 border border-emerald-500/20 rounded-full"></div>
    </div>

    <div class="relative z-10 px-4 md:px-16 pt-20 pb-28 max-w-[1440px] mx-auto">
        <div class="max-w-3xl">
            <h1 class="font-serif font-bold text-white mb-4
                       text-3xl sm:text-4xl md:text-5xl leading-tight">
                Laboratorium Maya<br>
                <span class="text-amber-400">Makro ke Mikro</span>
            </h1>
            <p class="text-emerald-200 text-sm md:text-base leading-relaxed max-w-xl">
                Pilih spesimen, lalu tekan tombol untuk mengamati objek seperti menggunakan
                lensa mikroskop nyata — dari tampilan luar hingga ke struktur selular terdalam.
            </p>
        </div>
    </div>

    {{-- Wave Divider --}}
    <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-[0] z-20">
        <svg class="relative block w-full h-[50px] md:h-[80px]"
             viewBox="0 0 1440 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path fill="#f7f9fb" d="M0,32 C360,90 1080,-10 1440,44 L1440,80 L0,80 Z"></path>
        </svg>
    </div>
</header>

{{-- ============================================================
     MAIN CONTENT — Alpine.js Virtual Lab
     ============================================================ --}}
<main class="px-4 md:px-16 py-12 max-w-[1440px] mx-auto -mt-4 relative z-10"
      x-data="virtualLab()"
      x-init="init()">

    {{-- ======================================================
         SPECIMEN SELECTOR TABS
         ====================================================== --}}
    <div class="mb-8">
        <h2 class="font-serif font-semibold text-xl text-primary mb-4">Pilih Spesimen</h2>
        <div class="flex flex-wrap gap-3" role="tablist" aria-label="Pilihan Spesimen">
            @foreach($specimens as $i => $specimen)
            <button
                @click="selectSpecimen({{ $i }})"
                :class="activeIndex === {{ $i }}
                    ? 'bg-emerald-900 text-white shadow-lg shadow-emerald-900/20 border-emerald-900'
                    : 'bg-white text-slate-600 hover:border-emerald-300 hover:text-emerald-700'"
                class="flex items-center gap-2.5 px-5 py-3 rounded-2xl border-2 border-transparent
                       font-semibold text-sm transition-all duration-200 min-h-[44px]"
                role="tab"
                :aria-selected="activeIndex === {{ $i }}"
                id="tab-specimen-{{ $i }}"
            >
                <span class="text-lg">{{ $specimen->icon ?: '🔬' }}</span>
                <span>{{ $specimen->name }}</span>
                <span x-show="activeIndex === {{ $i }}"
                      class="material-symbols-outlined text-amber-400" style="font-size:16px;">check_circle</span>
            </button>
            @endforeach
        </div>
    </div>

    {{-- ======================================================
         MAIN LAB VIEWER PANEL
         ====================================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 lg:gap-8">

        {{-- === MICROSCOPE VIEWER (3/5 width on desktop) === --}}
        <div class="lg:col-span-3">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">

                {{-- Viewer Header --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        {{-- Status dot --}}
                        <div class="relative">
                            <div class="w-3 h-3 rounded-full"
                                 :class="isMicro ? 'bg-teal-500' : 'bg-amber-500'"></div>
                            <div class="absolute inset-0 rounded-full animate-ping"
                                 :class="isMicro ? 'bg-teal-400' : 'bg-amber-400'"
                                 style="opacity:0.4;"></div>
                        </div>
                        <span class="font-semibold text-slate-700 text-sm"
                              x-text="isMicro ? currentSpecimen.micro_label : currentSpecimen.macro_label">
                        </span>
                    </div>
                    {{-- Magnification badge --}}
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-400 font-mono"
                              x-text="isMicro ? currentSpecimen.magnification : '1×'">1×</span>
                        <div class="px-2.5 py-1 rounded-lg text-xs font-bold"
                             :class="isMicro
                                ? 'bg-teal-50 text-teal-700 border border-teal-200'
                                : 'bg-amber-50 text-amber-700 border border-amber-200'">
                            <span x-text="isMicro ? 'MIKRO' : 'MAKRO'">MAKRO</span>
                        </div>
                    </div>
                </div>

                {{-- Image Viewer --}}
                <div class="relative overflow-hidden bg-slate-950" style="aspect-ratio: 16/9;">

                    {{-- MACRO Image --}}
                    <img
                        :src="currentSpecimen.macro_img"
                        :alt="'Gambar makro ' + currentSpecimen.name"
                        class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500"
                        :class="isMicro ? 'opacity-0' : 'opacity-100'"
                        loading="lazy"
                    />

                    {{-- MICRO Image --}}
                    <img
                        :src="currentSpecimen.micro_img"
                        :alt="'Gambar mikro ' + currentSpecimen.name"
                        class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500"
                        :class="isMicro ? 'opacity-100' : 'opacity-0'"
                        loading="lazy"
                    />

                    {{-- Microscope vignette overlay (mikro mode) --}}
                    <div class="absolute inset-0 transition-opacity duration-500 pointer-events-none"
                         :class="isMicro ? 'opacity-100' : 'opacity-0'"
                         style="background: radial-gradient(ellipse 80% 80% at 50% 50%, transparent 60%, rgba(0,0,0,0.7) 100%);"></div>

                    {{-- Micro mode: crosshair overlay --}}
                    <div class="absolute inset-0 pointer-events-none transition-opacity duration-500"
                         :class="isMicro ? 'opacity-30' : 'opacity-0'">
                        <div class="absolute top-1/2 left-0 right-0 h-px bg-teal-400"></div>
                        <div class="absolute left-1/2 top-0 bottom-0 w-px bg-teal-400"></div>
                    </div>

                    {{-- Loading shimmer on specimen change --}}
                    <div x-show="loading"
                         class="absolute inset-0 bg-slate-900 flex items-center justify-center"
                         x-transition:enter="transition-opacity duration-150"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition-opacity duration-300"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         style="display:none;">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-10 h-10 rounded-full border-2 border-teal-500 border-t-transparent animate-spin"></div>
                            <span class="text-teal-400 text-sm font-medium">Memuat spesimen...</span>
                        </div>
                    </div>
                </div>

                {{-- Toggle Action Button --}}
                <div class="p-5">
                    <button
                        @click="toggleView()"
                        id="btn-toggle-lab"
                        class="w-full min-h-[52px] rounded-2xl font-bold text-sm md:text-base
                               flex items-center justify-center gap-3 transition-all duration-300
                               shadow-lg active:scale-[0.98]"
                        :class="isMicro
                            ? 'bg-amber-500 hover:bg-amber-600 text-white shadow-amber-500/30'
                            : 'bg-emerald-900 hover:bg-emerald-800 text-white shadow-emerald-900/30'"
                    >
                        <span class="material-symbols-outlined text-xl"
                              x-text="isMicro ? 'zoom_out' : 'zoom_in'">zoom_in</span>
                        <span x-text="isMicro
                            ? 'Kembali ke Tampilan Makro'
                            : 'Lihat Lewat Lensa Mikroskop'">
                            Lihat Lewat Lensa Mikroskop
                        </span>
                    </button>

                    {{-- Zoom level indicator --}}
                    <div class="mt-3 flex items-center gap-3">
                        <span class="text-xs text-slate-400">Perbesaran:</span>
                        <div class="flex-1 bg-slate-100 rounded-full h-1.5">
                            <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-500
                                        transition-all duration-700"
                                 :style="{ width: isMicro ? '100%' : '5%' }"></div>
                        </div>
                        <span class="text-xs font-mono font-bold text-slate-600"
                              x-text="isMicro ? currentSpecimen.magnification : '1×'">1×</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- === INFO PANEL (2/5 width on desktop) === --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- === MAKRO STATE: How to use instructions === --}}
            <div x-show="!isMicro"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-4"
                 style="display:none;">

                {{-- Specimen info card --}}
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-2xl">
                            <span x-text="currentSpecimen.icon || '🔬'"></span>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Spesimen Aktif</p>
                            <h3 class="font-serif font-bold text-slate-800" x-text="currentSpecimen.name"></h3>
                        </div>
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Kamu sedang melihat tampilan makro (kasat mata). Tekan tombol di sebelah kiri
                        untuk "melihat" spesimen ini melalui lensa mikroskop dan mengungkap
                        struktur selular yang tersembunyi.
                    </p>
                </div>

                {{-- Steps guide --}}
                <div class="bg-emerald-950 rounded-3xl p-6 text-emerald-100">
                    <h4 class="font-serif font-semibold text-white mb-4 text-sm">📋 Cara Menggunakan Lab</h4>
                    <ol class="space-y-3 text-xs leading-relaxed">
                        <li class="flex items-start gap-3">
                            <span class="w-5 h-5 rounded-full bg-amber-500 text-white text-[10px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
                            <span>Pilih spesimen yang ingin diamati dari tab di atas</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-5 h-5 rounded-full bg-amber-500 text-white text-[10px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
                            <span>Amati tampilan makro (luar objek) terlebih dahulu</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-5 h-5 rounded-full bg-amber-500 text-white text-[10px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">3</span>
                            <span>Tekan tombol <strong>"Lihat Lewat Lensa Mikroskop"</strong></span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-5 h-5 rounded-full bg-amber-500 text-white text-[10px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">4</span>
                            <span>Pelajari struktur sel yang terlihat di panel informasi</span>
                        </li>
                    </ol>
                </div>
            </div>

            {{-- === MIKRO STATE: Cell structures info === --}}
            <div x-show="isMicro"
                 x-transition:enter="transition ease-out duration-400"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-4"
                 style="display:none;">

                {{-- Structures visible --}}
                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-teal-600" style="font-size:20px;">biotech</span>
                        <h4 class="font-serif font-semibold text-slate-800 text-sm">Struktur yang Terlihat</h4>
                    </div>
                    <div class="space-y-3">
                        <template x-for="(structure, idx) in currentSpecimen.structures" :key="idx">
                            <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100
                                        hover:border-emerald-200 hover:bg-emerald-50/50 transition-colors">
                                <span class="text-xl flex-shrink-0" x-text="structure.icon"></span>
                                <div>
                                    <p class="font-semibold text-slate-800 text-xs mb-1" x-text="structure.name"></p>
                                    <p class="text-slate-500 text-xs leading-relaxed" x-text="structure.function"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Tadabbur Panel --}}
                <div class="rounded-3xl p-5 border border-amber-200/60 bg-gradient-to-br from-amber-50 to-amber-100/50">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-amber-600">✨</span>
                        <h4 class="font-semibold text-amber-800 text-xs uppercase tracking-wider">Tadabbur Ayat</h4>
                    </div>
                    <blockquote class="font-serif text-sm text-amber-900 italic leading-relaxed mb-3"
                                x-text="'«' + currentSpecimen.quran_verse + '»'">
                    </blockquote>
                    <p class="text-xs font-bold text-amber-700 mb-3" x-text="currentSpecimen.quran_ref"></p>
                    <hr class="border-amber-200 mb-3">
                    <p class="text-xs text-amber-800 leading-relaxed" x-text="currentSpecimen.reflection"></p>
                </div>
            </div>
        </div>
    </div>

    {{-- ======================================================
         ALL SPECIMENS QUICK REFERENCE (bottom section)
         ====================================================== --}}
    <div class="mt-12">
        <h2 class="font-serif font-semibold text-xl text-primary mb-2">Semua Spesimen Lab</h2>
        <p class="text-on-surface-variant text-sm mb-6">Tekan untuk langsung mengamati spesimen lainnya.</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($specimens as $i => $specimen)
            <button
                @click="selectSpecimen({{ $i }}); isMicro = false"
                class="group text-left bg-white rounded-2xl border border-slate-100 shadow-sm
                       hover:border-emerald-300 hover:shadow-md transition-all duration-200 overflow-hidden"
                :class="activeIndex === {{ $i }} ? 'ring-2 ring-emerald-900 border-emerald-900' : ''"
            >
                <div class="relative h-32 overflow-hidden bg-slate-100">
                    <img src="{{ $specimen->macro_image_url }}"
                         alt="Makro {{ $specimen->name }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute top-3 left-3">
                        <span class="px-2.5 py-1 rounded-full bg-black/50 text-white text-xs font-semibold backdrop-blur-sm">
                            {{ $specimen->icon }} {{ $specimen->name }}
                        </span>
                    </div>
                    <div x-show="activeIndex === {{ $i }}"
                         class="absolute top-3 right-3 w-7 h-7 rounded-full bg-emerald-500 flex items-center justify-center shadow-md">
                        <span class="material-symbols-outlined text-white" style="font-size:16px;">check</span>
                    </div>
                </div>
                <div class="p-4">
                    <p class="font-semibold text-slate-800 text-sm mb-1">{{ $specimen->name }}</p>
                    <p class="text-xs text-slate-400">Perbesaran hingga {{ $specimen->magnification }}</p>
                </div>
            </button>
            @endforeach
        </div>
    </div>

</main>

@push('scripts')
<script>
function virtualLab() {
    // Data spesimen dari database via PHP — dikonversi ke JSON untuk Alpine.js
    // Field mapping: model accessor -> JS key
    const rawSpecimens = @json($specimens);

    // Normalisasi field name dari Eloquent model ke format yang dipakai Alpine
    const specimens = rawSpecimens.map(s => ({
        id:            s.id,
        name:          s.name,
        icon:          s.icon,
        macro_img:     s.macro_image_url,
        micro_img:     s.micro_image_url,
        macro_label:   'Tampilan Makro: ' + s.name,
        micro_label:   'Tampilan Mikro (' + s.magnification + ')',
        magnification: s.magnification,
        structures:    s.structures || [],
        quran_verse:   s.quran_verse || '',
        quran_ref:     s.quran_ref || '',
        reflection:    s.reflection || '',
    }));

    return {
        specimens: specimens,
        activeIndex: 0,
        isMicro: false,
        loading: false,

        get currentSpecimen() {
            return this.specimens[this.activeIndex];
        },

        init() {
            // Pre-load gambar pertama agar transisi pertama mulus
            if (this.specimens.length > 0) this.preloadImages(0);
        },

        selectSpecimen(index) {
            if (index === this.activeIndex) return;

            this.loading = true;
            this.isMicro = false;

            setTimeout(() => {
                this.activeIndex = index;
                this.preloadImages(index);
                this.loading = false;
            }, 300);
        },

        toggleView() {
            this.isMicro = !this.isMicro;
        },

        preloadImages(index) {
            const s = this.specimens[index];
            [s.macro_img, s.micro_img].filter(Boolean).forEach(src => {
                const img = new Image();
                img.src = src;
            });
        }
    };
}
</script>
@endpush

@endsection
