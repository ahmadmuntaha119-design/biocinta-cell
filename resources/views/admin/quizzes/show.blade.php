@extends('layouts.admin')
@section('title', 'Detail Kuis — ' . $quiz->title)

@section('content')
<div class="space-y-6">

    {{-- Breadcrumb & Back --}}
    <div class="space-y-2">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('admin.quizzes.index') }}" class="hover:text-emerald-700 transition-colors">Manajemen Kuis</a>
            <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
            <span class="font-semibold text-slate-700">Detail Kuis</span>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.quizzes.index') }}" class="text-slate-500 hover:text-slate-900 transition-colors flex items-center justify-center p-1 hover:bg-slate-100 rounded-lg">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <h1 class="font-serif font-bold text-2xl text-slate-900">Detail Kuis</h1>
        </div>
    </div>

    {{-- Info Card & Token Control --}}
    <div class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-3">
            <div class="flex flex-wrap items-center gap-2">
                <h2 class="font-serif font-bold text-lg text-slate-800 mr-2">{{ $quiz->title }}</h2>
                @if($quiz->timer_mode === 'question')
                    <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 text-[10px] font-bold uppercase tracking-wider">Per-Soal (Quizizz)</span>
                @else
                    <span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 text-[10px] font-bold uppercase tracking-wider">Kuis Global</span>
                @endif
            </div>
            
            <div class="flex flex-wrap gap-x-6 gap-y-2 text-xs text-slate-500">
                <div class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-slate-400" style="font-size:16px;">schedule</span>
                    <span>Durasi Ujian: <strong>{{ $quiz->duration_minutes }} Menit</strong></span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-slate-400" style="font-size:16px;">article</span>
                    <span>Jumlah Soal: <strong>{{ $quiz->questions->count() }} Butir Soal</strong></span>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200/40">
            {{-- Token Display --}}
            <div>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Token Ujian Aktif</p>
                <div class="flex items-center gap-1.5">
                    @if($quiz->is_active && $quiz->token)
                        <span class="font-mono font-bold text-base text-amber-800 bg-amber-100/60 border border-amber-200/80 px-2 py-0.5 rounded">{{ $quiz->token }}</span>
                    @else
                        <span class="text-xs text-slate-400 font-medium italic">Kuis Nonaktif</span>
                    @endif
                </div>
            </div>

            {{-- Toggle Button --}}
            <div class="pt-2 sm:pt-0 sm:border-l sm:border-slate-200 sm:pl-4">
                <form action="{{ route('admin.quizzes.toggle', $quiz) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all min-h-[40px] shadow-sm
                                   {{ $quiz->is_active ? 'bg-red-50 text-red-700 border border-red-200 hover:bg-red-100' : 'bg-emerald-700 text-white hover:bg-emerald-800' }}">
                        <span class="material-symbols-outlined" style="font-size:16px;">
                            {{ $quiz->is_active ? 'block' : 'key' }}
                        </span>
                        {{ $quiz->is_active ? 'Matikan Kuis' : 'Aktifkan Token Kuis' }}
                    </button>
                </form>
            </div>

            {{-- Export Excel Button --}}
            <div class="pt-2 sm:pt-0 sm:border-l sm:border-slate-200 sm:pl-4">
                <a href="{{ route('admin.quizzes.export', $quiz) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all min-h-[40px] shadow-sm bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100"
                   title="Download nilai seluruh peserta kuis dalam format Excel">
                    <span class="material-symbols-outlined" style="font-size:16px;">download</span>
                    Export Nilai Excel
                </a>
            </div>
        </div>
    </div>

    {{-- Panel Butir Soal --}}
    <div class="bg-white border border-slate-200/60 rounded-2xl overflow-hidden shadow-sm" x-data="questionsTable()">
        
        {{-- Section Header --}}
        <div class="p-5 border-b border-slate-100 flex items-center justify-between gap-4 bg-slate-50/30">
            <div>
                <h3 class="font-serif font-bold text-slate-800 text-base">Butir Soal Pilihan Ganda</h3>
                <p class="text-xs text-slate-500">Daftar soal yang akan dikerjakan siswa.</p>
            </div>
            
            <a href="{{ route('admin.quizzes.questions.create', $quiz) }}"
               class="inline-flex items-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white px-4 py-2 rounded-xl text-xs font-semibold shadow-sm hover:shadow transition-colors min-h-[38px]">
                <span class="material-symbols-outlined" style="font-size:16px;">add</span>
                Tambah Soal Baru
            </a>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-100 uppercase tracking-wider text-[11px]">
                        <th class="px-6 py-4 text-center w-16">No</th>
                        <th class="px-6 py-4">Butir Soal & Pilihan Jawaban</th>
                        @if($quiz->timer_mode === 'question')
                            <th class="px-6 py-4 text-center w-32">Batas Waktu</th>
                        @endif
                        <th class="px-6 py-4 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @if($quiz->questions->isEmpty())
                        <tr>
                            <td colspan="{{ $quiz->timer_mode === 'question' ? '4' : '3' }}" class="px-6 py-12 text-center text-slate-400">
                                <span class="material-symbols-outlined block mb-2 text-slate-300" style="font-size:36px;">ballot</span>
                                Kuis ini belum memiliki soal. Klik "Tambah Soal Baru" untuk mulai memasukkan soal.
                            </td>
                        </tr>
                    @else
                        @foreach($quiz->questions as $index => $question)
                            <tr class="hover:bg-slate-50/20 transition-colors align-top">
                                <td class="px-6 py-5 text-center font-bold text-slate-500">
                                    {{ $question->sort_order }}
                                </td>
                                <td class="px-6 py-5 space-y-3">
                                    {{-- Teks Pertanyaan --}}
                                    <div class="font-semibold text-slate-900 leading-relaxed text-sm">
                                        {!! nl2br(e($question->question_text)) !!}
                                    </div>
                                    
                                    {{-- Pilihan Opsi A-D --}}
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 pt-1 text-xs">
                                        <div class="flex items-start gap-2 p-2 rounded-lg border {{ $question->correct_option === 'a' ? 'bg-emerald-50 border-emerald-300 text-emerald-800 font-bold' : 'border-slate-100 text-slate-600' }}">
                                            <span class="w-5 h-5 rounded-full flex items-center justify-center border font-bold text-[10px] flex-shrink-0 {{ $question->correct_option === 'a' ? 'bg-emerald-600 border-emerald-600 text-white' : 'bg-slate-100 border-slate-200 text-slate-500' }}">A</span>
                                            <span>{{ $question->option_a }}</span>
                                        </div>
                                        
                                        <div class="flex items-start gap-2 p-2 rounded-lg border {{ $question->correct_option === 'b' ? 'bg-emerald-50 border-emerald-300 text-emerald-800 font-bold' : 'border-slate-100 text-slate-600' }}">
                                            <span class="w-5 h-5 rounded-full flex items-center justify-center border font-bold text-[10px] flex-shrink-0 {{ $question->correct_option === 'b' ? 'bg-emerald-600 border-emerald-600 text-white' : 'bg-slate-100 border-slate-200 text-slate-500' }}">B</span>
                                            <span>{{ $question->option_b }}</span>
                                        </div>

                                        <div class="flex items-start gap-2 p-2 rounded-lg border {{ $question->correct_option === 'c' ? 'bg-emerald-50 border-emerald-300 text-emerald-800 font-bold' : 'border-slate-100 text-slate-600' }}">
                                            <span class="w-5 h-5 rounded-full flex items-center justify-center border font-bold text-[10px] flex-shrink-0 {{ $question->correct_option === 'c' ? 'bg-emerald-600 border-emerald-600 text-white' : 'bg-slate-100 border-slate-200 text-slate-500' }}">C</span>
                                            <span>{{ $question->option_c }}</span>
                                        </div>

                                        <div class="flex items-start gap-2 p-2 rounded-lg border {{ $question->correct_option === 'd' ? 'bg-emerald-50 border-emerald-300 text-emerald-800 font-bold' : 'border-slate-100 text-slate-600' }}">
                                            <span class="w-5 h-5 rounded-full flex items-center justify-center border font-bold text-[10px] flex-shrink-0 {{ $question->correct_option === 'd' ? 'bg-emerald-600 border-emerald-600 text-white' : 'bg-slate-100 border-slate-200 text-slate-500' }}">D</span>
                                            <span>{{ $question->option_d }}</span>
                                        </div>
                                    </div>
                                </td>
                                @if($quiz->timer_mode === 'question')
                                    <td class="px-6 py-5 text-center font-semibold text-slate-700">
                                        {{ $question->duration_seconds ?? 30 }} Detik
                                    </td>
                                @endif
                                <td class="px-6 py-5">
                                    <div class="flex items-center justify-center gap-1.5">
                                        {{-- Edit Soal --}}
                                        <a href="{{ route('admin.questions.edit', $question) }}"
                                           class="p-2 rounded-xl text-slate-500 hover:bg-slate-100 hover:text-slate-900 transition-colors flex items-center justify-center min-w-[36px] min-h-[36px]"
                                           title="Edit Soal">
                                            <span class="material-symbols-outlined" style="font-size:18px;">edit</span>
                                        </a>

                                        {{-- Hapus Soal --}}
                                        <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline"
                                              @submit.prevent="confirmDelete($el)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="p-2 rounded-xl text-red-500 hover:bg-red-50 hover:text-red-700 transition-colors flex items-center justify-center min-w-[36px] min-h-[36px]"
                                                    title="Hapus Soal">
                                                <span class="material-symbols-outlined" style="font-size:18px;">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
function questionsTable() {
    return {
        confirmDelete(formElement) {
            if (confirm('Apakah Anda yakin ingin menghapus butir soal ini secara permanen?')) {
                formElement.submit();
            }
        }
    };
}
</script>
@endpush
