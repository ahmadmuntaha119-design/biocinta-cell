@extends('layouts.admin')
@section('title', 'Edit Spesimen — ' . $lab->name)

@section('content')

<div class="max-w-3xl mx-auto"
     x-data="specimenForm({ structures: {{ json_encode($lab->structures ?? [['name'=>'','function'=>'','icon'=>'🔬']]) }} })">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.lab.index') }}"
           class="w-10 h-10 rounded-xl border border-slate-200 flex items-center justify-center
                  hover:bg-slate-50 transition-colors text-slate-500">
            <span class="material-symbols-outlined" style="font-size:20px;">arrow_back</span>
        </a>
        <div class="flex items-center gap-3">
            <span class="text-3xl">{{ $lab->icon }}</span>
            <div>
                <h1 class="font-serif font-bold text-2xl text-slate-900">Edit Spesimen</h1>
                <p class="text-slate-500 text-sm mt-0.5">{{ $lab->name }}</p>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.lab.update', $lab) }}" method="POST" enctype="multipart/form-data" id="form-edit-specimen">
        @csrf
        @method('PUT')

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
                           value="{{ old('name', $lab->name) }}"
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
                           value="{{ old('icon', $lab->icon) }}"
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
                           value="{{ old('magnification', $lab->magnification) }}"
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
                           value="{{ old('sort_order', $lab->sort_order) }}"
                           min="0" max="9999"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                </div>

                {{-- Status Aktif --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                    <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl border border-slate-200 hover:border-emerald-300 transition-colors">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $lab->is_active) ? 'checked' : '' }}
                               class="w-5 h-5 rounded text-emerald-600 focus:ring-emerald-500">
                        <div>
                            <p class="text-sm font-semibold text-slate-800">Aktif</p>
                            <p class="text-xs text-slate-400">Tampil di halaman siswa</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        {{-- ======================================================
             SECTION 2: GAMBAR
             ====================================================== --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
            <h2 class="font-semibold text-slate-800 text-base mb-5 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-emerald-900 text-white text-xs font-bold flex items-center justify-center">2</span>
                Gambar Spesimen
                <span class="text-xs font-normal text-slate-400">(Kosongkan jika tidak ingin mengganti)</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Gambar Makro --}}
                <div x-data="imagePreview('{{ $lab->macro_image_url }}')"
                     class="border-2 border-dashed border-slate-200 rounded-2xl p-4 hover:border-emerald-300 transition-colors">
                    <input type="file" id="macro_image" name="macro_image" accept="image/*"
                           class="hidden" @change="previewImage($event)">
                    <label for="macro_image" class="cursor-pointer block">
                        <img :src="previewUrl" class="w-full rounded-xl object-cover mb-3" style="aspect-ratio:16/9;">
                        <p class="text-center text-xs text-emerald-700 font-semibold">📷 Gambar Makro (klik untuk ganti)</p>
                        <p class="text-center text-xs text-slate-400">JPG/PNG/WebP, maks. 5 MB</p>
                    </label>
                    @error('macro_image') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                {{-- Gambar Mikro --}}
                <div x-data="imagePreview('{{ $lab->micro_image_url }}')"
                     class="border-2 border-dashed border-slate-200 rounded-2xl p-4 hover:border-teal-300 transition-colors">
                    <input type="file" id="micro_image" name="micro_image" accept="image/*"
                           class="hidden" @change="previewImage($event)">
                    <label for="micro_image" class="cursor-pointer block">
                        <img :src="previewUrl" class="w-full rounded-xl object-cover mb-3" style="aspect-ratio:16/9;">
                        <p class="text-center text-xs text-teal-700 font-semibold">🔬 Gambar Mikro (klik untuk ganti)</p>
                        <p class="text-center text-xs text-slate-400">Auto-kompres ke WebP 75%</p>
                    </label>
                    @error('micro_image') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- ======================================================
             SECTION 3: STRUKTUR SEL
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

            <div class="space-y-3">
                <template x-for="(structure, index) in structures" :key="index">
                    <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="flex-shrink-0">
                            <label class="block text-xs text-slate-400 mb-1 font-medium">Ikon</label>
                            <input type="text"
                                   :name="'structures[' + index + '][icon]'"
                                   x-model="structure.icon"
                                   maxlength="5"
                                   class="w-14 px-2 py-2.5 rounded-lg border border-slate-200 text-center text-xl
                                          focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        </div>
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
                        <button type="button" @click="removeStructure(index)"
                                x-show="structures.length > 1"
                                class="flex-shrink-0 mt-6 w-8 h-8 rounded-lg bg-red-50 border border-red-200
                                       flex items-center justify-center text-red-500 hover:bg-red-100 transition-colors">
                            <span class="material-symbols-outlined" style="font-size:16px;">close</span>
                        </button>
                    </div>
                </template>
            </div>
        </div>

        {{-- ======================================================
             SECTION 4: TADABBUR
             ====================================================== --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
            <h2 class="font-semibold text-slate-800 text-base mb-5 flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-amber-500 text-white text-xs font-bold flex items-center justify-center">4</span>
                Tadabbur Ayat Al-Quran
                <span class="text-xs font-normal text-slate-400">(Opsional)</span>
            </h2>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="quran_verse">Teks Ayat</label>
                <textarea id="quran_verse" name="quran_verse" rows="3"
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm
                                 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent resize-none">{{ old('quran_verse', $lab->quran_verse) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="quran_ref">Referensi Surah:Ayat</label>
                <input type="text" id="quran_ref" name="quran_ref"
                       value="{{ old('quran_ref', $lab->quran_ref) }}"
                       placeholder="mis. QS. Al-An'am (6): 99"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm
                              focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="reflection">Teks Renungan</label>
                <textarea id="reflection" name="reflection" rows="4"
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm
                                 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent resize-none">{{ old('reflection', $lab->reflection) }}</textarea>
            </div>
        </div>

        {{-- SUBMIT --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.lab.index') }}"
               class="px-5 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm
                      hover:bg-slate-50 transition-colors min-h-[44px] flex items-center">
                Batal
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('labs.index') }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-3 rounded-xl border border-emerald-200 text-emerald-700
                          font-semibold text-sm hover:bg-emerald-50 transition-colors min-h-[44px]">
                    <span class="material-symbols-outlined" style="font-size:16px;">preview</span>
                    Preview
                </a>
                <button type="submit"
                        id="btn-update-specimen"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-emerald-900 text-white
                               font-bold text-sm hover:bg-emerald-800 transition-colors min-h-[44px] shadow-sm">
                    <span class="material-symbols-outlined" style="font-size:18px;">save</span>
                    Perbarui Spesimen
                </button>
            </div>
        </div>
    </form>

</div>

@push('scripts')
<script>
function specimenForm(initialData) {
    return {
        structures: (initialData.structures && initialData.structures.length > 0)
            ? initialData.structures
            : [{ name: '', function: '', icon: '🔬' }],

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

function imagePreview(initialUrl) {
    return {
        previewUrl: initialUrl || null,
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
