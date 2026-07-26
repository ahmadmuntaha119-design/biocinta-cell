@extends('layouts.app')
@section('title', 'Jurnal Refleksi — Ruang Tadabbur')
@section('meta_description', 'Jurnal Refleksi Islami Kurikulum CINTA: 4 Pilar Cinta — BioCinta Cell MAN 2 Kebumen')

@section('content')
<div x-data="reflectionPage()">

{{-- Breadcrumb --}}
<div class="bg-white border-b border-outline-variant/20">
    <div class="max-w-[1440px] mx-auto px-4 md:px-16 py-3 flex items-center gap-2 text-xs text-on-surface-variant">
        <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
        <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
        <span class="text-primary font-semibold">Ruang Tadabbur</span>
    </div>
</div>

{{-- ============================================================
     HERO: Spiritual Header
     ============================================================ --}}
<header class="hero-gradient py-16 px-4 md:px-16 text-center relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none opacity-5">
        <svg class="w-full h-full" viewBox="0 0 400 300" preserveAspectRatio="xMidYMid slice">
            <pattern id="pattern-circles" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                <circle cx="20" cy="20" r="15" fill="none" stroke="white" stroke-width="1"/>
            </pattern>
            <rect x="0" y="0" width="100%" height="100%" fill="url(#pattern-circles)"/>
        </svg>
    </div>
    <div class="max-w-2xl mx-auto relative z-10">
        <div class="inline-flex items-center gap-2 bg-white/10 px-5 py-2 rounded-full border border-white/20 mb-6 text-xs font-medium text-white">
            <span class="material-symbols-outlined text-tertiary-fixed" style="font-size:16px;">self_improvement</span>
            Kurikulum Berbasis CINTA — MAN 2 Kebumen
        </div>
        <h1 class="font-serif font-bold text-3xl md:text-4xl text-white mb-4 leading-tight">
            Kurikulum Berbasis CINTA<br>
            <span class="text-tertiary-fixed">Jurnal Refleksi Islami</span>
        </h1>
        <p class="text-primary-fixed-dim text-sm md:text-base leading-relaxed">
            "Dan di bumi itu terdapat tanda-tanda (kekuasaan Allah) bagi orang-orang yang yakin, dan (juga) pada dirimu sendiri."
            <span class="block text-xs mt-2 text-tertiary-fixed font-semibold">— QS. Az-Zariyat: 20-21</span>
        </p>
    </div>
</header>

<div class="max-w-[1440px] mx-auto px-4 md:px-16 pt-8 pb-2">
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
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ============================================================
             FORM REFLEKSI (2/3 lebar di desktop)
             ============================================================ --}}
        <div class="lg:col-span-2">
            <div class="glass-card rounded-2xl p-6 md:p-8">

                <div class="flex items-center gap-3 mb-6 pb-5 border-b border-outline-variant/20">
                    <div>
                        <h2 class="font-serif font-bold text-xl text-primary">Tulis Refleksi Baru</h2>
                        <p class="text-xs text-on-surface-variant">Isi keempat kolom dengan jujur dan sungguh-sungguh. Min. 20 karakter per kolom.</p>
                    </div>
                </div>

                <form
                    action="{{ route('reflection.store') }}"
                    method="POST"
                    @submit="submitStart()"
                    id="form-reflection"
                >
                    @csrf

                    {{-- Indikator Progress 4 Pilar --}}
                    <div class="grid grid-cols-4 gap-2 mb-6">
                        @foreach([
                            'connection'     => 'Allah & Rasul',
                            'wisdom'         => 'Diri Sendiri',
                            'implementation' => 'Lingkungan',
                            'love_knowledge' => 'Ilmu',
                        ] as $field => $label)
                        <div class="text-center">
                            <div
                                class="h-1.5 rounded-full mb-1.5 transition-all duration-500"
                                :class="charCounts['{{ $field }}'] >= 20 ? 'bg-primary' : 'bg-slate-200'"
                            ></div>
                            <span class="text-[10px] font-semibold leading-tight block"
                                  :class="charCounts['{{ $field }}'] >= 20 ? 'text-primary' : 'text-slate-400'"
                            >{{ $label }}</span>
                        </div>
                        @endforeach
                    </div>

                    {{-- PILAR 1: CINTA KEPADA ALLAH DAN RASUL --}}
                    <div class="mb-6">
                        <label for="connection" class="flex items-center gap-2 text-sm font-bold text-primary mb-2">
                            <span class="w-6 h-6 rounded-full bg-primary text-white text-xs flex items-center justify-center font-bold flex-shrink-0">1</span>
                            Cinta kepada Allah dan Rasul
                        </label>
                        <p class="text-xs text-on-surface-variant mb-2 ml-8">
                            Struktur sel apa yang paling membuatmu takjub akan kebesaran Sang Pencipta? Bagaimana kamu menghubungkannya dengan kecintaanmu kepada Allah dan Rasul-Nya?
                        </p>
                        <textarea
                            id="connection"
                            name="connection"
                            rows="4"
                            placeholder="Contoh: Yang paling membuatku takjub adalah mitokondria, karena keteraturannya mengingatkanku betapa Maha Kuasa Allah yang menciptakan mesin sekecil itu..."
                            x-model="reflections.connection"
                            @input="charCounts.connection = reflections.connection.length"
                            class="w-full text-sm px-4 py-3 rounded-xl border @error('connection') border-red-400 bg-red-50 @else border-outline-variant/40 @enderror
                                   bg-surface-container-low resize-none outline-none
                                   focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                        >{{ old('connection') }}</textarea>
                        <div class="flex justify-between mt-1">
                            @error('connection')
                            <span class="text-xs text-red-600">{{ $message }}</span>
                            @else
                            <span class="text-xs" :class="charCounts.connection >= 20 ? 'text-primary font-medium' : 'text-slate-400'">
                                <span x-text="charCounts.connection"></span> / min. 20 karakter
                            </span>
                            @enderror
                        </div>
                    </div>

                    {{-- PILAR 2: CINTA PADA DIRI SENDIRI --}}
                    <div class="mb-6">
                        <label for="wisdom" class="flex items-center gap-2 text-sm font-bold text-primary mb-2">
                            <span class="w-6 h-6 rounded-full bg-primary text-white text-xs flex items-center justify-center font-bold flex-shrink-0">2</span>
                            Cinta pada diri sendiri
                        </label>
                        <p class="text-xs text-on-surface-variant mb-2 ml-8">
                            Hikmah apa yang kamu dapatkan tentang pentingnya menjaga dirimu sendiri setelah mempelajari keteraturan sel dalam tubuhmu?
                        </p>
                        <textarea
                            id="wisdom"
                            name="wisdom"
                            rows="4"
                            placeholder="Contoh: Setelah mempelajari sel, saya menyadari bahwa menjaga kesehatan adalah bentuk syukur karena tubuhku bekerja sangat keras untuk hidupku..."
                            x-model="reflections.wisdom"
                            @input="charCounts.wisdom = reflections.wisdom.length"
                            class="w-full text-sm px-4 py-3 rounded-xl border @error('wisdom') border-red-400 bg-red-50 @else border-outline-variant/40 @enderror
                                   bg-surface-container-low resize-none outline-none
                                   focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                        >{{ old('wisdom') }}</textarea>
                        <div class="flex justify-between mt-1">
                            @error('wisdom')
                            <span class="text-xs text-red-600">{{ $message }}</span>
                            @else
                            <span class="text-xs" :class="charCounts.wisdom >= 20 ? 'text-primary font-medium' : 'text-slate-400'">
                                <span x-text="charCounts.wisdom"></span> / min. 20 karakter
                            </span>
                            @enderror
                        </div>
                    </div>

                    {{-- PILAR 3: CINTA PADA LINGKUNGAN --}}
                    <div class="mb-6">
                        <label for="implementation" class="flex items-center gap-2 text-sm font-bold text-primary mb-2">
                            <span class="w-6 h-6 rounded-full bg-primary text-white text-xs flex items-center justify-center font-bold flex-shrink-0">3</span>
                            Cinta pada lingkungan
                        </label>
                        <p class="text-xs text-on-surface-variant mb-2 ml-8">
                            Bagaimana pemahamanmu tentang sel menginspirasi kamu untuk lebih peduli dan bertanggung jawab terhadap lingkungan sekitarmu?
                        </p>
                        <textarea
                            id="implementation"
                            name="implementation"
                            rows="4"
                            placeholder="Contoh: Melihat betapa sel tumbuhan bergantung satu sama lain, saya bertekad untuk mulai memilah sampah dan menjaga tumbuhan di sekitar sekolah..."
                            x-model="reflections.implementation"
                            @input="charCounts.implementation = reflections.implementation.length"
                            class="w-full text-sm px-4 py-3 rounded-xl border @error('implementation') border-red-400 bg-red-50 @else border-outline-variant/40 @enderror
                                   bg-surface-container-low resize-none outline-none
                                   focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                        >{{ old('implementation') }}</textarea>
                        <div class="flex justify-between mt-1">
                            @error('implementation')
                            <span class="text-xs text-red-600">{{ $message }}</span>
                            @else
                            <span class="text-xs" :class="charCounts.implementation >= 20 ? 'text-primary font-medium' : 'text-slate-400'">
                                <span x-text="charCounts.implementation"></span> / min. 20 karakter
                            </span>
                            @enderror
                        </div>
                    </div>

                    {{-- PILAR 4: CINTA PADA ILMU PENGETAHUAN --}}
                    <div class="mb-7">
                        <label for="love_knowledge" class="flex items-center gap-2 text-sm font-bold text-primary mb-2">
                            <span class="w-6 h-6 rounded-full bg-primary text-white text-xs flex items-center justify-center font-bold flex-shrink-0">4</span>
                            Cinta pada ilmu pengetahuan
                        </label>
                        <p class="text-xs text-on-surface-variant mb-2 ml-8">
                            Apa yang membuat kamu semakin bersemangat untuk terus belajar setelah mempelajari keajaiban sel hari ini? Komitmen apa yang ingin kamu jaga?
                        </p>
                        <textarea
                            id="love_knowledge"
                            name="love_knowledge"
                            rows="4"
                            placeholder="Contoh: Saya semakin penasaran bagaimana sel bisa berkomunikasi satu sama lain. Saya berkomitmen untuk membaca buku biologi lebih giat dan bertanya kepada guru..."
                            x-model="reflections.love_knowledge"
                            @input="charCounts.love_knowledge = reflections.love_knowledge.length"
                            class="w-full text-sm px-4 py-3 rounded-xl border @error('love_knowledge') border-red-400 bg-red-50 @else border-outline-variant/40 @enderror
                                   bg-surface-container-low resize-none outline-none
                                   focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                        >{{ old('love_knowledge') }}</textarea>
                        <div class="flex justify-between mt-1">
                            @error('love_knowledge')
                            <span class="text-xs text-red-600">{{ $message }}</span>
                            @else
                            <span class="text-xs" :class="charCounts.love_knowledge >= 20 ? 'text-primary font-medium' : 'text-slate-400'">
                                <span x-text="charCounts.love_knowledge"></span> / min. 20 karakter
                            </span>
                            @enderror
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        id="btn-submit-reflection"
                        :disabled="!isFormValid() || submitting"
                        class="w-full btn-primary disabled:opacity-50 disabled:cursor-not-allowed min-h-[52px] text-base"
                    >
                        <span x-show="!submitting" class="flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined" style="font-size:20px; font-variation-settings: 'FILL' 1;">save</span>
                            Simpan Jurnal Refleksi
                        </span>
                        <span x-show="submitting" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                            </svg>
                            Menyimpan...
                        </span>
                    </button>
                </form>
            </div>
        </div>

        {{-- ============================================================
             SIDEBAR: Panduan 4 Pilar CINTA
             ============================================================ --}}
        <div class="space-y-6">

            {{-- Panduan 4 Pilar CINTA --}}
            <div class="glass-card rounded-2xl p-5">
                <h3 class="font-serif font-semibold text-inter text-primary mb-4 flex items-center gap-2">
                    Pilar Kurikulum CINTA:
                </h3>
                <div class="space-y-3 text-xs text-on-surface-variant">
                    <div class="flex gap-3 p-3 bg-surface-container-low rounded-xl">
                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">1</span>
                        <div>
                            <p class="font-bold text-on-surface mb-0.5">Cinta kepada Allah dan Rasul</p>
                            <p>Hubungkan keajaiban sains dengan kebesaran Allah sebagai Sang Pencipta dan rasa cintamu kepada-Nya.</p>
                        </div>
                    </div>
                    <div class="flex gap-3 p-3 bg-surface-container-low rounded-xl">
                        <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">2</span>
                        <div>
                            <p class="font-bold text-on-surface mb-0.5">Cinta pada diri sendiri</p>
                            <p>Rumuskan hikmah untuk menjaga dan menghargai dirimu sendiri sebagai amanah dari Allah.</p>
                        </div>
                    </div>
                    <div class="flex gap-3 p-3 bg-surface-container-low rounded-xl">
                        <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">3</span>
                        <div>
                            <p class="font-bold text-on-surface mb-0.5">Cinta pada lingkungan</p>
                            <p>Rencanakan langkah konkret untuk menjaga dan merawat lingkungan sebagai bentuk rasa syukur.</p>
                        </div>
                    </div>
                    <div class="flex gap-3 p-3 bg-surface-container-low rounded-xl">
                        <span class="w-6 h-6 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">4</span>
                        <div>
                            <p class="font-bold text-on-surface mb-0.5">Cinta pada ilmu pengetahuan</p>
                            <p>Nyatakan komitmenmu untuk terus mencari ilmu karena menuntut ilmu adalah ibadah.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

</div>{{-- End x-data --}}
@endsection

@push('scripts')
<script>
function reflectionPage() {
    return {
        submitting: false,
        reflections: {
            connection:     '{{ addslashes(old('connection', '')) }}',
            wisdom:         '{{ addslashes(old('wisdom', '')) }}',
            implementation: '{{ addslashes(old('implementation', '')) }}',
            love_knowledge: '{{ addslashes(old('love_knowledge', '')) }}',
        },
        charCounts: {
            connection:     {{ strlen(old('connection', '')) }},
            wisdom:         {{ strlen(old('wisdom', '')) }},
            implementation: {{ strlen(old('implementation', '')) }},
            love_knowledge: {{ strlen(old('love_knowledge', '')) }},
        },

        isFormValid() {
            return this.charCounts.connection     >= 20
                && this.charCounts.wisdom         >= 20
                && this.charCounts.implementation >= 20
                && this.charCounts.love_knowledge >= 20;
        },

        submitStart() {
            if (this.isFormValid()) {
                this.submitting = true;
            }
        }
    };
}
</script>
@endpush
