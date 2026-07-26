@extends('layouts.admin')
@section('title', 'Manajemen Kuis')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="font-serif font-bold text-2xl text-slate-900">Manajemen Kuis</h1>
            <p class="text-sm text-slate-500">Buat kuis kelas, kelola butir soal pilihan ganda, dan atur token aktif.</p>
        </div>
        
        <div>
            <a href="{{ route('admin.quizzes.create') }}"
               class="inline-flex items-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm hover:shadow transition-all min-h-[44px]">
                <span class="material-symbols-outlined" style="font-size:20px;">add</span>
                Buat Kuis Baru
            </a>
        </div>
    </div>

    {{-- Tabel Kuis --}}
    <div class="bg-white border border-slate-200/60 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-100 uppercase tracking-wider text-[11px]">
                        <th class="px-6 py-4">Nama / Judul Kuis</th>
                        <th class="px-6 py-4 w-40 text-center">Status</th>
                        <th class="px-6 py-4 w-40 text-center">Token Ujian</th>
                        <th class="px-6 py-4 w-32 text-center">Durasi</th>
                        <th class="px-6 py-4 w-36 text-center">Mode Timer</th>
                        <th class="px-6 py-4 w-28 text-center">Soal</th>
                        <th class="px-6 py-4 text-center w-48">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700" x-data="quizIndexPage()">
                    @if($quizzes->isEmpty())
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <span class="material-symbols-outlined block mb-2 text-slate-300" style="font-size:36px;">quiz</span>
                                Belum ada kuis yang dibuat. Silakan klik tombol "Buat Kuis Baru".
                            </td>
                        </tr>
                    @else
                        @foreach($quizzes as $quiz)
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="px-6 py-4.5">
                                    <a href="{{ route('admin.quizzes.show', $quiz) }}" class="font-semibold text-slate-850 hover:text-emerald-700 transition-colors block">
                                        {{ $quiz->title }}
                                    </a>
                                    <span class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Dibuat {{ $quiz->created_at->diffForHumans() }}</span>
                                </td>
                                <td class="px-6 py-4.5 text-center">
                                    <form action="{{ route('admin.quizzes.toggle', $quiz) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider transition-all min-h-[32px]
                                                       {{ $quiz->is_active ? 'bg-emerald-100 text-emerald-800 border border-emerald-200 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $quiz->is_active ? 'bg-emerald-600' : 'bg-slate-400' }}"></span>
                                            {{ $quiz->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4.5 text-center">
                                    @if($quiz->is_active && $quiz->token)
                                        <div class="inline-flex items-center gap-2 bg-amber-50 border border-amber-200 rounded-lg px-2.5 py-1 text-amber-800 font-mono font-bold text-sm">
                                            <span>{{ $quiz->token }}</span>
                                            <button @click="copyToken('{{ $quiz->token }}')" 
                                                    class="text-amber-600 hover:text-amber-800 transition-colors flex items-center justify-center p-0.5 hover:bg-amber-100 rounded" 
                                                    title="Salin Token">
                                                <span class="material-symbols-outlined" style="font-size:16px;">content_copy</span>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-slate-400 font-medium">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4.5 text-center font-medium">
                                    {{ $quiz->duration_minutes }} Menit
                                </td>
                                <td class="px-6 py-4.5 text-center">
                                    @if($quiz->timer_mode === 'question')
                                        <span class="px-2 py-1 rounded-md bg-amber-100 border border-amber-200 text-amber-800 text-[10px] font-bold uppercase tracking-wider">Per-Soal (Quizizz)</span>
                                    @else
                                        <span class="px-2 py-1 rounded-md bg-blue-100 border border-blue-200 text-blue-800 text-[10px] font-bold uppercase tracking-wider">Kuis Global</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4.5 text-center font-bold text-slate-800">
                                    {{ $quiz->questions_count }} Soal
                                </td>
                                <td class="px-6 py-4.5">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Detail / Show --}}
                                        <a href="{{ route('admin.quizzes.show', $quiz) }}"
                                           class="p-2 rounded-xl text-slate-500 hover:bg-slate-100 hover:text-slate-900 transition-colors flex items-center justify-center min-w-[36px] min-h-[36px]"
                                           title="Kelola Soal Kuis">
                                            <span class="material-symbols-outlined" style="font-size:18px;">list_alt</span>
                                        </a>
                                        
                                        {{-- Edit --}}
                                        <a href="{{ route('admin.quizzes.edit', $quiz) }}"
                                           class="p-2 rounded-xl text-slate-500 hover:bg-slate-100 hover:text-slate-900 transition-colors flex items-center justify-center min-w-[36px] min-h-[36px]"
                                           title="Edit Kuis">
                                            <span class="material-symbols-outlined" style="font-size:18px;">edit</span>
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST" class="inline" 
                                              @submit.prevent="confirmDelete($el, '{{ addslashes($quiz->title) }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="p-2 rounded-xl text-red-500 hover:bg-red-50 hover:text-red-700 transition-colors flex items-center justify-center min-w-[36px] min-h-[36px]"
                                                    title="Hapus Kuis">
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

        {{-- Pagination --}}
        @if($quizzes->hasPages())
            <div class="px-6 py-4.5 border-t border-slate-100 bg-slate-50/30">
                {{ $quizzes->links() }}
            </div>
        @endif

    </div>

</div>
@endsection

@push('scripts')
<script>
function quizIndexPage() {
    return {
        copyToken(token) {
            navigator.clipboard.writeText(token).then(() => {
                alert('Token "' + token + '" berhasil disalin ke papan klip!');
            }).catch(err => {
                console.error('Gagal menyalin token: ', err);
            });
        },
        confirmDelete(formElement, title) {
            if (confirm('Apakah Anda yakin ingin menghapus kuis "' + title + '"? Tindakan ini akan menghapus semua butir soal kuis secara permanen.')) {
                formElement.submit();
            }
        }
    };
}
</script>
@endpush
