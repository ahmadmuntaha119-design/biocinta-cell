@extends('layouts.app')
@section('title', 'Masuk Kuis')
@section('meta_description', 'Masukkan token kuis dari guru Anda untuk mulai mengerjakan ujian di BioCinta Cell.')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-950 via-emerald-900 to-slate-900 flex items-center justify-center px-4 py-16">

    {{-- Decorative blobs --}}
    <div class="absolute top-20 left-1/4 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-20 right-1/4 w-80 h-80 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10" x-data="{ token: '', loading: false }">

        {{-- Brand Header --}}
        <div class="text-center mb-8">
            <h1 class="font-serif font-bold text-3xl text-white">Gerbang Kuis</h1>
            <p class="text-emerald-300 text-sm mt-2">Masukkan token 6-karakter dari guru Anda untuk memulai kuis.</p>
        </div>

        {{-- Error Alert --}}
        @if($errors->has('token'))
            <div class="mb-4 bg-red-500/20 border border-red-400/40 text-red-200 px-5 py-3.5 rounded-xl text-sm flex items-start gap-3 backdrop-blur-sm">
                <span class="material-symbols-outlined text-red-300 flex-shrink-0 mt-0.5" style="font-size: 20px;">error</span>
                <span>{{ $errors->first('token') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-500/20 border border-red-400/40 text-red-200 px-5 py-3.5 rounded-xl text-sm flex items-start gap-3">
                <span class="material-symbols-outlined text-red-300 flex-shrink-0" style="font-size: 20px;">warning</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-4 bg-blue-500/20 border border-blue-400/40 text-blue-200 px-5 py-3.5 rounded-xl text-sm flex items-start gap-3">
                <span class="material-symbols-outlined text-blue-300 flex-shrink-0" style="font-size: 20px;">info</span>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        {{-- Token Input Card --}}
        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-8 shadow-2xl">
            <form action="{{ route('quiz.join') }}" method="POST" @submit="loading = true" x-ref="form">
                @csrf

                <div class="space-y-5">
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-emerald-300 block mb-2">
                            Token Kuis
                        </label>
                        <input
                            type="text"
                            name="token"
                            id="token-input"
                            x-model="token"
                            @input="token = token.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 6)"
                            value="{{ old('token') }}"
                            placeholder="Biocin"
                            autocomplete="off"
                            spellcheck="false"
                            maxlength="6"
                            class="w-full text-center text-3xl font-mono font-bold tracking-[0.5em] text-white bg-white/10 border-2 border-white/30 focus:border-amber-400 rounded-xl px-6 py-5 focus:outline-none placeholder-white/30 transition-all uppercase min-h-[80px]"
                        >
                    </div>

                    {{-- Indikator karakter --}}
                    <div class="flex justify-center gap-2">
                        @for($i = 0; $i < 6; $i++)
                            <div
                                :class="token.length > {{ $i }} ? 'bg-amber-400 border-amber-400 scale-110' : 'bg-white/10 border-white/20'"
                                class="w-3 h-3 rounded-full border-2 transition-all duration-200"
                            ></div>
                        @endfor
                    </div>

                    <button
                        type="submit"
                        :disabled="loading || token.length !== 6"
                        :class="token.length === 6 && !loading ? 'bg-amber-500 hover:bg-amber-400 text-emerald-950' : 'bg-white/10 text-white/40 cursor-not-allowed'"
                        class="w-full py-4 rounded-xl font-bold text-base transition-all duration-200 min-h-[56px] flex items-center justify-center gap-2 shadow-lg"
                    >
                        <span x-show="!loading" class="material-symbols-outlined" style="font-size: 22px;">login</span>
                        <span x-show="loading" class="material-symbols-outlined animate-spin" style="font-size: 22px;">progress_activity</span>
                        <span x-text="loading ? 'Memvalidasi Token...' : 'Mulai Kuis'"></span>
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('home') }}" class="text-sm text-emerald-400 hover:text-emerald-200 transition-colors">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
