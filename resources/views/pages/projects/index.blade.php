@extends('layouts.app')
@section('title', 'Projek Kolaboratif')
@section('meta_description', 'Kumpulkan link video projek kelompok biologi sel — BioCinta Cell MAN 2 Kebumen')

@section('content')
<div x-data="projectPage()">

{{-- Breadcrumb --}}
<div class="bg-white border-b border-outline-variant/20">
    <div class="max-w-[1440px] mx-auto px-4 md:px-16 py-3 flex items-center gap-2 text-xs text-on-surface-variant">
        <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
        <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
        <span class="text-primary font-semibold">Projek Kolaboratif</span>
    </div>
</div>

{{-- ============================================================
     HERO
     ============================================================ --}}
<header class="hero-gradient py-12 px-4 md:px-16">
    <div class="max-w-[1440px] mx-auto flex flex-col md:flex-row items-center gap-6">
        <div class="flex-1 text-center md:text-left">
            <div class="inline-flex items-center gap-2 bg-white/10 px-4 py-1.5 rounded-full border border-white/20 mb-4 text-xs font-medium text-white">
                <span class="material-symbols-outlined text-tertiary-fixed" style="font-size:16px;">groups</span>
                Tugas Kelompok — Kreasi Digital
            </div>
            <h1 class="font-serif font-bold text-3xl md:text-4xl text-white mb-3 leading-tight">Projek Kolaboratif</h1>
            <p class="text-primary-fixed-dim text-sm md:text-base leading-relaxed max-w-xl">
                Kumpulkan link video proyekmu (YouTube atau Google Drive). Guru Biologi akan memberikan nilai dan evaluasi langsung di sini.
            </p>
        </div>
    </div>
</header>

<div class="max-w-[1440px] mx-auto px-4 md:px-16 py-10">

    {{-- ============================================================
         TAB SWITCHER
         ============================================================ --}}
    <div class="flex gap-1 bg-white border border-outline-variant/30 rounded-2xl p-1 mb-8 w-fit shadow-sm overflow-x-auto">
        <button
            @click="activeTab = 'lkpd'"
            :class="activeTab === 'lkpd' ? 'bg-primary text-white shadow-sm' : 'text-on-surface-variant hover:bg-surface-container'"
            class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all min-h-[44px] whitespace-nowrap"
        >
            <span class="material-symbols-outlined" style="font-size:18px;">description</span>
            LKPD
        </button>
        <button
            @click="activeTab = 'my-projects'"
            :class="activeTab === 'my-projects' ? 'bg-primary text-white shadow-sm' : 'text-on-surface-variant hover:bg-surface-container'"
            class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all min-h-[44px] whitespace-nowrap"
        >
            <span class="material-symbols-outlined" style="font-size:18px;">person</span>
            Projek
        </button>
        <button
            @click="activeTab = 'gallery'"
            :class="activeTab === 'gallery' ? 'bg-primary text-white shadow-sm' : 'text-on-surface-variant hover:bg-surface-container'"
            class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all min-h-[44px] whitespace-nowrap"
        >
            <span class="material-symbols-outlined" style="font-size:18px;">grid_view</span>
            Galeri Projek
            <span class="bg-white/20 text-xs font-bold px-2 py-0.5 rounded-full" :class="activeTab === 'gallery' ? 'text-white' : 'bg-outline-variant/20 text-on-surface-variant'">
                {{ $galleryProjects->count() }}
            </span>
        </button>
    </div>

    {{-- ============================================================
         TAB: LKPD & PETUNJUK GURU
         ============================================================ --}}
    <div x-show="activeTab === 'lkpd'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">

        @if($lkpdFile)
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

            {{-- INFO PANEL (2/5 lebar di desktop) --}}
            <div class="lg:col-span-2">
                <div class="glass-card rounded-2xl p-6 sticky top-24 space-y-5">
                    {{-- Header --}}
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center">
                            <span class="material-symbols-outlined text-blue-600" style="font-size:26px;">description</span>
                        </div>
                        <div>
                            <p class="text-xs text-on-surface-variant font-medium">Lembar Kerja Peserta Didik</p>
                            <h2 class="font-serif font-bold text-lg text-primary leading-tight">{{ $lkpdTitle ?? 'LKPD Projek' }}</h2>
                        </div>
                    </div>

                    {{-- Instruksi Guru --}}
                    @if($lkpdDesc)
                    <div class="bg-surface-container-low rounded-xl p-4 border border-outline-variant/20">
                        <h3 class="flex items-center gap-2 text-sm font-semibold text-on-surface mb-2">
                            <span class="material-symbols-outlined text-primary" style="font-size:18px;">info</span>
                            Petunjuk dari Guru
                        </h3>
                        <p class="text-sm text-on-surface-variant leading-relaxed whitespace-pre-line">{{ $lkpdDesc }}</p>
                    </div>
                    @endif

                    {{-- Tombol Unduh --}}
                    <a href="{{ asset('storage/' . $lkpdFile) }}" download
                       class="flex items-center justify-center gap-2 w-full py-3.5 rounded-xl
                              bg-primary hover:bg-primary/90 text-white font-semibold text-sm
                              transition-all shadow-md hover:shadow-lg active:scale-[0.98]">
                        <span class="material-symbols-outlined" style="font-size:20px;">download</span>
                        Unduh LKPD (PDF)
                    </a>

                    {{-- Info tambahan --}}
                    <div class="flex items-center gap-2 text-xs text-on-surface-variant bg-surface-container-low rounded-lg px-3 py-2">
                        <span class="material-symbols-outlined" style="font-size:16px;">lightbulb</span>
                        <span>Baca petunjuk LKPD dengan saksama sebelum mengerjakan projek.</span>
                    </div>
                </div>
            </div>

            {{-- EMBEDDED PDF VIEWER (3/5 lebar di desktop) --}}
            <div class="lg:col-span-3">
                <div class="glass-card rounded-2xl overflow-hidden">
                    <div class="flex items-center gap-3 px-5 py-4 border-b border-outline-variant/20">
                        <span class="material-symbols-outlined text-primary" style="font-size:20px;">picture_as_pdf</span>
                        <h3 class="font-semibold text-on-surface text-sm">Pratinjau Dokumen LKPD</h3>
                    </div>
                    <iframe
                        src="{{ asset('storage/' . $lkpdFile) }}"
                        class="w-full border-0"
                        style="min-height: 70vh; height: 800px;"
                        title="Pratinjau LKPD"
                    ></iframe>
                </div>
            </div>

        </div>
        @else
        {{-- Empty State: LKPD belum diunggah --}}
        <div class="glass-card rounded-2xl p-12 text-center max-w-lg mx-auto">
            <div class="w-20 h-20 rounded-full bg-surface-container-low flex items-center justify-center mx-auto mb-5">
                <span class="material-symbols-outlined text-outline-variant" style="font-size:40px;">description</span>
            </div>
            <h3 class="font-serif font-semibold text-xl text-on-surface mb-2">LKPD Belum Tersedia</h3>
            <p class="text-on-surface-variant text-sm leading-relaxed max-w-sm mx-auto">
                Guru belum mengunggah file LKPD (Lembar Kerja Peserta Didik). Silakan hubungi guru pengampu untuk informasi lebih lanjut.
            </p>
        </div>
        @endif

    </div>

    {{-- ============================================================
         TAB: PROJEK SAYA
         ============================================================ --}}
    <div x-show="activeTab === 'my-projects'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

            {{-- FORM PENGUMPULAN (2/5 lebar di desktop) --}}
            <div class="lg:col-span-2">
                <div class="glass-card rounded-2xl p-6 sticky top-24">
                    <h2 class="font-serif font-bold text-lg text-primary mb-5 flex items-center gap-2">
                        Kumpulkan Projek Baru
                    </h2>

                    <form
                        action="{{ route('projects.store') }}"
                        method="POST"
                        @submit="submitting = true"
                        id="form-project"
                    >
                        @csrf

                        {{-- Judul --}}
                        <div class="mb-4">
                            <label for="title" class="block text-xs font-bold text-on-surface mb-1.5">Judul Projek <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                id="title"
                                name="title"
                                value="{{ old('title') }}"
                                placeholder="Contoh: Model Sel Hewan dari Plastisin"
                                class="form-input @error('title') border-red-400 bg-red-50 @enderror"
                                maxlength="150"
                            >
                            @error('title')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mb-4">
                            <label for="description" class="block text-xs font-bold text-on-surface mb-1.5">Deskripsi Projek <span class="text-red-500">*</span></label>
                            <textarea
                                id="description"
                                name="description"
                                rows="3"
                                placeholder="Ceritakan proses pembuatan dan hal unik dari proyekmu..."
                                class="form-input resize-none @error('description') border-red-400 bg-red-50 @enderror"
                            >{{ old('description') }}</textarea>
                            @error('description')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- URL Video --}}
                        <div class="mb-5">
                            <label for="video_url" class="block text-xs font-bold text-on-surface mb-1.5">Link Video <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2">
                                    <span class="material-symbols-outlined text-on-surface-variant" style="font-size:18px;">link</span>
                                </span>
                                <input
                                    type="url"
                                    id="video_url"
                                    name="video_url"
                                    value="{{ old('video_url') }}"
                                    x-model="videoUrl"
                                    @input="validateUrl()"
                                    placeholder="https://www.youtube.com/watch?v=..."
                                    class="form-input pl-9 @error('video_url') border-red-400 bg-red-50 @enderror"
                                >
                            </div>
                            @error('video_url')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @else
                            <div class="mt-1.5">
                                <span x-show="urlStatus === 'valid'" class="text-xs text-emerald-600 font-medium">✅ URL valid (YouTube/Google Drive)</span>
                                <span x-show="urlStatus === 'invalid'" class="text-xs text-red-500">❌ Hanya diterima dari YouTube atau Google Drive</span>
                                <span x-show="urlStatus === 'empty'" class="text-xs text-on-surface-variant opacity-60">YouTube atau Google Drive saja yang diterima.</span>
                            </div>
                            @enderror
                        </div>

                        {{-- Platform Badges --}}
                        <div class="flex gap-2 mb-5">
                            <button type="button" @click="videoUrl='https://www.youtube.com/watch?v='" class="flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full bg-red-100 text-red-700 hover:bg-red-200 transition-colors font-medium">
                                ▶ YouTube
                            </button>
                            <button type="button" @click="videoUrl='https://drive.google.com/file/d/'" class="flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-full bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors font-medium">
                                ☁ Google Drive
                            </button>
                        </div>

                        {{-- Submit --}}
                        <button
                            type="submit"
                            id="btn-submit-project"
                            :disabled="submitting"
                            class="w-full btn-primary min-h-[50px] text-sm disabled:opacity-60 disabled:cursor-not-allowed"
                        >
                            <span x-show="!submitting" class="flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined" style="font-size:18px;">upload</span>
                                Kumpulkan Projek
                            </span>
                            <span x-show="submitting" class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                                </svg>
                                Mengumpulkan...
                            </span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- DAFTAR PROJEK SAYA (3/5 lebar di desktop) --}}
            <div class="lg:col-span-3">
                <h2 class="font-serif font-bold text-xl text-primary mb-5 flex items-center gap-2">
                    Projek yang Sudah Dikumpulkan
                </h2>

                @if($projects->isEmpty())
                <div class="glass-card rounded-2xl p-12 text-center">
                    <span class="material-symbols-outlined text-5xl text-on-surface-variant opacity-30">video_library</span>
                    <p class="text-on-surface-variant mt-4 font-medium">Belum ada projek yang dikumpulkan.</p>
                    <p class="text-on-surface-variant text-sm mt-1 opacity-70">Kumpulkan projek pertamamu menggunakan form di atas!</p>
                </div>
                @else
                <div class="space-y-4">
                    @foreach($projects as $project)
                    <div class="glass-card rounded-2xl p-5 relative">
                        {{-- Status Badge --}}
                        <div class="absolute top-4 right-4">
                            @if($project->grade !== null)
                            <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2.5 py-1 rounded-full">
                                Nilai: {{ $project->grade }}/100
                            </span>
                            @else
                            <span class="bg-amber-100 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                Menunggu Penilaian
                            </span>
                            @endif
                        </div>

                        {{-- Project Info --}}
                        <div class="pr-32">
                            <h3 class="font-serif font-bold text-base text-primary mb-1">{{ $project->title }}</h3>
                            <p class="text-on-surface-variant text-xs mb-2">
                                Dikumpulkan {{ $project->created_at->diffForHumans() }}
                            </p>
                            <p class="text-on-surface text-sm leading-relaxed line-clamp-2">{{ $project->description }}</p>
                        </div>

                        {{-- Video Link --}}
                        <div class="mt-4 pt-4 border-t border-outline-variant/20 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2 min-w-0">
                                @if(str_contains($project->video_url, 'youtube') || str_contains($project->video_url, 'youtu.be'))
                                <span class="flex-shrink-0 w-7 h-7 rounded-lg bg-red-100 flex items-center justify-center text-xs font-bold text-red-700">▶</span>
                                <span class="text-on-surface-variant text-xs truncate">YouTube</span>
                                @else
                                <span class="flex-shrink-0 w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-700">☁</span>
                                <span class="text-on-surface-variant text-xs truncate">Google Drive</span>
                                @endif
                            </div>
                            <a
                                href="{{ $project->video_url }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="flex items-center gap-1.5 text-xs font-semibold text-primary hover:underline flex-shrink-0 min-h-[36px]"
                            >
                                <span class="material-symbols-outlined" style="font-size:14px;">open_in_new</span>
                                Buka Video
                            </a>
                        </div>

                        {{-- Feedback Guru --}}
                        @if($project->teacher_feedback)
                        <div class="mt-3 bg-emerald-50 border border-emerald-200 rounded-xl p-3">
                            <p class="text-xs font-bold text-emerald-800 mb-1">💬 Evaluasi Guru Biologi:</p>
                            <p class="text-xs text-emerald-700">{{ $project->teacher_feedback }}</p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>
    </div>

    {{-- ============================================================
         TAB: GALERI SEMUA PROJEK
         ============================================================ --}}
    <div x-show="activeTab === 'gallery'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">

        @if($galleryProjects->isEmpty())
        <div class="glass-card rounded-2xl p-16 text-center">
            <span class="material-symbols-outlined text-6xl text-on-surface-variant opacity-20">collections</span>
            <p class="text-on-surface-variant mt-4 font-medium text-lg">Galeri masih kosong.</p>
            <p class="text-on-surface-variant text-sm mt-1 opacity-70">Proyek dari seluruh siswa akan muncul di sini setelah dikumpulkan.</p>
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($galleryProjects as $project)
            <div class="glass-card rounded-2xl overflow-hidden flex flex-col hover:-translate-y-1 transition-all duration-300 group">

                {{-- Header Card --}}
                <div class="bg-gradient-to-br from-primary/10 to-secondary-container/40 px-5 pt-5 pb-4">
                    <div class="flex items-start justify-between gap-2 mb-3">
                        {{-- Avatar Inisial --}}
                        <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center font-bold text-white text-sm flex-shrink-0">
                            {{ strtoupper(substr($project->user->name ?? '?', 0, 1)) }}
                        </div>
                        {{-- Badge Nilai --}}
                        @if($project->grade !== null)
                        <span class="flex-shrink-0 bg-emerald-100 text-emerald-700 text-xs font-bold px-2.5 py-1 rounded-full border border-emerald-200">
                            🏆 {{ $project->grade }}/100
                        </span>
                        @else
                        <span class="flex-shrink-0 bg-amber-100 text-amber-600 text-xs font-semibold px-2.5 py-1 rounded-full">
                            Menunggu Nilai
                        </span>
                        @endif
                    </div>
                    <h3 class="font-serif font-bold text-base text-primary leading-tight mb-1">{{ $project->title }}</h3>
                    <p class="text-xs text-on-surface-variant">
                        oleh <span class="font-semibold">{{ $project->user->name ?? 'Siswa' }}</span>
                        · {{ $project->created_at->diffForHumans() }}
                    </p>
                </div>

                {{-- Body Card --}}
                <div class="px-5 py-4 flex-1 flex flex-col gap-3">
                    <p class="text-on-surface-variant text-sm leading-relaxed line-clamp-3 flex-1">
                        {{ $project->description }}
                    </p>

                    {{-- Feedback Guru (jika ada) --}}
                    @if($project->teacher_feedback)
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-3 py-2">
                        <p class="text-xs font-semibold text-emerald-800 mb-0.5">💬 Komentar Guru:</p>
                        <p class="text-xs text-emerald-700 line-clamp-2">{{ $project->teacher_feedback }}</p>
                    </div>
                    @endif

                    {{-- Tombol Tonton --}}
                    <a
                        href="{{ $project->video_url }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-xl border-2 border-primary text-primary text-sm font-semibold hover:bg-primary hover:text-white transition-all min-h-[44px] group-hover:bg-primary group-hover:text-white"
                    >
                        @if(str_contains($project->video_url, 'youtube') || str_contains($project->video_url, 'youtu.be'))
                        <span class="material-symbols-outlined" style="font-size:18px;">play_circle</span>
                        Tonton di YouTube
                        @else
                        <span class="material-symbols-outlined" style="font-size:18px;">folder_open</span>
                        Buka Google Drive
                        @endif
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>

</div>

</div>{{-- End x-data --}}
@endsection

@push('scripts')
<script>
function projectPage() {
    return {
        activeTab:  'lkpd',
        submitting: false,
        videoUrl:   '{{ old('video_url', '') }}',
        urlStatus:  '{{ old('video_url') ? 'valid' : 'empty' }}',

        validateUrl() {
            if (!this.videoUrl) { this.urlStatus = 'empty'; return; }
            const validDomains = /^https?:\/\/(www\.)?(youtube\.com|youtu\.be|drive\.google\.com)\//;
            this.urlStatus = validDomains.test(this.videoUrl) ? 'valid' : 'invalid';
        }
    };
}
</script>
@endpush
