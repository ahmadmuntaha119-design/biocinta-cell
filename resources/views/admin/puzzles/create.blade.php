@extends('layouts.admin')
@section('title', 'Tambah Soal Puzzle')
@section('content')
<div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
    <a href="{{ route('admin.puzzles.index') }}" class="hover:text-emerald-700 transition-colors font-medium">Bank Soal Puzzle</a>
    <span class="material-symbols-outlined" style="font-size:16px;">chevron_right</span>
    <span class="text-slate-800 font-semibold">Tambah Soal Baru</span>
</div>
<div class="mb-6">
    <h1 class="font-serif font-bold text-2xl text-slate-900 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-emerald-900 flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-white" style="font-size:20px;">add_circle</span>
        </div>
        Tambah Soal Puzzle Baru
    </h1>
    <p class="text-slate-500 text-sm mt-1 ml-[52px]">Isi 4 pasang deskripsi fungsi dan nama organel untuk tantangan harian siswa.</p>
</div>
@include('admin.puzzles._form', ['formAction' => route('admin.puzzles.store')])
@endsection
