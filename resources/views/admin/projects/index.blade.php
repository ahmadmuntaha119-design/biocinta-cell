@extends('layouts.admin')
@section('title', 'Penilaian Projek Kolaboratif')

@section('content')

{{-- Page Header --}}
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="font-serif font-bold text-2xl text-slate-900">Penilaian Projek Kolaboratif</h1>
        <p class="text-slate-500 text-sm mt-0.5">
            Tinjau dan berikan nilai (0–100) untuk setiap proyek yang dikumpulkan siswa.
        </p>
    </div>
    {{-- Stats --}}
    <div class="flex gap-3 flex-shrink-0">
        <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-2.5 text-center">
            <div class="text-2xl font-bold text-amber-700">{{ $ungradedCount }}</div>
            <div class="text-xs text-amber-600">Belum Dinilai</div>
        </div>
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-2.5 text-center">
            <div class="text-2xl font-bold text-emerald-700">{{ $totalCount }}</div>
            <div class="text-xs text-emerald-600">Total Proyek</div>
        </div>
    </div>
</div>

{{-- ============================================================
     LKPD & PETUNJUK PROJEK
     ============================================================ --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6" x-data="{ lkpdOpen: false }">
    <button @click="lkpdOpen = !lkpdOpen" type="button"
            class="w-full flex items-center justify-between px-6 py-4 hover:bg-slate-50 transition-colors">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                <span class="material-symbols-outlined text-blue-600" style="font-size:22px;">description</span>
            </div>
            <div class="text-left">
                <h2 class="font-semibold text-slate-900 text-sm">LKPD & Petunjuk Projek</h2>
                <p class="text-xs text-slate-500">
                    @if($lkpdFile)
                        <span class="text-emerald-600 font-medium">✅ File LKPD aktif</span> · {{ $lkpdTitle ?? 'Tanpa Judul' }}
                    @else
                        <span class="text-amber-600 font-medium">⚠ Belum ada file LKPD</span>
                    @endif
                </p>
            </div>
        </div>
        <span class="material-symbols-outlined text-slate-400 transition-transform duration-200"
              :class="lkpdOpen ? 'rotate-180' : ''" style="font-size:20px;">expand_more</span>
    </button>

    <div x-show="lkpdOpen" x-collapse>
        <div class="border-t border-slate-100 px-6 py-5">
            <form action="{{ route('admin.projects.lkpd.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    {{-- Judul LKPD --}}
                    <div class="md:col-span-2">
                        <label for="lkpd_title" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Judul LKPD <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="lkpd_title" name="lkpd_title"
                               value="{{ old('lkpd_title', $lkpdTitle) }}"
                               placeholder="mis. LKPD Projek Model Sel & Video Presentasi"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                      @error('lkpd_title') border-red-400 bg-red-50 @enderror">
                        @error('lkpd_title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Deskripsi / Instruksi --}}
                    <div class="md:col-span-2">
                        <label for="lkpd_desc" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Instruksi / Petunjuk Guru <span class="text-slate-400 font-normal text-xs">(opsional)</span>
                        </label>
                        <textarea id="lkpd_desc" name="lkpd_desc" rows="3"
                                  placeholder="Tuliskan instruksi atau petunjuk tambahan untuk siswa..."
                                  class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm
                                         focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                         @error('lkpd_desc') border-red-400 bg-red-50 @enderror">{{ old('lkpd_desc', $lkpdDesc) }}</textarea>
                        @error('lkpd_desc') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- File Upload --}}
                    <div class="md:col-span-2">
                        <label for="lkpd_file" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            File LKPD (PDF)
                            @if($lkpdFile)
                                <span class="text-slate-400 font-normal text-xs">(kosongkan jika tidak ingin mengganti)</span>
                            @else
                                <span class="text-red-500">*</span>
                            @endif
                        </label>
                        <input type="file" id="lkpd_file" name="lkpd_file" accept=".pdf"
                               class="w-full text-sm text-slate-600 file:mr-4 file:py-2.5 file:px-4
                                      file:rounded-xl file:border-0 file:text-sm file:font-semibold
                                      file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100
                                      @error('lkpd_file') border-red-400 @enderror">
                        <p class="text-xs text-slate-400 mt-1.5">Format: PDF · Ukuran maks. 10 MB</p>
                        @error('lkpd_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                        @if($lkpdFile)
                            <div class="mt-3 flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-2.5">
                                <span class="material-symbols-outlined text-emerald-600" style="font-size:20px;">picture_as_pdf</span>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-emerald-800 truncate">{{ basename($lkpdFile) }}</p>
                                    <p class="text-xs text-emerald-600">File LKPD saat ini aktif</p>
                                </div>
                                <a href="{{ asset('storage/' . $lkpdFile) }}" target="_blank"
                                   class="text-emerald-700 hover:text-emerald-900 transition-colors">
                                    <span class="material-symbols-outlined" style="font-size:18px;">open_in_new</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <button type="submit"
                        class="inline-flex items-center gap-2 bg-emerald-900 hover:bg-emerald-800 text-white
                               font-semibold px-6 py-3 rounded-xl transition-colors text-sm shadow-sm">
                    <span class="material-symbols-outlined" style="font-size:18px;">save</span>
                    Simpan LKPD & Petunjuk
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Filter Tabs --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-1 flex gap-1 mb-6 w-fit">
    @foreach(['all' => 'Semua', 'ungraded' => 'Belum Dinilai', 'graded' => 'Sudah Dinilai'] as $key => $label)
        <a href="{{ route('admin.projects.index', ['status' => $key]) }}"
           class="px-4 py-2 rounded-xl text-sm font-semibold transition-all min-h-[40px] flex items-center gap-1.5
                  {{ $statusFilter === $key ? 'bg-emerald-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            @if($key === 'ungraded' && $ungradedCount > 0)
                <span class="w-5 h-5 rounded-full bg-amber-500 text-white text-[10px] font-bold flex items-center justify-center flex-shrink-0">
                    {{ $ungradedCount }}
                </span>
            @endif
            {{ $label }}
        </a>
    @endforeach
</div>

{{-- Projects Table --}}
<div class="space-y-4">
    @forelse($projects as $project)
        @php $isGraded = $project->grade !== null; @endphp
        <div class="bg-white rounded-2xl border {{ $isGraded ? 'border-slate-200' : 'border-amber-300 shadow-amber-50' }} shadow-sm overflow-hidden">
            <div class="p-5 md:p-6">
                <div class="flex flex-col lg:flex-row gap-5">

                    {{-- Project Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start gap-3 mb-3">
                            {{-- Avatar --}}
                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center font-bold text-emerald-800 text-sm flex-shrink-0">
                                {{ strtoupper(substr($project->user->name ?? '?', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-slate-900 text-sm">{{ $project->user->name ?? 'Siswa Tidak Diketahui' }}</p>
                                <p class="text-xs text-slate-400">NIS: {{ $project->user->nis ?? '—' }} · Dikumpulkan {{ $project->created_at->diffForHumans() }}</p>
                            </div>
                            @if(!$isGraded)
                                <span class="ml-auto flex-shrink-0 bg-amber-100 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-lg">Belum Dinilai</span>
                            @else
                                <span class="ml-auto flex-shrink-0 bg-emerald-100 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-lg">✅ Sudah Dinilai</span>
                            @endif
                        </div>

                        <h3 class="font-serif font-semibold text-lg text-slate-900 mb-1">{{ $project->title }}</h3>
                        <p class="text-sm text-slate-600 leading-relaxed mb-3">{{ $project->description }}</p>

                        {{-- Video link --}}
                        <a
                            href="{{ $project->video_url }}"
                            target="_blank"
                            class="inline-flex items-center gap-2 text-sm text-red-600 hover:text-red-800 font-medium transition-colors"
                        >
                            <span class="material-symbols-outlined" style="font-size:18px;">play_circle</span>
                            Tonton Video Proyek
                            <span class="material-symbols-outlined text-slate-400" style="font-size:14px;">open_in_new</span>
                        </a>

                        {{-- Tombol Hapus --}}
                        <form
                            action="{{ route('admin.projects.destroy', $project) }}"
                            method="POST"
                            class="inline"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek \"{{ addslashes($project->title) }}\" milik {{ addslashes($project->user->name ?? '') }}? Tindakan ini tidak dapat dibatalkan.')"
                        >
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="inline-flex items-center gap-1.5 text-sm text-red-500 hover:text-white hover:bg-red-500 border border-red-200 hover:border-red-500 px-3 py-1.5 rounded-xl transition-all font-medium"
                            >
                                <span class="material-symbols-outlined" style="font-size:16px;">delete</span>
                                Hapus Proyek
                            </button>
                        </form>

                        @if($isGraded && $project->teacher_feedback)
                            <div class="mt-3 bg-slate-50 rounded-xl px-4 py-3 border border-slate-200">
                                <p class="text-xs font-semibold text-slate-500 mb-1">Catatan Guru Sebelumnya:</p>
                                <p class="text-sm text-slate-700">{{ $project->teacher_feedback }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Grading Form --}}
                    <div class="lg:w-72 flex-shrink-0">
                        <form
                            action="{{ route('admin.projects.grade', $project) }}"
                            method="POST"
                            class="bg-slate-50 rounded-xl border border-slate-200 p-4 space-y-3"
                        >
                            @csrf
                            <p class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                                <span class="material-symbols-outlined text-amber-500" style="font-size:18px;">grade</span>
                                Beri Nilai
                            </p>

                            {{-- Nilai --}}
                            <div>
                                <label class="text-xs text-slate-500 font-medium block mb-1">Nilai (0–100)</label>
                                <div class="flex items-center gap-2">
                                    <input
                                        type="number"
                                        name="grade"
                                        min="0"
                                        max="100"
                                        value="{{ $project->grade ?? '' }}"
                                        placeholder="0 – 100"
                                        class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all text-center"
                                        required
                                    >
                                    <span class="text-slate-400 text-sm font-medium">/100</span>
                                </div>
                            </div>

                            {{-- Quick grade buttons --}}
                            <div class="flex gap-1.5 flex-wrap">
                                @foreach([60, 70, 75, 80, 85, 90, 95, 100] as $quickGrade)
                                    <button
                                        type="button"
                                        onclick="this.closest('form').querySelector('[name=grade]').value = {{ $quickGrade }}"
                                        class="px-2 py-1 text-xs rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-800 transition-all font-medium"
                                    >{{ $quickGrade }}</button>
                                @endforeach
                            </div>

                            {{-- Catatan --}}
                            <div>
                                <label class="text-xs text-slate-500 font-medium block mb-1">Catatan Evaluasi (opsional)</label>
                                <textarea
                                    name="teacher_feedback"
                                    rows="3"
                                    placeholder="Tulis catatan konstruktif untuk siswa..."
                                    class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all resize-none"
                                >{{ $project->teacher_feedback }}</textarea>
                            </div>

                            <button
                                type="submit"
                                class="w-full bg-emerald-900 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-emerald-800 transition-all flex items-center justify-center gap-2 min-h-[44px]"
                            >
                                <span class="material-symbols-outlined" style="font-size:18px;">save</span>
                                {{ $isGraded ? 'Perbarui Nilai' : 'Simpan Nilai' }}
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm py-16 text-center">
            <span class="material-symbols-outlined text-slate-300" style="font-size:56px;">video_library</span>
            <p class="text-slate-500 font-medium mt-3">Belum ada proyek yang dikumpulkan.</p>
            <p class="text-slate-400 text-sm mt-1">Proyek dari siswa akan muncul di sini setelah mereka mengumpulkan.</p>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($projects->hasPages())
    <div class="mt-6">
        {{ $projects->links() }}
    </div>
@endif

@endsection
