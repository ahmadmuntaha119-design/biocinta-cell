@extends('layouts.admin')
@section('title', 'Edit Kuis')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Breadcrumb & Title --}}
    <div class="space-y-2">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('admin.quizzes.index') }}" class="hover:text-emerald-700 transition-colors">Manajemen Kuis</a>
            <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
            <span class="font-semibold text-slate-700">Edit Kuis</span>
        </div>
        <h1 class="font-serif font-bold text-2xl text-slate-900">Edit Kuis</h1>
    </div>

    {{-- Form --}}
    <div class="bg-white border border-slate-200/60 rounded-2xl p-6 md:p-8 shadow-sm">
        <form action="{{ route('admin.quizzes.update', $quiz) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Judul Kuis --}}
            <div class="space-y-2">
                <label for="title" class="text-sm font-bold text-slate-700 block">Judul Kuis <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title', $quiz->title) }}"
                    placeholder="Contoh: Ulangan Harian Bab Sel Hewan & Tumbuhan"
                    required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/10 min-h-[44px]"
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Durasi (Menit) --}}
                <div class="space-y-2">
                    <label for="duration_minutes" class="text-sm font-bold text-slate-700 block">Durasi Kuis (Menit) <span class="text-red-500">*</span></label>
                    <input
                        type="number"
                        id="duration_minutes"
                        name="duration_minutes"
                        value="{{ old('duration_minutes', $quiz->duration_minutes) }}"
                        min="1"
                        max="600"
                        required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/10 min-h-[44px]"
                    />
                    <span class="text-xs text-slate-400 block mt-1">Siswa harus menyelesaikan kuis dalam batas waktu ini.</span>
                </div>

                {{-- Mode Timer --}}
                <div class="space-y-2">
                    <label for="timer_mode" class="text-sm font-bold text-slate-700 block">Mode Timer Kuis <span class="text-red-500">*</span></label>
                    <select
                        id="timer_mode"
                        name="timer_mode"
                        required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/10 min-h-[44px] bg-white"
                    >
                        <option value="quiz" {{ old('timer_mode', $quiz->timer_mode) === 'quiz' ? 'selected' : '' }}>Kuis Global (Satu Timer Utama)</option>
                        <option value="question" {{ old('timer_mode', $quiz->timer_mode) === 'question' ? 'selected' : '' }}>Per-Soal (Quizizz Style - Sekuensial)</option>
                    </select>
                    <span class="text-xs text-slate-400 block mt-1">Gunakan **Per-Soal** untuk mencegah kecurangan dan membatasi waktu per butir soal.</span>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.quizzes.index') }}"
                   class="inline-flex items-center justify-center border border-slate-200 text-slate-700 px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-50 transition-colors min-h-[44px]">
                    Batal
                </a>
                <button
                    type="submit"
                    class="inline-flex items-center justify-center bg-emerald-700 hover:bg-emerald-800 text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-sm hover:shadow transition-colors min-h-[44px]"
                >
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
