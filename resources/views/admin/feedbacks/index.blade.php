@extends('layouts.admin')
@section('title', 'Umpan Balik Siswa')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="font-serif font-bold text-2xl text-slate-900">Umpan Balik Siswa</h1>
        <p class="text-sm text-slate-500">Tinjau laporan kendala (bug), saran pengembangan, dan apresiasi yang dikirim oleh siswa secara langsung.</p>
    </div>

    {{-- Counters --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white border border-slate-200/60 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-700">
                <span class="material-symbols-outlined" style="font-size:28px;">bug_report</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Laporan Bug / Kendala</p>
                <p class="text-xl font-bold text-slate-800">{{ \App\Models\Feedback::where('category', 'bug')->count() }} Laporan</p>
            </div>
        </div>
        <div class="bg-white border border-slate-200/60 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-700">
                <span class="material-symbols-outlined" style="font-size:28px;">lightbulb</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Saran &amp; Masukan</p>
                <p class="text-xl font-bold text-slate-800">{{ \App\Models\Feedback::where('category', 'suggestion')->count() }} Masukan</p>
            </div>
        </div>
        <div class="bg-white border border-slate-200/60 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-700">
                <span class="material-symbols-outlined" style="font-size:28px;">sentiment_very_satisfied</span>
            </div>
            <div>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Kesan &amp; Apresiasi</p>
                <p class="text-xl font-bold text-slate-800">{{ \App\Models\Feedback::where('category', 'appreciation')->count() }} Apresiasi</p>
            </div>
        </div>
    </div>

    {{-- Daftar Umpan Balik --}}
    <div class="space-y-4" x-data="feedbackIndexPage()">
        @if($feedbacks->isEmpty())
            <div class="bg-white border border-slate-200/60 rounded-2xl p-12 text-center shadow-sm">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <span class="material-symbols-outlined" style="font-size:32px;">rate_review</span>
                </div>
                <h3 class="font-serif font-bold text-lg text-slate-800 mb-1">Umpan Balik Kosong</h3>
                <p class="text-sm text-slate-500 max-w-md mx-auto">
                    Belum ada siswa yang mengirimkan umpan balik atau masukan mengenai aplikasi BioCinta Cell.
                </p>
            </div>
        @else
            @foreach($feedbacks as $fb)
                <div class="bg-white border rounded-2xl p-5 shadow-sm hover:shadow transition-shadow relative overflow-hidden flex flex-col md:flex-row md:items-start justify-between gap-4
                            {{ $fb->category === 'bug' ? 'border-red-200 bg-gradient-to-r from-red-50/10 to-transparent' : ($fb->category === 'appreciation' ? 'border-amber-200 bg-gradient-to-r from-amber-50/10 to-transparent' : 'border-slate-200') }}">
                    
                    {{-- Left content --}}
                    <div class="space-y-3 flex-1">
                        <div class="flex flex-wrap items-center gap-2.5">
                            {{-- Category Badge --}}
                            @if($fb->category === 'bug')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-red-100 border border-red-200 text-red-800 text-[10px] font-bold uppercase tracking-wider">
                                    <span class="material-symbols-outlined" style="font-size:12px;">bug_report</span>
                                    Bug / Error
                                </span>
                            @elseif($fb->category === 'appreciation')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-amber-100 border border-amber-200 text-amber-800 text-[10px] font-bold uppercase tracking-wider">
                                    <span class="material-symbols-outlined" style="font-size:12px;">sentiment_very_satisfied</span>
                                    Apresiasi
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-blue-100 border border-blue-200 text-blue-800 text-[10px] font-bold uppercase tracking-wider">
                                    <span class="material-symbols-outlined" style="font-size:12px;">lightbulb</span>
                                    Saran
                                </span>
                            @endif

                            {{-- Student Info --}}
                            <span class="text-xs font-semibold text-slate-700">
                                {{ $fb->user->name }}
                            </span>
                            <span class="text-xs text-slate-400">
                                (NIS: {{ $fb->user->nis }})
                            </span>
                            <span class="text-[10px] text-slate-400 font-medium">
                                • {{ $fb->created_at->diffForHumans() }}
                            </span>
                        </div>

                        {{-- Message Teks --}}
                        <p class="text-slate-800 text-sm leading-relaxed font-medium">
                            {!! nl2br(e($fb->message)) !!}
                        </p>
                    </div>

                    {{-- Action Button --}}
                    <div class="flex items-center justify-end flex-shrink-0">
                        <form action="{{ route('admin.feedbacks.destroy', $fb) }}" method="POST"
                              @submit.prevent="confirmDelete($el)">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center justify-center p-2 rounded-xl text-red-500 hover:bg-red-50 hover:text-red-700 transition-all min-w-[38px] min-h-[38px] border border-transparent hover:border-red-200"
                                    title="Hapus Umpan Balik">
                                <span class="material-symbols-outlined" style="font-size:20px;">delete</span>
                            </button>
                        </form>
                    </div>

                </div>
            @endforeach

            {{-- Pagination --}}
            @if($feedbacks->hasPages())
                <div class="pt-2">
                    {{ $feedbacks->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
function feedbackIndexPage() {
    return {
        confirmDelete(formElement) {
            if (confirm('Apakah Anda yakin ingin menghapus umpan balik ini? Tindakan ini tidak dapat dibatalkan.')) {
                formElement.submit();
            }
        }
    };
}
</script>
@endpush
