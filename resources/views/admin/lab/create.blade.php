@extends('layouts.admin')
@section('title', 'Tambah Spesimen — Lab Virtual')

@section('content')

<div class="max-w-3xl mx-auto"
     x-data="specimenForm({ structures: [{ name: '', function: '', icon: '🔬' }] })">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.lab.index') }}"
           class="w-10 h-10 rounded-xl border border-slate-200 flex items-center justify-center
                  hover:bg-slate-50 transition-colors text-slate-500">
            <span class="material-symbols-outlined" style="font-size:20px;">arrow_back</span>
        </a>
        <div>
            <h1 class="font-serif font-bold text-2xl text-slate-900">Tambah Spesimen Baru</h1>
            <p class="text-slate-500 text-sm mt-0.5">Isi detail spesimen yang akan ditampilkan di Lab Virtual siswa</p>
        </div>
    </div>

    <form action="{{ route('admin.lab.store') }}" method="POST" enctype="multipart/form-data" id="form-create-specimen">
        @csrf

        {{-- ======================================================
             SECTION 1: INFO DASAR
             ====================================================== --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
            <h2 class="font-semibold text-slate-800 text-base mb-5 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-emerald-900 text-white text-xs font-bold flex items-center justify-center">1</span>
                Informasi Dasar
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Nama --}}
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="name">
                        Nama Spesimen <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name') }}"
                           placeholder="mis. Sel Epitel Pipi"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                  @error('name') border-red-400 bg-red-50 @enderror"
                           required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Icon --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="icon">
                        Emoji Ikon <span class="text-slate-400 font-normal text-xs">(opsional)</span>
                    </label>
                    <input type="text" id="icon" name="icon"
                           value="{{ old('icon') }}"
                           placeholder="(opsional)"
                           maxlength="5"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-2xl
                                  focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                  @error('icon') border-red-400 bg-red-50 @enderror">
                    <p class="text-xs text-slate-500 mt-1.5">
                        Tekan <kbd class="px-1.5 py-0.5 rounded bg-slate-100 border border-slate-300 font-mono text-[10px]">Win</kbd> + <kbd class="px-1.5 py-0.5 rounded bg-slate-100 border border-slate-300 font-mono text-[10px]">.</kbd> (titik) di Windows untuk membuka menu Emoji.
                    </p>
                    @error('icon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Perbesaran --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="magnification">
                        Label Perbesaran <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="magnification" name="magnification"
                           value="{{ old('magnification', '400×') }}"
                           placeholder="mis. 400×"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    @error('magnification') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Sort Order --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="sort_order">
                        Urutan Tampil
                    </label>
                    <input type="number" id="sort_order" name="sort_order"
                           value="{{ old('sort_order', 0) }}"
                           min="0" max="9999"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <p class="text-xs text-slate-400 mt-1">Angka lebih kecil tampil lebih dulu</p>
                </div>

                {{-- Status Aktif --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                    <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl border border-slate-200 hover:border-emerald-300 transition-colors">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', '1') ? 'checked' : '' }}
                               class="w-5 h-5 rounded text-emerald-600 focus:ring-emerald-500">
                        <div>
                            <p class="text-sm font-semibold text-slate-800">Aktif</p>
                            <p class="text-xs text-slate-400">Spesimen langsung tampil ke siswa</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        {{-- ======================================================
             SECTION 2: GAMBAR MAKRO & MIKRO
             ====================================================== --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
            <h2 class="font-semibold text-slate-800 text-base mb-5 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-emerald-900 text-white text-xs font-bold flex items-center justify-center">2</span>
                Gambar Spesimen
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Upload Makro --}}
                <div x-data="imagePreview()"
                     class="border-2 border-dashed border-slate-200 rounded-2xl p-4 hover:border-emerald-300 transition-colors">
                    <input type="file" id="macro_image" name="macro_image" accept="image/*"
                           class="hidden" @change="previewImage($event)">
                    <label for="macro_image" class="cursor-pointer block">
                        <template x-if="previewUrl">
                            <img :src="previewUrl" class="w-full rounded-xl object-cover mb-3" style="aspect-ratio:16/9;">
                        </template>
                        <template x-if="!previewUrl">
                            <div class="flex flex-col items-center justify-center py-8 text-center">
                                <span class="material-symbols-outlined text-slate-300 mb-2" style="font-size:40px;">add_photo_alternate</span>
                                <p class="text-sm font-semibold text-slate-500">Upload Gambar Makro</p>
                                <p class="text-xs text-slate-400 mt-1">Tampilan luar objek (mis. daun utuh)</p>
                            </div>
                        </template>
                        <p class="text-center text-xs text-emerald-700 font-semibold mt-2">
                            📷 Gambar Makro (1×)
                        </p>
                        <p class="text-center text-xs text-slate-400">JPG/PNG/WebP, maks. 5 MB</p>
                    </label>
                    @error('macro_image') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                {{-- Upload Mikro --}}
                <div x-data="imagePreview()"
                     class="border-2 border-dashed border-slate-200 rounded-2xl p-4 hover:border-teal-300 transition-colors">
                    <input type="file" id="micro_image" name="micro_image" accept="image/*"
                           class="hidden" @change="previewImage($event)">
                    <label for="micro_image" class="cursor-pointer block">
                        <template x-if="previewUrl">
                            <img :src="previewUrl" class="w-full rounded-xl object-cover mb-3" style="aspect-ratio:16/9;">
                        </template>
                        <template x-if="!previewUrl">
                            <div class="flex flex-col items-center justify-center py-8 text-center">
                                <span class="material-symbols-outlined text-slate-300 mb-2" style="font-size:40px;">biotech</span>
                                <p class="text-sm font-semibold text-slate-500">Upload Gambar Mikro</p>
                                <p class="text-xs text-slate-400 mt-1">Tampilan sel (hasil perbesaran mikroskop)</p>
                            </div>
                        </template>
                        <p class="text-center text-xs text-teal-700 font-semibold mt-2">
                            🔬 Gambar Mikro ({{ old('magnification', '400×') }})
                        </p>
                        <p class="text-center text-xs text-slate-400">JPG/PNG/WebP, maks. 5 MB • Auto-kompres WebP</p>
                    </label>
                    @error('micro_image') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- ======================================================
             SECTION 3: STRUKTUR SEL (DYNAMIC ROWS)
             ====================================================== --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-semibold text-slate-800 text-base flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-emerald-900 text-white text-xs font-bold flex items-center justify-center">3</span>
                    Struktur Sel Terlihat
                </h2>
                <button type="button" @click="addStructure()"
                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold
                               text-emerald-700 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100
                               transition-colors min-h-[36px]">
                    <span class="material-symbols-outlined" style="font-size:14px;">add</span>
                    Tambah Baris
                </button>
            </div>

            <p class="text-xs text-slate-400 mb-4">
                Daftar organel/struktur yang dapat diamati saat siswa mengaktifkan tampilan mikro.
            </p>

            <div class="space-y-3">
                <template x-for="(structure, index) in structures" :key="index">
                    <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-xl border border-slate-100">
                        {{-- Icon --}}
                        <div class="flex-shrink-0">
                            <label class="block text-xs text-slate-400 mb-1 font-medium">Ikon</label>
                            <input type="text"
                                   :name="'structures[' + index + '][icon]'"
                                   x-model="structure.icon"
                                   maxlength="5"
                                   class="w-14 px-2 py-2.5 rounded-lg border border-slate-200 text-center text-xl
                                          focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
                        {{-- Name + Function --}}
                        <div class="flex-1 space-y-2">
                            <div>
                                <label class="block text-xs text-slate-400 mb-1 font-medium">Nama Struktur</label>
                                <input type="text"
                                       :name="'structures[' + index + '][name]'"
                                       x-model="structure.name"
                                       placeholder="mis. Kloroplas"
                                       class="w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm text-slate-800
                                              focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-400 mb-1 font-medium">Fungsi / Deskripsi</label>
                                <input type="text"
                                       :name="'structures[' + index + '][function]'"
                                       x-model="structure.function"
                                       placeholder="mis. Tempat berlangsungnya fotosintesis..."
                                       class="w-full px-3 py-2.5 rounded-lg border border-slate-200 text-sm text-slate-800
                                              focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>
                        {{-- Remove button --}}
                        <button type="button" @click="removeStructure(index)"
                                x-show="structures.length > 1"
                                class="flex-shrink-0 mt-6 w-8 h-8 rounded-lg bg-red-50 border border-red-200
                                       flex items-center justify-center text-red-500 hover:bg-red-100
                                       transition-colors">
                            <span class="material-symbols-outlined" style="font-size:16px;">close</span>
                        </button>
                    </div>
                </template>
            </div>
        </div>

        {{-- ======================================================
             SECTION 4: TADABBUR AYAT
             ====================================================== --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
            <h2 class="font-semibold text-slate-800 text-base mb-5 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-amber-500 text-white text-xs font-bold flex items-center justify-center">4</span>
                Tadabbur Ayat Al-Quran
                <span class="text-xs font-normal text-slate-400">(Opsional)</span>
            </h2>

            {{-- Ayat --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="quran_verse">
                    Teks Ayat (Terjemahan)
                </label>
                <textarea id="quran_verse" name="quran_verse" rows="3"
                          placeholder='mis. "Dan Dia-lah yang menurunkan air hujan dari langit..."'
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm
                                 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent resize-none">{{ old('quran_verse') }}</textarea>
            </div>

            {{-- Referensi --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="quran_ref">
                    Referensi Surah:Ayat
                </label>
                <input type="text" id="quran_ref" name="quran_ref"
                       value="{{ old('quran_ref') }}"
                       placeholder="mis. QS. Al-An'am (6): 99"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm
                              focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent">
            </div>

            {{-- Refleksi --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="reflection">
                    Teks Renungan/Refleksi
                </label>
                <textarea id="reflection" name="reflection" rows="4"
                          placeholder="Tulis renungan/tadabbur yang mengaitkan spesimen ini dengan kebesaran Allah..."
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm
                                 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent resize-none">{{ old('reflection') }}</textarea>
            </div>
        </div>

        {{-- ======================================================
             SUBMIT
             ====================================================== --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.lab.index') }}"
               class="px-5 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm
                      hover:bg-slate-50 transition-colors min-h-[44px] flex items-center">
                Batal
            </a>
            <button type="submit"
                    id="btn-submit-specimen"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-emerald-900 text-white
                           font-bold text-sm hover:bg-emerald-800 transition-colors min-h-[44px] shadow-sm">
                <span class="material-symbols-outlined" style="font-size:18px;">save</span>
                Simpan Spesimen
            </button>
        </div>
    </form>

</div>

@push('scripts')
<script>
function specimenForm(initialData) {
    return {
        structures: initialData.structures || [{ name: '', function: '', icon: '🔬' }],

        addStructure() {
            this.structures.push({ name: '', function: '', icon: '🔬' });
        },

        removeStructure(index) {
            if (this.structures.length > 1) {
                this.structures.splice(index, 1);
            }
        }
    };
}

function imagePreview() {
    return {
        previewUrl: null,
        previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => { this.previewUrl = e.target.result; };
                reader.readAsDataURL(file);
            }
        }
    };
}
</script>
@endpush

@endsection
