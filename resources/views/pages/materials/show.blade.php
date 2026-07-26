@extends('layouts.app')
@section('title', 'Materi Interaktif Sel')
@section('meta_description', 'Eksplorasi interaktif struktur Sel Hewan dan Sel Tumbuhan — BioCinta Cell MAN 2 Kebumen')

{{-- Pre-load data SEBELUM Alpine.js init, hindari inline @json di x-data attribute --}}
@push('head')
<script>
    window.__materialsData  = {!! json_encode($materials->map(fn($m) => [
        'id'              => $m->id,
        'type'            => $m->type,
        'title'           => $m->title,
        'slug'            => $m->slug,
        'description'     => $m->description,
        'youtube_id'      => $m->youtube_id,
        'history_founder' => $m->history_founder,
        'quran_verse'     => $m->quran_verse,
        'image_path'      => $m->image_path,
        'sort_order'      => $m->sort_order,
        'hotspots'        => $m->hotspots->toArray(),
    ])) !!};
    window.__initialType    = '{{ $initialType }}';
</script>
<style>
    @keyframes hotspot-ping {
        0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
        50%       { transform: translate(-50%, -50%) scale(1.15); opacity: 0.8; }
    }
    .hotspot-btn {
        animation: hotspot-ping 2.5s ease-in-out infinite;
        animation-delay: var(--delay, 0s);
    }
    .hotspot-btn:hover { animation: none; transform: translate(-50%, -50%) scale(1.15); }
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div
    x-data="materialPage()"
    x-init="init()"
    @keydown.escape.window="closeModal()"
>

{{-- ============================================================
     BREADCRUMB
     ============================================================ --}}
<div class="bg-white border-b border-outline-variant/20">
    <div class="max-w-[1440px] mx-auto px-4 md:px-16 py-3 flex items-center gap-2 text-xs text-on-surface-variant">
        <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
        <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
        <span class="text-primary font-semibold">Materi Interaktif Sel</span>
    </div>
</div>

{{-- ============================================================
     HEADER + TAB SWITCHER
     ============================================================ --}}
<section class="bg-white border-b border-outline-variant/20 shadow-sm">
    <div class="max-w-[1440px] mx-auto px-4 md:px-16 py-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-serif font-bold text-2xl md:text-3xl text-primary mb-1">Anatomi Sel Interaktif</h1>
                <p class="text-on-surface-variant text-sm">Klik organel pada diagram untuk melihat detail fungsi, sejarah, dan tadabbur.</p>
            </div>

            {{-- Tab Switcher --}}
            <div class="flex rounded-xl border border-outline-variant/40 overflow-hidden shadow-sm flex-shrink-0">
                <button
                    @click="activeType = 'animal'"
                    :class="activeType === 'animal' ? 'bg-emerald-900 text-white' : 'bg-white text-slate-600 hover:bg-slate-50'"
                    class="px-5 py-2.5 text-sm font-semibold transition-all min-h-[44px] flex items-center gap-2"
                    id="tab-sel-hewan"
                >
                    <span class="material-symbols-outlined" style="font-size:18px;">pets</span>
                    Sel Hewan
                </button>
                <button
                    @click="activeType = 'plant'"
                    :class="activeType === 'plant' ? 'bg-emerald-900 text-white' : 'bg-white text-slate-600 hover:bg-slate-50'"
                    class="px-5 py-2.5 text-sm font-semibold transition-all min-h-[44px] flex items-center gap-2"
                    id="tab-sel-tumbuhan"
                >
                    <span class="material-symbols-outlined" style="font-size:18px;">park</span>
                    Sel Tumbuhan
                </button>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     MAIN CONTENT: DIAGRAM + ORGANELLE LIST
     ============================================================ --}}
<div class="max-w-[1440px] mx-auto px-4 md:px-16 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">

        {{-- === DIAGRAM AREA (3/5 lebar desktop) === --}}
        <div class="lg:col-span-3">
            <div class="glass-card rounded-2xl p-4 md:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-serif font-semibold text-base text-emerald-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-600" style="font-size:20px;">biotech</span>
                        <span x-text="activeType === 'animal' ? 'Diagram Sel Hewan' : 'Diagram Sel Tumbuhan'"></span>
                    </h2>
                </div>

                {{-- ---- DIAGRAM CONTAINER ---- --}}
                <div class="relative w-full rounded-xl overflow-hidden bg-slate-50" style="aspect-ratio: 4/3;">

                    {{-- === SEL HEWAN SVG === --}}
                    <div x-show="activeType === 'animal'" class="absolute inset-0">
                        <svg class="absolute inset-0 w-full h-full" viewBox="0 0 640 480" preserveAspectRatio="xMidYMid meet">
                            <ellipse cx="320" cy="242" rx="295" ry="220" fill="#e8f5e9" stroke="#66bb6a" stroke-width="3.5"/>
                            <ellipse cx="315" cy="192" rx="72" ry="62" fill="#e8eaf6" stroke="#7986cb" stroke-width="2.5"/>
                            <ellipse cx="310" cy="187" rx="24" ry="20" fill="#9fa8da"/>
                            <ellipse cx="145" cy="157" rx="44" ry="23" transform="rotate(-25 145 157)" fill="#ff8a65" stroke="#e64a19" stroke-width="2"/>
                            <path d="M112 155 Q145 143 178 157" stroke="#e64a19" stroke-width="1.5" fill="none"/>
                            <ellipse cx="492" cy="312" rx="40" ry="20" transform="rotate(20 492 312)" fill="#ff8a65" stroke="#e64a19" stroke-width="2"/>
                            <path d="M460 310 Q492 298 524 312" stroke="#e64a19" stroke-width="1.5" fill="none"/>
                            <path d="M392 180 C415 158, 435 182, 425 202 C448 180, 466 205, 455 225" stroke="#ab47bc" stroke-width="3" fill="none" stroke-linecap="round"/>
                            <circle cx="397" cy="185" r="3.5" fill="#6a1b9a"/>
                            <circle cx="412" cy="172" r="3.5" fill="#6a1b9a"/>
                            <circle cx="430" cy="189" r="3.5" fill="#6a1b9a"/>
                            <circle cx="450" cy="200" r="3.5" fill="#6a1b9a"/>
                            <circle cx="460" cy="220" r="3.5" fill="#6a1b9a"/>
                            <path d="M193 295 Q256 273, 256 303 Q193 325, 193 295 Z" fill="#fff176" stroke="#f9a825" stroke-width="2"/>
                            <path d="M198 310 Q263 288, 263 318 Q198 340, 198 310 Z" fill="#ffe57f" stroke="#f9a825" stroke-width="1.5"/>
                            <path d="M203 325 Q270 303, 270 333 Q203 355, 203 325 Z" fill="#ffecb3" stroke="#f9a825" stroke-width="1.5"/>
                            <circle cx="416" cy="335" r="27" fill="#ef9a9a" stroke="#e53935" stroke-width="2"/>
                            <circle cx="418" cy="337" r="14" fill="#ffcdd2" opacity="0.7"/>
                            <circle cx="172" cy="318" r="20" fill="#b3e5fc" stroke="#0288d1" stroke-width="2"/>
                            <circle cx="450" cy="162" r="16" fill="#b3e5fc" stroke="#0288d1" stroke-width="2"/>
                            <rect x="338" y="244" width="20" height="11" rx="3" fill="#90a4ae" stroke="#546e7a" stroke-width="1.5"/>
                            <rect x="344" y="257" width="11" height="20" rx="3" fill="#90a4ae" stroke="#546e7a" stroke-width="1.5"/>
                        </svg>

                        {{-- HOTSPOT: Sel Hewan (dinamis dari DB) --}}
                        @foreach($materials->where('type', 'animal') as $material)
                            @foreach($material->hotspots as $hs)
                            <button
                                @click="openOrganelle('{{ $material->slug }}')"
                                class="absolute text-white rounded-full shadow-lg
                                       flex items-center justify-center text-[10px] font-bold
                                       hover:scale-110 transition-transform duration-150 z-10 hotspot-btn
                                       min-w-[48px] min-h-[48px]"
                                style="left:{{ $hs->left_percent }};top:{{ $hs->top_percent }};transform:translate(-50%,-50%);background:{{ $hs->bg_color }};--delay:{{ $hs->delay }};"
                                title="{{ $material->title }}"
                            >
                                <span class="text-center leading-tight px-1 text-[9px] md:text-[10px]">{{ Str::limit($material->title, 8) }}</span>
                            </button>
                            @endforeach
                        @endforeach
                    </div>

                    {{-- === SEL TUMBUHAN SVG === --}}
                    <div x-show="activeType === 'plant'" class="absolute inset-0" style="display:none;">
                        <svg class="absolute inset-0 w-full h-full" viewBox="0 0 640 480" preserveAspectRatio="xMidYMid meet">
                            <rect x="8" y="8" width="624" height="464" rx="10" fill="#d7ccc8" stroke="#795548" stroke-width="14"/>
                            <rect x="22" y="22" width="596" height="436" rx="6" fill="#e8f5e9" stroke="#66bb6a" stroke-width="2.5"/>
                            <ellipse cx="295" cy="268" rx="185" ry="155" fill="#e3f2fd" stroke="#1976d2" stroke-width="2.5"/>
                            <text x="295" y="272" text-anchor="middle" font-size="11" fill="#1565c0" font-weight="600" opacity="0.8">Vakuola Sentral</text>
                            <ellipse cx="490" cy="127" rx="67" ry="57" fill="#e8eaf6" stroke="#7986cb" stroke-width="2.5"/>
                            <ellipse cx="488" cy="122" rx="22" ry="18" fill="#9fa8da"/>
                            <ellipse cx="100" cy="147" rx="46" ry="24" fill="#81c784" stroke="#388e3c" stroke-width="2"/>
                            <path d="M78 147 Q100 137 122 147" stroke="#2e7d32" stroke-width="2" fill="none"/>
                            <ellipse cx="165" cy="342" rx="44" ry="22" fill="#81c784" stroke="#388e3c" stroke-width="2"/>
                            <path d="M143 342 Q165 332 187 342" stroke="#2e7d32" stroke-width="2" fill="none"/>
                            <ellipse cx="82" cy="397" rx="40" ry="20" fill="#81c784" stroke="#388e3c" stroke-width="2"/>
                            <path d="M60 397 Q82 387 104 397" stroke="#2e7d32" stroke-width="2" fill="none"/>
                            <ellipse cx="554" cy="347" rx="40" ry="21" fill="#81c784" stroke="#388e3c" stroke-width="2"/>
                            <path d="M532 347 Q554 337 576 347" stroke="#2e7d32" stroke-width="2" fill="none"/>
                            <ellipse cx="528" cy="282" rx="34" ry="17" transform="rotate(15 528 282)" fill="#ff8a65" stroke="#e64a19" stroke-width="2"/>
                            <path d="M500 280 Q528 268 556 282" stroke="#e64a19" stroke-width="1.5" fill="none"/>
                            <path d="M488 207 Q548 190, 548 217 Q488 234, 488 207 Z" fill="#fff176" stroke="#f9a825" stroke-width="1.5"/>
                            <path d="M492 220 Q554 203, 554 230 Q492 247, 492 220 Z" fill="#ffe57f" stroke="#f9a825" stroke-width="1.5"/>
                            <circle cx="22" cy="230" r="5" fill="#a5d6a7" stroke="#388e3c" stroke-width="1.5"/>
                            <circle cx="22" cy="252" r="5" fill="#a5d6a7" stroke="#388e3c" stroke-width="1.5"/>
                            <circle cx="618" cy="200" r="5" fill="#a5d6a7" stroke="#388e3c" stroke-width="1.5"/>
                        </svg>

                        {{-- HOTSPOT: Sel Tumbuhan (dinamis dari DB) --}}
                        @foreach($materials->where('type', 'plant') as $material)
                            @foreach($material->hotspots as $hs)
                            <button
                                @click="openOrganelle('{{ $material->slug }}')"
                                class="absolute text-white rounded-full shadow-lg
                                       flex items-center justify-center text-[10px] font-bold
                                       hover:scale-110 transition-transform duration-150 z-10 hotspot-btn
                                       min-w-[48px] min-h-[48px]"
                                style="left:{{ $hs->left_percent }};top:{{ $hs->top_percent }};transform:translate(-50%,-50%);background:{{ $hs->bg_color }};--delay:{{ $hs->delay }};"
                                title="{{ $material->title }}"
                            >
                                <span class="text-center leading-tight px-1 text-[9px] md:text-[10px]">{{ Str::limit($material->title, 8) }}</span>
                            </button>
                            @endforeach
                        @endforeach
                    </div>
                </div>{{-- End diagram container --}}

                {{-- Legenda warna --}}
                <div class="mt-4 flex flex-wrap gap-x-4 gap-y-2 text-xs text-slate-500">
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-indigo-600 inline-block"></span>Nukleus</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-orange-600 inline-block"></span>Mitokondria</span>
                    <span x-show="activeType === 'animal'" class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-violet-600 inline-block"></span>RE Kasar</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-amber-600 inline-block"></span>Golgi</span>
                    <span x-show="activeType === 'animal'" class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-red-600 inline-block"></span>Lisosom</span>
                    <span x-show="activeType === 'plant'" class="flex items-center gap-1" style="display:none;"><span class="w-3 h-3 rounded-full bg-green-600 inline-block"></span>Kloroplas</span>
                    <span x-show="activeType === 'plant'" class="flex items-center gap-1" style="display:none;"><span class="w-3 h-3 rounded-full bg-sky-600 inline-block"></span>Vakuola</span>
                </div>
            </div>
        </div>

        {{-- === ORGANELLE LIST (2/5 lebar desktop) === --}}
        <div class="lg:col-span-2 space-y-3">
            <h3 class="font-serif font-semibold text-base text-emerald-900 mb-1 flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-size:18px;">list_alt</span>
                Daftar Organel
            </h3>
            <template x-for="organelle in filteredOrganelles()" :key="organelle.id">
                <button
                    @click="openOrganelle(organelle.slug)"
                    class="w-full glass-card rounded-xl p-4 text-left hover:-translate-y-0.5 hover:shadow-md
                           transition-all duration-200 group min-h-[60px] flex items-center justify-between"
                >
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-emerald-700" style="font-size:16px;">circle</span>
                        </div>
                        <span class="font-semibold text-sm text-slate-800" x-text="organelle.title"></span>
                    </div>
                    <span class="material-symbols-outlined text-slate-400 group-hover:text-emerald-900 transition-colors" style="font-size:18px;">arrow_forward_ios</span>
                </button>
            </template>
        </div>

    </div>{{-- End grid --}}

    {{-- ============================================================
         VIDEO PEMBELAJARAN SEL (YouTube Embed Template)
         ============================================================ --}}
    <div class="mt-8 glass-card rounded-2xl p-6 border border-outline-variant/30 shadow-md">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="font-serif font-bold text-lg md:text-xl text-primary flex items-center gap-2">
                    <span x-text="activeType === 'animal' ? 'Video Edukasi: Cara Kerja Sel Hewan' : 'Video Edukasi: Cara Kerja Sel Tumbuhan'"></span>
                </h3>
                <p class="text-xs text-on-surface-variant mt-1">Tonton video interaktif ini untuk memahami cara kerja organel-organel sel secara keseluruhan.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            {{-- Video Frame --}}
            <div class="lg:col-span-2 rounded-xl overflow-hidden aspect-video bg-black shadow-inner border border-slate-200">
                {{-- YouTube Embed with dynamic ID based on activeType --}}
                <iframe
                    class="w-full h-full"
                    :src="activeType === 'animal' 
                        ? 'https://www.youtube.com/embed/F49I1j7rA9Q?rel=0' 
                        : 'https://www.youtube.com/embed/9UvlqAvLj5s?rel=0'"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                ></iframe>
            </div>

            {{-- Info / Deskripsi Video --}}
            <div class="space-y-4 h-full flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-4">
                        <h4 class="font-bold text-xs text-emerald-950 uppercase tracking-wide flex items-center gap-1.5 mb-2">
                            <span class="material-symbols-outlined text-emerald-700" style="font-size:16px;">info</span>
                            Detail Pembelajaran
                        </h4>
                        <p class="text-xs text-on-surface-variant leading-relaxed" 
                           x-text="activeType === 'animal' 
                               ? 'Video ini menjelaskan alur metabolisme, sintesis protein, pembelahan sel, dan sinergi antar organel di dalam sel hewan demi mempertahankan hidup.' 
                               : 'Video ini menyoroti bagaimana kloroplas menangkap sinar matahari untuk fotosintesis dan dinding sel mempertahankan turgiditas sel tumbuhan.'"></p>
                    </div>

                    <div class="bg-primary/5 rounded-xl p-4 border border-primary/10">
                        <h4 class="font-bold text-xs text-primary uppercase tracking-wide flex items-center gap-1.5 mb-1.5">
                            <span class="material-symbols-outlined" style="font-size:16px;">psychology</span>
                            Tugas Siswa
                        </h4>
                        <p class="text-xs text-slate-700 leading-relaxed">
                            Catat proses penting yang kamu lihat di video dan buatlah refleksi singkat tentang keagungan struktur sel ciptaan Allah SWT.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>{{-- End max-w container --}}

{{-- ============================================================
     MODAL DETAIL ORGANEL
     ============================================================ --}}
<div
    x-show="showModal"
    x-cloak
    class="fixed inset-0 z-50 flex items-end md:items-center justify-center"
>
    {{-- Backdrop --}}
    <div
        x-show="showModal"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm"
        @click="closeModal()"
    ></div>

    {{-- Drawer Content --}}
    <div
        x-show="showModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-8 md:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 md:scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-8"
        class="relative w-full md:max-w-2xl md:mx-4 bg-white md:rounded-2xl shadow-2xl
               max-h-[90dvh] overflow-y-auto rounded-t-2xl z-10"
    >
        {{-- Modal Header --}}
        <div class="sticky top-0 bg-white border-b border-slate-100 px-5 py-4 flex items-center justify-between z-10">
            <div class="absolute top-2 left-1/2 -translate-x-1/2 w-10 h-1 bg-slate-200 rounded-full md:hidden"></div>

            <div class="flex items-center gap-3 mt-2 md:mt-0" x-show="activeOrganelle">
                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-emerald-900" style="font-size:22px;">biotech</span>
                </div>
                <div>
                    <h2 class="font-serif font-bold text-lg text-emerald-900 leading-tight" x-text="activeOrganelle ? activeOrganelle.title : ''"></h2>
                    <span
                        class="text-xs font-semibold px-2 py-0.5 rounded-full"
                        :class="activeOrganelle && activeOrganelle.type === 'animal' ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700'"
                        x-text="activeOrganelle && activeOrganelle.type === 'animal' ? 'Sel Hewan' : 'Sel Tumbuhan'"
                    ></span>
                </div>
            </div>

            <button
                @click="closeModal()"
                class="w-10 h-10 rounded-xl hover:bg-slate-100 flex items-center justify-center transition-colors flex-shrink-0 ml-auto"
                aria-label="Tutup"
            >
                <span class="material-symbols-outlined text-slate-500">close</span>
            </button>
        </div>

        {{-- Modal Body --}}
        <div class="px-5 py-5 space-y-6" x-show="activeOrganelle">

            {{-- GAMBAR ORGANEL — tampil hanya jika ada foto --}}
            <template x-if="activeOrganelle && activeOrganelle.image_path">
                <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-sm mb-2">
                    <img
                        :src="'/storage/' + activeOrganelle.image_path"
                        :alt="activeOrganelle.title"
                        class="w-full h-auto object-cover max-h-64"
                        onerror="this.closest('div').style.display='none'"
                    >
                </div>
            </template>

            {{-- PILAR 1: Fungsi Teknis --}}
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 rounded-lg bg-emerald-900 flex items-center justify-center">
                        <span class="text-white text-xs font-bold">01</span>
                    </div>
                    <h3 class="font-bold text-sm text-emerald-900 uppercase tracking-wide">Fungsi Teknis</h3>
                </div>
                <div class="bg-slate-50 rounded-xl p-4 text-sm text-slate-700 leading-relaxed whitespace-pre-line"
                     x-text="activeOrganelle ? activeOrganelle.description : ''"></div>
            </div>

            {{-- PILAR 2: Video YouTube --}}
            <div x-show="activeOrganelle && activeOrganelle.youtube_id">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 rounded-lg bg-red-600 flex items-center justify-center">
                        <span class="text-white text-xs font-bold">02</span>
                    </div>
                    <h3 class="font-bold text-sm text-red-700 uppercase tracking-wide">Video Visualisasi</h3>
                </div>
                <div class="rounded-xl overflow-hidden aspect-video bg-black">
                    <iframe
                        class="w-full h-full"
                        :src="activeOrganelle && activeOrganelle.youtube_id ? 'https://www.youtube.com/embed/' + activeOrganelle.youtube_id : ''"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                    ></iframe>
                </div>
            </div>

            {{-- PILAR 3: Sejarah Penemu --}}
            <div x-show="activeOrganelle && activeOrganelle.history_founder">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 rounded-lg bg-amber-500 flex items-center justify-center">
                        <span class="text-white text-xs font-bold">03</span>
                    </div>
                    <h3 class="font-bold text-sm text-amber-700 uppercase tracking-wide">Sejarah Penemu</h3>
                </div>
                <div class="border-l-4 border-amber-400 pl-4 bg-amber-50 rounded-r-xl p-4 text-sm text-slate-700 leading-relaxed"
                     x-text="activeOrganelle ? activeOrganelle.history_founder : ''"></div>
            </div>

            {{-- PILAR 4: Tadabbur Quran --}}
            <div x-show="activeOrganelle && activeOrganelle.quran_verse">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 rounded-lg bg-teal-700 flex items-center justify-center">
                        <span class="text-white text-xs font-bold">04</span>
                    </div>
                    <h3 class="font-bold text-sm text-teal-800 uppercase tracking-wide">Tadabbur — Renungan Islami</h3>
                </div>
                <div class="bg-gradient-to-br from-emerald-900 to-teal-900 rounded-xl p-5 text-white relative overflow-hidden">
                    <span class="material-symbols-outlined absolute top-2 right-3 text-white/10 select-none" style="font-size:60px;">format_quote</span>
                    <p class="text-sm leading-relaxed relative z-10 italic"
                       x-text="activeOrganelle ? activeOrganelle.quran_verse : ''"></p>
                </div>
            </div>
        </div>{{-- End modal body --}}
    </div>
</div>{{-- End modal --}}

</div>{{-- End x-data --}}
@endsection

@push('scripts')
<script>
function materialPage() {
    return {
        // State utama — semua dikelola di satu scope, tidak ada nested x-data
        activeType:      window.__initialType  || 'animal',
        materials:       window.__materialsData || [],
        showModal:       false,
        activeOrganelle: null,

        // State feedback (tidak ada nested x-data lagi)
        feedbackText:    '',
        feedbackSent:    false,
        feedbackLoading: false,

        init() {
            // Tidak ada side-effect yang diperlukan saat init
        },

        filteredOrganelles() {
            return this.materials.filter(m => m.type === this.activeType);
        },

        openOrganelle(slug) {
            const found = this.materials.find(m => m.slug === slug);
            if (!found) return;
            this.activeOrganelle = found;
            this.showModal       = true;
            // Reset feedback state setiap kali buka organel baru
            this.feedbackText    = '';
            this.feedbackSent    = false;
            this.feedbackLoading = false;
            document.body.style.overflow = 'hidden';
        },

        closeModal() {
            this.showModal       = false;
            this.activeOrganelle = null;
            document.body.style.overflow = '';
        },
    };
}
</script>
@endpush
