@extends('layouts.auth')
@section('title', 'Ganti Kata Sandi — BioCinta Cell')

@section('content')
    <div class="min-h-screen bg-slate-50 flex items-center justify-center p-4">
        <div class="w-full max-w-md">

            {{-- Card Utama --}}
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                {{-- Identitas Pengguna — Diubah ke Hijau Soft & Mint --}}
                <div class="flex items-center gap-3 px-6 py-4 bg-emerald-50/60 border-b border-emerald-100/80">
                    <div class="w-10 h-10 bg-emerald-900 rounded-full flex items-center justify-center flex-shrink-0">
                        <span
                            class="text-white font-bold text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="text-slate-800 font-semibold text-sm">{{ auth()->user()->name }}</p>
                        <p class="text-slate-400 text-xs">NIS: {{ auth()->user()->nis }}</p>
                    </div>
                    <span class="ml-auto bg-emerald-100 text-emerald-700 text-xs font-semibold px-1 py-1 rounded-lg">Pertama
                        Kali</span>
                </div>

                {{-- Form --}}
                <div class="px-6 py-6">

                    {{-- Error Validasi --}}
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-5 flex items-start gap-3">
                            <span class="text-red-500 text-base mt-0.5 flex-shrink-0">⚠️</span>
                            <div class="space-y-1">
                                @foreach ($errors->all() as $error)
                                    <p class="text-red-600 text-sm">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('password.update') }}" method="POST"
                        x-data="{ loading: false, newPass: '', confirmPass: '', showNew: false, showConfirm: false }"
                        @submit="loading = true">
                        @csrf

                        {{-- Password Baru --}}
                        <div class="mb-5">
                            <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                                Kata Sandi Baru
                            </label>
                            <div class="relative">
                                <input :type="showNew ? 'text' : 'password'" id="password" name="password" x-model="newPass"
                                    autocomplete="new-password" placeholder="Min. 8 karakter, huruf & angka"
                                    class="w-full min-h-[44px] px-4 py-3 pr-12 rounded-xl border text-sm
                                           transition-all outline-none
                                           {{ $errors->has('password') ? 'border-red-400 bg-red-50 focus:ring-2 focus:ring-red-300' : 'border-slate-200 bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100' }}"
                                    required>
                                <button type="button" @click="showNew = !showNew"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors p-1 min-h-[44px] flex items-center"
                                    aria-label="Tampilkan/sembunyikan kata sandi">
                                    {{-- Icon Eye (Tampilkan Password) --}}
                                    <svg x-show="!showNew" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.43 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    {{-- Icon Eye-Slash (Sembunyikan Password) --}}
                                    <svg x-show="showNew" style="display: none;" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                    </svg>
                                </button>
                            </div>

                            {{-- Indikator kekuatan password — Bar terakhir diselaraskan ke warna Teal --}}
                            <div class="mt-2 space-y-1" x-show="newPass.length > 0">
                                <div class="flex gap-1">
                                    <div class="h-1 flex-1 rounded-full transition-all"
                                        :class="newPass.length >= 8 ? 'bg-emerald-500' : 'bg-slate-200'"></div>
                                    <div class="h-1 flex-1 rounded-full transition-all"
                                        :class="/[A-Z]/.test(newPass) ? 'bg-emerald-500' : 'bg-slate-200'"></div>
                                    <div class="h-1 flex-1 rounded-full transition-all"
                                        :class="/[0-9]/.test(newPass) ? 'bg-emerald-500' : 'bg-slate-200'"></div>
                                    <div class="h-1 flex-1 rounded-full transition-all"
                                        :class="/[a-z]/.test(newPass) && /[A-Z]/.test(newPass) && /[0-9]/.test(newPass) && newPass.length >= 8 ? 'bg-teal-500' : 'bg-slate-200'">
                                    </div>
                                </div>
                                <div class="flex gap-3 text-xs text-slate-400">
                                    <span
                                        :class="/[A-Z]/.test(newPass) && /[a-z]/.test(newPass) ? 'text-emerald-600' : ''">✓
                                        Huruf besar & kecil</span>
                                    <span :class="/[0-9]/.test(newPass) ? 'text-emerald-600' : ''">✓ Angka</span>
                                    <span :class="newPass.length >= 8 ? 'text-emerald-600' : ''">✓ Min. 8 karakter</span>
                                </div>
                            </div>
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">
                                Konfirmasi Kata Sandi Baru
                            </label>
                            <div class="relative">
                                <input :type="showConfirm ? 'text' : 'password'" id="password_confirmation"
                                    name="password_confirmation" x-model="confirmPass" autocomplete="new-password"
                                    placeholder="Ketik ulang kata sandi baru"
                                    class="w-full min-h-[44px] px-4 py-3 pr-12 rounded-xl border text-sm
                                           transition-all outline-none
                                           border-slate-200 bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" :class="{
                                        'border-emerald-500 bg-emerald-50': confirmPass.length > 0 && newPass === confirmPass,
                                        'border-red-400 bg-red-50': confirmPass.length > 0 && newPass !== confirmPass
                                    }" required>
                                <button type="button" @click="showConfirm = !showConfirm"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors min-h-[44px] flex items-center px-1"
                                    aria-label="Toggle visibilitas konfirmasi">
                                    <span x-text="showConfirm ? '🙈' : '👁️'" class="text-sm"></span>
                                </button>
                            </div>
                            <p class="text-xs mt-1 transition-colors" x-show="confirmPass.length > 0"
                                :class="newPass === confirmPass ? 'text-emerald-600' : 'text-red-500'">
                                <span
                                    x-text="newPass === confirmPass ? '✅ Kata sandi cocok!' : '❌ Kata sandi tidak cocok'"></span>
                            </p>
                        </div>

                        {{-- Submit Button — Diubah ke Hijau Emerald Utama (bg-emerald-600) --}}
                        <button type="submit" id="btn-change-password"
                            :disabled="loading || newPass !== confirmPass || newPass.length < 8" class="w-full min-h-[44px] py-3 px-6 rounded-xl font-bold text-sm transition-all shadow-md
                                   bg-emerald-600 text-white hover:bg-emerald-700 active:scale-95
                                   disabled:opacity-50 disabled:cursor-not-allowed disabled:scale-100">
                            <span x-show="!loading">Simpan</span>
                            <span x-show="loading" class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                Menyimpan...
                            </span>
                        </button>
                    </form>

                    {{-- Logout Link --}}
                    <form action="{{ route('logout') }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit"
                            class="w-full text-slate-400 text-xs hover:text-slate-600 transition-colors py-2">
                            Batal & Keluar dari Sistem
                        </button>
                    </form>
                </div>
            </div>

            <p class="text-center text-xs text-slate-300 mt-6">BioCinta Cell</p>
        </div>
    </div>
@endsection