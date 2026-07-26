@extends('layouts.admin')
@section('title', 'Tambah Soal Baru — ' . $quiz->title)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Breadcrumb & Title --}}
    <div class="space-y-2">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('admin.quizzes.index') }}" class="hover:text-emerald-700 transition-colors">Manajemen Kuis</a>
            <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
            <a href="{{ route('admin.quizzes.show', $quiz) }}" class="hover:text-emerald-700 transition-colors">Detail Kuis</a>
            <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
            <span class="font-semibold text-slate-700">Tambah Soal</span>
        </div>
        <h1 class="font-serif font-bold text-2xl text-slate-900">Tambah Soal Baru</h1>
        <p class="text-sm text-slate-500">Kuis: <strong class="text-slate-800">{{ $quiz->title }}</strong></p>
    </div>

    {{-- Form --}}
    <div class="bg-white border border-slate-200/60 rounded-2xl p-6 md:p-8 shadow-sm">
        <form action="{{ route('admin.quizzes.questions.store', $quiz) }}" method="POST" class="space-y-6">
            @csrf

            {{-- Teks Pertanyaan --}}
            <div class="space-y-2">
                <label for="question_text" class="text-sm font-bold text-slate-700 block">Pertanyaan / Soal <span class="text-red-500">*</span></label>
                <textarea
                    id="question_text"
                    name="question_text"
                    rows="4"
                    placeholder="Tuliskan pertanyaan kuis di sini..."
                    required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/10"
                >{{ old('question_text') }}</textarea>
            </div>

            {{-- Opsi A & B --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="option_a" class="text-sm font-bold text-slate-700 block">Pilihan Jawaban A <span class="text-red-500">*</span></label>
                    <input
                        type="text"
                        id="option_a"
                        name="option_a"
                        value="{{ old('option_a') }}"
                        placeholder="Teks pilihan A..."
                        required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/10 min-h-[44px]"
                    />
                </div>
                <div class="space-y-2">
                    <label for="option_b" class="text-sm font-bold text-slate-700 block">Pilihan Jawaban B <span class="text-red-500">*</span></label>
                    <input
                        type="text"
                        id="option_b"
                        name="option_b"
                        value="{{ old('option_b') }}"
                        placeholder="Teks pilihan B..."
                        required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/10 min-h-[44px]"
                    />
                </div>
            </div>

            {{-- Opsi C & D --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="option_c" class="text-sm font-bold text-slate-700 block">Pilihan Jawaban C <span class="text-red-500">*</span></label>
                    <input
                        type="text"
                        id="option_c"
                        name="option_c"
                        value="{{ old('option_c') }}"
                        placeholder="Teks pilihan C..."
                        required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/10 min-h-[44px]"
                    />
                </div>
                <div class="space-y-2">
                    <label for="option_d" class="text-sm font-bold text-slate-700 block">Pilihan Jawaban D <span class="text-red-500">*</span></label>
                    <input
                        type="text"
                        id="option_d"
                        name="option_d"
                        value="{{ old('option_d') }}"
                        placeholder="Teks pilihan D..."
                        required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/10 min-h-[44px]"
                    />
                </div>
            </div>

            {{-- Kunci Jawaban & Urutan Tampil --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- Kunci Jawaban --}}
                <div class="space-y-2">
                    <label for="correct_option" class="text-sm font-bold text-slate-700 block">Kunci Jawaban <span class="text-red-500">*</span></label>
                    <select
                        id="correct_option"
                        name="correct_option"
                        required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/10 min-h-[44px] bg-white"
                    >
                        <option value="" disabled selected>Pilih Kunci...</option>
                        <option value="a" {{ old('correct_option') === 'a' ? 'selected' : '' }}>Opsi A</option>
                        <option value="b" {{ old('correct_option') === 'b' ? 'selected' : '' }}>Opsi B</option>
                        <option value="c" {{ old('correct_option') === 'c' ? 'selected' : '' }}>Opsi C</option>
                        <option value="d" {{ old('correct_option') === 'd' ? 'selected' : '' }}>Opsi D</option>
                    </select>
                </div>

                {{-- Urutan Tampil --}}
                <div class="space-y-2">
                    <label for="sort_order" class="text-sm font-bold text-slate-700 block">Nomor Urutan Tampil</label>
                    <input
                        type="number"
                        id="sort_order"
                        name="sort_order"
                        value="{{ old('sort_order', $quiz->questions->count() + 1) }}"
                        min="0"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/10 min-h-[44px]"
                    />
                </div>

                {{-- Batas Waktu Soal (Hanya jika kuis bertipe per-soal) --}}
                <div class="space-y-2">
                    <label for="duration_seconds" class="text-sm font-bold text-slate-700 block">
                        Durasi Soal (Detik)
                        @if($quiz->timer_mode !== 'question')
                            <span class="text-slate-400 font-normal">(Non-aktif)</span>
                        @else
                            <span class="text-red-500">*</span>
                        @endif
                    </label>
                    <input
                        type="number"
                        id="duration_seconds"
                        name="duration_seconds"
                        value="{{ old('duration_seconds', 30) }}"
                        min="5"
                        max="3600"
                        {{ $quiz->timer_mode !== 'question' ? 'disabled' : 'required' }}
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/10 min-h-[44px] disabled:bg-slate-100 disabled:text-slate-400"
                    />
                    @if($quiz->timer_mode !== 'question')
                        <span class="text-[10px] text-slate-400 block mt-1">Non-aktif karena kuis menggunakan mode timer Global.</span>
                    @endif
                </div>

            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.quizzes.show', $quiz) }}"
                   class="inline-flex items-center justify-center border border-slate-200 text-slate-700 px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-slate-50 transition-colors min-h-[44px]">
                    Batal
                </a>
                <button
                    type="submit"
                    class="inline-flex items-center justify-center bg-emerald-700 hover:bg-emerald-800 text-white px-6 py-2.5 rounded-xl text-sm font-semibold shadow-sm hover:shadow transition-colors min-h-[44px]"
                >
                    Simpan Soal
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
