@extends('layouts.auth')
@section('title', 'Masuk ke BioCinta Cell')

@section('content')
<div class="min-h-screen flex flex-col md:flex-row">
    {{-- Panel Kanan: Form Login --}}
    <div class="flex-1 flex items-center justify-center p-6 md:p-12">
        <div class="w-full max-w-sm">
            <h2 class="text-slate-800 font-serif text-2xl font-bold mb-1 text-center">Selamat Datang! 👋</h2>
            <p class="text-slate-500 text-sm mb-8 text-center">Masuk menggunakan NIS dan Kata Sandi.</p>

            {{-- Error Validasi --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 flex items-start gap-3">
                    <span class="text-red-500 text-lg mt-0.5">⚠️</span>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p class="text-red-600 text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Form Login --}}
            <form
                action="{{ url('/login') }}"
                method="POST"
                x-data="{ loading: false }"
                @submit="loading = true"
            >
                @csrf

                {{-- Field NIS --}}
                <div class="mb-5">
                    <label for="nis" class="block text-sm font-semibold text-slate-700 mb-2">
                        Nomor Induk Siswa (NIS)
                    </label>
                    <input
                        type="text"
                        id="nis"
                        name="nis"
                        value="{{ old('nis') }}"
                        autocomplete="username"
                        inputmode="numeric"
                        placeholder="Masukkan NIS kamu"
                        class="w-full min-h-[44px] px-4 py-3 rounded-xl border text-sm
                               transition-all outline-none
                               {{ $errors->has('nis') ? 'border-red-400 bg-red-50 focus:ring-2 focus:ring-red-300' : 'border-slate-200 bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100' }}"
                        required
                    >
                </div>

                {{-- Field Password --}}
                <div class="mb-6" x-data="{ showPass: false }">
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <input
                            :type="showPass ? 'text' : 'password'"
                            id="password"
                            name="password"
                            autocomplete="current-password"
                            placeholder="Masukkan kata sandi"
                            class="w-full min-h-[44px] px-4 py-3 pr-12 rounded-xl border text-sm
                                   transition-all outline-none
                                   {{ $errors->has('password') ? 'border-red-400 bg-red-50 focus:ring-2 focus:ring-red-300' : 'border-slate-200 bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100' }}"
                            required
                        >
                        <button
                            type="button"
                            @click="showPass = !showPass"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors p-1 min-h-[44px] flex items-center"
                            aria-label="Tampilkan/sembunyikan kata sandi"
                        >
                            {{-- Icon Eye (Tampilkan Password) --}}
                            <svg x-show="!showPass" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.43 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            {{-- Icon Eye-Slash (Sembunyikan Password) --}}
                            <svg x-show="showPass" style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Tombol Submit dengan Anti-Double Click --}}
                <button
                    type="submit"
                    id="btn-login"
                    :disabled="loading"
                    class="w-full min-h-[44px] py-3 px-6 rounded-xl font-bold text-sm transition-all shadow-md
                           bg-emerald-900 text-white hover:bg-emerald-800 active:scale-95
                           disabled:opacity-60 disabled:cursor-not-allowed disabled:scale-100"
                >
                    <span x-show="!loading"> Masuk ke BioCinta Cell</span>
                    <span x-show="loading" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        Memverifikasi...
                    </span>
                </button>
            </form>

            {{-- Footer Info --}}
            <p class="text-center text-xs text-slate-400 mt-8">
                Lupa kata sandi? Hubungi Guru Biologi kamu. 🌿
            </p>
            <p class="text-center text-xs text-slate-300 mt-2">
                BioCinta Cell
            </p>
        </div>
    </div>
</div>
@endsection
