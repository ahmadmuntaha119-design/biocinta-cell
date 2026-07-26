@extends('layouts.admin')
@section('title', 'Edit Soal Puzzle — ' . $puzzle->publish_date->format('d M Y'))
@section('content')
<div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
    <a href="{{ route('admin.puzzles.index') }}" class="hover:text-emerald-700 transition-colors font-medium">Bank Soal Puzzle</a>
    <span class="material-symbols-outlined" style="font-size:16px;">chevron_right</span>
    <span class="text-slate-800 font-semibold">Edit: {{ $puzzle->publish_date->format('d M Y') }}</span>
</div>
<div class="mb-6">
    <h1 class="font-serif font-bold text-2xl text-slate-900 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-white" style="font-size:20px;">edit_calendar</span>
        </div>
        Edit Soal Puzzle
    </h1>
    <p class="text-slate-500 text-sm mt-1 ml-[52px]">
        Memperbarui soal untuk tanggal
        <strong class="text-slate-800">{{ $puzzle->publish_date->translatedFormat('l, d M Y') }}</strong>
        @if($puzzle->publish_date->isToday())
            <span class="ml-2 bg-emerald-100 text-emerald-700 text-xs font-semibold px-2 py-0.5 rounded-full">Aktif Sekarang</span>
        @endif
        · Sudah dimainkan oleh <strong class="text-amber-700">{{ $puzzle->scores()->count() }} siswa</strong>
    </p>
</div>
@include('admin.puzzles._form', [
    'formAction' => route('admin.puzzles.update', $puzzle),
])
@endsection
