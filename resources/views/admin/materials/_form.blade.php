{{--
    _form.blade.php — Partial Form CRUD Materi
    Dipakai oleh: create.blade.php & edit.blade.php

    Props yang dibutuhkan (dari parent):
      $material  — instance Material (baru atau existing)
      $formAction — URL action form (route admin.materials.store atau .update)
      $formMethod — 'POST' untuk create, 'POST' + @method('PUT') untuk edit
--}}

@php
    $isEdit     = $material->exists;
    $oldType    = old('type', $material->type ?? 'animal');
    $oldHotspots = old('hotspots_json', $material->exists ? $material->hotspots->toJson() : '[]');
@endphp

<form
    action="{{ $formAction }}"
    method="POST"
    enctype="multipart/form-data"
    x-data="materialForm({{ json_encode($material->exists ? $material->hotspots->toArray() : []) }})"
    id="form-material"
>
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- =====================================================
             KOLOM KIRI: Data Teks Materi (2/3 lebar)
             ===================================================== --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Card: Info Dasar --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-serif font-semibold text-base text-emerald-900 flex items-center gap-2 mb-5">
                    <span class="material-symbols-outlined text-emerald-600" style="font-size:20px;">info</span>
                    Informasi Dasar
                </h3>

                {{-- Tipe Materi --}}
                <div class="mb-5">
                    <label for="type" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Tipe Materi <span class="text-red-500">*</span>
                    </label>
                    <select
                        name="type"
                        id="type"
                        x-model="activeType"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all bg-white"
                        required
                    >
                        <option value="animal"     {{ $oldType === 'animal'     ? 'selected' : '' }}>🐾 Sel Hewan</option>
                        <option value="plant"      {{ $oldType === 'plant'      ? 'selected' : '' }}>🌿 Sel Tumbuhan</option>
                        <option value="microscope" {{ $oldType === 'microscope' ? 'selected' : '' }}>🔬 Teori Mikroskop</option>
                    </select>
                    @error('type') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>

                {{-- Judul --}}
                <div class="mb-5">
                    <label for="title" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Judul Materi <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="title"
                        id="title"
                        value="{{ old('title', $material->title) }}"
                        @input="updateSlugPreview($event.target.value)"
                        placeholder="contoh: Nukleus (Sel Hewan)"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                        required
                    >
                    @error('title') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>

                {{-- Slug (auto-preview) --}}
                <div class="mb-5">
                    <label for="slug" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Slug URL
                        <span class="text-slate-400 text-xs font-normal">(kosongkan untuk auto-generate dari judul)</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">/materials/</span>
                        <input
                            type="text"
                            name="slug"
                            id="slug"
                            value="{{ old('slug', $material->slug) }}"
                            x-model="slugPreview"
                            placeholder="auto-generate"
                            class="w-full border border-slate-300 rounded-xl pl-24 pr-4 py-3 text-sm text-slate-600 font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all bg-slate-50"
                        >
                    </div>
                    @error('slug') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>

                {{-- Sort Order --}}
                <div>
                    <label for="sort_order" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Urutan Tampil
                        <span class="text-slate-400 text-xs font-normal">(angka kecil tampil duluan)</span>
                    </label>
                    <input
                        type="number"
                        name="sort_order"
                        id="sort_order"
                        value="{{ old('sort_order', $material->sort_order ?? 0) }}"
                        min="0"
                        class="w-32 border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                    >
                    @error('sort_order') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Card: Fungsi Teknis --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-serif font-semibold text-base text-emerald-900 flex items-center gap-2 mb-5">
                    <div class="w-6 h-6 rounded-md bg-emerald-900 flex items-center justify-center">
                        <span class="text-white text-xs font-bold">01</span>
                    </div>
                    Fungsi Teknis (Deskripsi)
                </h3>
                <textarea
                    name="description"
                    id="description"
                    rows="6"
                    placeholder="Jelaskan fungsi teknis organel/materi ini secara detail dan ilmiah..."
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all resize-y"
                    required
                >{{ old('description', $material->description) }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Card: Video YouTube + Preview --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6" x-data="{
                rawInput: '{{ old('youtube_id', $material->youtube_id ?? '') }}',
                get youtubeId() {
                    const val = this.rawInput.trim();
                    // Sudah berupa ID murni (tanpa http)
                    if (!val.includes('http') && !val.includes('/')) return val;
                    // Ekstrak dari berbagai format URL YouTube
                    try {
                        const url = new URL(val);
                        // youtube.com/watch?v=ID
                        if (url.searchParams.get('v')) return url.searchParams.get('v');
                        // youtube.com/live/ID atau youtu.be/ID
                        const parts = url.pathname.split('/').filter(Boolean);
                        return parts[parts.length - 1].split('?')[0];
                    } catch (e) {
                        return val;
                    }
                }
            }">
                <h3 class="font-serif font-semibold text-base text-red-700 flex items-center gap-2 mb-5">
                    <div class="w-6 h-6 rounded-md bg-red-600 flex items-center justify-center">
                        <span class="text-white text-xs font-bold">02</span>
                    </div>
                    Video Visualisasi (YouTube)
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="youtube_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Link atau ID Video YouTube
                            <span class="text-slate-400 text-xs font-normal">(opsional)</span>
                        </label>
                        <input
                            type="text"
                            name="youtube_id"
                            id="youtube_id"
                            x-model="rawInput"
                            placeholder="Tempel link YouTube di sini..."
                            class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent transition-all"
                        >
                        {{-- Field tersembunyi untuk kirim ID bersih ke server --}}
                        <input type="hidden" name="youtube_id" :value="youtubeId">
                        <p class="text-xs text-slate-400 mt-1.5">
                            Bisa tempel link penuh, contoh:<br>
                            <span class="font-mono text-slate-500">https://www.youtube.com/watch?v=XXXXXXXXXXX</span><br>
                            <span class="font-mono text-slate-500">https://www.youtube.com/live/XXXXXXXXXXX</span><br>
                            <span class="font-mono text-slate-500">https://youtu.be/XXXXXXXXXXX</span>
                        </p>
                        <p class="text-xs mt-1.5" x-show="youtubeId">
                            ID terdeteksi: <strong class="text-red-600 font-mono" x-text="youtubeId"></strong>
                        </p>
                        @error('youtube_id') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    {{-- Live Preview --}}
                    <div>
                        <p class="text-sm font-semibold text-slate-700 mb-1.5">Preview Video</p>
                        <div class="rounded-xl overflow-hidden aspect-video bg-slate-100 border border-slate-200">
                            <template x-if="youtubeId && youtubeId.length >= 11">
                                <iframe
                                    :src="'https://www.youtube.com/embed/' + youtubeId"
                                    class="w-full h-full"
                                    frameborder="0"
                                    allowfullscreen
                                ></iframe>
                            </template>
                            <template x-if="!youtubeId || youtubeId.length < 11">
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                                    <span class="material-symbols-outlined" style="font-size:32px;">play_circle</span>
                                    <p class="text-xs mt-2">Masukkan YouTube ID untuk preview</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card: Sejarah Penemu --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-serif font-semibold text-base text-amber-700 flex items-center gap-2 mb-5">
                    <div class="w-6 h-6 rounded-md bg-amber-500 flex items-center justify-center">
                        <span class="text-white text-xs font-bold">03</span>
                    </div>
                    Sejarah Penemu
                </h3>
                <textarea
                    name="history_founder"
                    id="history_founder"
                    rows="4"
                    placeholder="Ceritakan sejarah singkat ilmuwan penemu organel/alat ini..."
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition-all resize-y"
                >{{ old('history_founder', $material->history_founder) }}</textarea>
                @error('history_founder') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Card: Tadabbur Quran --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-serif font-semibold text-base text-teal-800 flex items-center gap-2 mb-5">
                    <div class="w-6 h-6 rounded-md bg-teal-700 flex items-center justify-center">
                        <span class="text-white text-xs font-bold">04</span>
                    </div>
                    Tadabbur — Integrasi Nilai Islami
                </h3>
                <textarea
                    name="quran_verse"
                    id="quran_verse"
                    rows="4"
                    placeholder="Tuliskan ayat Al-Quran beserta terjemahan dan refleksi spiritual yang terkait..."
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent transition-all resize-y"
                >{{ old('quran_verse', $material->quran_verse) }}</textarea>
                @error('quran_verse') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

        </div>{{-- End kolom kiri --}}

        {{-- =====================================================
             KOLOM KANAN: Gambar + Visual Hotspot Picker (1/3)
             ===================================================== --}}
        <div class="space-y-5">

            {{-- Card: Upload Gambar --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-serif font-semibold text-base text-slate-700 flex items-center gap-2 mb-5">
                    <span class="material-symbols-outlined text-slate-500" style="font-size:20px;">image</span>
                    Gambar Organel
                    <span class="text-slate-400 text-xs font-normal">(opsional)</span>
                </h3>

                @if($isEdit && $material->image_path)
                    <div class="mb-4 rounded-xl overflow-hidden border border-slate-200 bg-slate-50">
                        <img src="{{ $material->image_url }}" alt="{{ $material->title }}" class="w-full h-32 object-cover">
                        <p class="text-xs text-slate-400 text-center py-2">Gambar saat ini</p>
                    </div>
                @endif

                <label
                    for="image"
                    class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-slate-300 rounded-xl cursor-pointer hover:border-emerald-400 hover:bg-emerald-50 transition-all"
                    x-data="{ fileName: '' }"
                    @dragover.prevent
                    @drop.prevent="fileName = $event.dataTransfer.files[0]?.name || ''"
                >
                    <input
                        type="file"
                        name="image"
                        id="image"
                        class="hidden"
                        accept="image/*"
                        @change="fileName = $event.target.files[0]?.name || ''"
                    >
                    <span class="material-symbols-outlined text-slate-400" style="font-size:32px;">upload_file</span>
                    <p class="text-xs text-slate-500 mt-1" x-text="fileName || 'Klik atau drag gambar ke sini'"></p>
                    <p class="text-xs text-slate-400 mt-0.5">JPG, PNG, WebP — max 5 MB</p>
                    <p class="text-xs text-emerald-600 mt-0.5 font-medium">Otomatis dikompresi → WebP 75%</p>
                </label>
                @error('image') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Card: Visual Hotspot Picker --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sticky top-[80px]">
                <h3 class="font-serif font-semibold text-base text-slate-700 flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-amber-500" style="font-size:20px;">pin_drop</span>
                    Koordinat Hotspot Klik
                </h3>
                <p class="text-xs text-slate-500 mb-4">
                    Klik pada diagram sel di bawah untuk menambahkan titik hotspot. Siswa akan bisa mengklik titik ini untuk melihat detail materi.
                </p>

                {{-- Hidden input — dikirim ke server sebagai JSON --}}
                <input type="hidden" name="hotspots_json" id="hotspots_json" :value="JSON.stringify(hotspots)">

                {{-- TYPE NOTICE --}}
                <div x-show="activeType === 'microscope'" class="mb-4 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 text-xs text-blue-700">
                    <span class="font-semibold">ℹ️ Info:</span> Materi tipe "Mikroskop" tidak menggunakan diagram sel interaktif — hotspot tidak diperlukan untuk tipe ini.
                </div>

                {{-- Diagram Container --}}
                <div
                    x-show="activeType !== 'microscope'"
                    class="relative w-full rounded-xl overflow-hidden border-2 border-dashed border-emerald-300 cursor-crosshair bg-slate-50 select-none"
                    style="aspect-ratio: 4/3;"
                    @click="addHotspot($event)"
                    title="Klik di sini untuk menambahkan hotspot"
                >
                    {{-- SVG Sel Hewan --}}
                    <div x-show="activeType === 'animal'" class="absolute inset-0">
                        <svg class="absolute inset-0 w-full h-full pointer-events-none" viewBox="0 0 640 480" preserveAspectRatio="xMidYMid meet">
                            <ellipse cx="320" cy="242" rx="295" ry="220" fill="#e8f5e9" stroke="#66bb6a" stroke-width="3.5"/>
                            <ellipse cx="315" cy="192" rx="72" ry="62" fill="#e8eaf6" stroke="#7986cb" stroke-width="2.5"/>
                            <ellipse cx="310" cy="187" rx="24" ry="20" fill="#9fa8da"/>
                            <ellipse cx="145" cy="157" rx="44" ry="23" transform="rotate(-25 145 157)" fill="#ff8a65" stroke="#e64a19" stroke-width="2"/>
                            <ellipse cx="492" cy="312" rx="40" ry="20" transform="rotate(20 492 312)" fill="#ff8a65" stroke="#e64a19" stroke-width="2"/>
                            <path d="M392 180 C415 158, 435 182, 425 202 C448 180, 466 205, 455 225" stroke="#ab47bc" stroke-width="3" fill="none"/>
                            <path d="M193 295 Q256 273, 256 303 Q193 325, 193 295 Z" fill="#fff176" stroke="#f9a825" stroke-width="2"/>
                            <circle cx="416" cy="335" r="27" fill="#ef9a9a" stroke="#e53935" stroke-width="2"/>
                            <circle cx="172" cy="318" r="20" fill="#b3e5fc" stroke="#0288d1" stroke-width="2"/>
                        </svg>
                        {{-- Hotspot pins --}}
                        <template x-for="(hs, idx) in hotspots" :key="idx">
                            <div
                                class="absolute z-10 group"
                                :style="`left:${hs.left_percent};top:${hs.top_percent};transform:translate(-50%,-50%)`"
                                @click.stop
                            >
                                <div
                                    class="w-8 h-8 rounded-full border-2 border-white shadow-lg flex items-center justify-center text-white text-xs font-bold cursor-pointer transition-transform hover:scale-110"
                                    :style="`background-color:${hs.bg_color}`"
                                    :title="`Hotspot ${idx+1} — klik untuk hapus`"
                                    @click="removeHotspot(idx)"
                                >
                                    <span x-text="idx+1"></span>
                                </div>
                            </div>
                        </template>
                        <div x-show="hotspots.length === 0" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <p class="text-slate-400 text-xs text-center px-4">Klik pada diagram untuk menambah hotspot</p>
                        </div>
                    </div>

                    {{-- SVG Sel Tumbuhan --}}
                    <div x-show="activeType === 'plant'" style="display:none;" class="absolute inset-0">
                        <svg class="absolute inset-0 w-full h-full pointer-events-none" viewBox="0 0 640 480" preserveAspectRatio="xMidYMid meet">
                            <rect x="8" y="8" width="624" height="464" rx="10" fill="#d7ccc8" stroke="#795548" stroke-width="14"/>
                            <rect x="22" y="22" width="596" height="436" rx="6" fill="#e8f5e9" stroke="#66bb6a" stroke-width="2.5"/>
                            <ellipse cx="295" cy="268" rx="185" ry="155" fill="#e3f2fd" stroke="#1976d2" stroke-width="2.5"/>
                            <text x="295" y="272" text-anchor="middle" font-size="11" fill="#1565c0" font-weight="600">Vakuola Sentral</text>
                            <ellipse cx="490" cy="127" rx="67" ry="57" fill="#e8eaf6" stroke="#7986cb" stroke-width="2.5"/>
                            <ellipse cx="100" cy="147" rx="46" ry="24" fill="#81c784" stroke="#388e3c" stroke-width="2"/>
                            <ellipse cx="165" cy="342" rx="44" ry="22" fill="#81c784" stroke="#388e3c" stroke-width="2"/>
                            <ellipse cx="528" cy="282" rx="34" ry="17" transform="rotate(15 528 282)" fill="#ff8a65" stroke="#e64a19" stroke-width="2"/>
                        </svg>
                        {{-- Hotspot pins --}}
                        <template x-for="(hs, idx) in hotspots" :key="idx">
                            <div
                                class="absolute z-10 group"
                                :style="`left:${hs.left_percent};top:${hs.top_percent};transform:translate(-50%,-50%)`"
                                @click.stop
                            >
                                <div
                                    class="w-8 h-8 rounded-full border-2 border-white shadow-lg flex items-center justify-center text-white text-xs font-bold cursor-pointer transition-transform hover:scale-110"
                                    :style="`background-color:${hs.bg_color}`"
                                    @click="removeHotspot(idx)"
                                    :title="`Hotspot ${idx+1} — klik untuk hapus`"
                                >
                                    <span x-text="idx+1"></span>
                                </div>
                            </div>
                        </template>
                        <div x-show="hotspots.length === 0" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <p class="text-slate-400 text-xs text-center px-4">Klik pada diagram untuk menambah hotspot</p>
                        </div>
                    </div>
                </div>{{-- End diagram container --}}

                {{-- Hotspot Color + List --}}
                <div x-show="activeType !== 'microscope'" class="mt-4 space-y-3">
                    {{-- Color picker untuk hotspot berikutnya --}}
                    <div class="flex items-center gap-3">
                        <label class="text-xs font-semibold text-slate-600">Warna hotspot berikutnya:</label>
                        <div class="flex gap-2 flex-wrap">
                            @php
                                $colors = ['#4f46e5','#ea580c','#16a34a','#dc2626','#b45309','#0284c7','#7c3aed','#0d9488','#92400e','#15803d'];
                            @endphp
                            @foreach($colors as $color)
                                <button
                                    type="button"
                                    @click="nextColor = '{{ $color }}'"
                                    :class="nextColor === '{{ $color }}' ? 'ring-2 ring-offset-1 ring-slate-400 scale-110' : ''"
                                    class="w-5 h-5 rounded-full transition-transform cursor-pointer border border-white shadow-sm"
                                    style="background-color: {{ $color }}"
                                    title="{{ $color }}"
                                ></button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Daftar hotspot yang sudah ditambahkan --}}
                    <div class="space-y-2 max-h-48 overflow-y-auto" x-show="hotspots.length > 0">
                        <p class="text-xs font-semibold text-slate-600">Hotspot tersimpan (<span x-text="hotspots.length"></span>):</p>
                        <template x-for="(hs, idx) in hotspots" :key="idx">
                            <div class="flex items-center gap-2 bg-slate-50 rounded-lg px-3 py-2">
                                <div class="w-5 h-5 rounded-full border-2 border-white shadow-sm flex-shrink-0" :style="`background-color:${hs.bg_color}`"></div>
                                <span class="text-xs text-slate-500 font-mono flex-1">
                                    L: <span x-text="hs.left_percent"></span> | T: <span x-text="hs.top_percent"></span>
                                </span>
                                <button
                                    type="button"
                                    @click="removeHotspot(idx)"
                                    class="text-red-400 hover:text-red-600 transition-colors"
                                    title="Hapus hotspot ini"
                                >
                                    <span class="material-symbols-outlined" style="font-size:16px;">close</span>
                                </button>
                            </div>
                        </template>
                    </div>

                    {{-- Reset all hotspot --}}
                    <button
                        type="button"
                        @click="if(confirm('Reset semua hotspot untuk materi ini?')) hotspots = []"
                        x-show="hotspots.length > 0"
                        class="text-xs text-red-500 hover:text-red-700 flex items-center gap-1 transition-colors"
                    >
                        <span class="material-symbols-outlined" style="font-size:14px;">restart_alt</span>
                        Reset semua hotspot
                    </button>
                </div>{{-- End hotspot list --}}
            </div>{{-- End picker card --}}

        </div>{{-- End kolom kanan --}}
    </div>{{-- End grid --}}

    {{-- =====================================================
         SUBMIT BUTTONS
         ===================================================== --}}
    <div class="flex items-center gap-3 mt-6 pt-6 border-t border-slate-200">
        <button
            type="submit"
            id="btn-simpan-materi"
            class="flex items-center gap-2 bg-emerald-900 text-white px-8 py-3 rounded-xl font-semibold text-sm hover:bg-emerald-800 transition-all shadow-sm min-h-[44px]"
        >
            <span class="material-symbols-outlined" style="font-size:20px;">save</span>
            {{ $isEdit ? 'Perbarui Materi' : 'Simpan Materi Baru' }}
        </button>
        <a
            href="{{ route('admin.materials.index') }}"
            class="flex items-center gap-2 bg-slate-100 text-slate-700 px-6 py-3 rounded-xl font-semibold text-sm hover:bg-slate-200 transition-all min-h-[44px]"
        >
            Batal
        </a>
        @if($isEdit)
            <span class="text-xs text-slate-400 ml-2">
                Terakhir diperbarui: {{ $material->updated_at->diffForHumans() }}
            </span>
        @endif
    </div>
</form>

@push('scripts')
<script>
function materialForm(existingHotspots) {
    return {
        activeType:  document.getElementById('type')?.value || 'animal',
        slugPreview: document.getElementById('slug')?.value || '',
        hotspots:    existingHotspots || [],
        nextColor:   '#4f46e5',

        updateSlugPreview(title) {
            // Auto-generate slug preview (slugify simpel)
            if (!document.getElementById('slug').value || document.getElementById('slug').value === this.slugPreview) {
                const slug = title
                    .toLowerCase()
                    .replace(/[^a-z0-9\s\-]/g, '')
                    .trim()
                    .replace(/\s+/g, '-');
                this.slugPreview = slug;
            }
        },

        /**
         * Tambah hotspot baru berdasarkan klik pada diagram.
         * Hitung koordinat dalam persen relatif terhadap container.
         */
        addHotspot(event) {
            if (this.activeType === 'microscope') return;

            const container = event.currentTarget;
            const rect      = container.getBoundingClientRect();
            const x         = event.clientX - rect.left;
            const y         = event.clientY - rect.top;

            const leftPct = ((x / rect.width)  * 100).toFixed(1) + '%';
            const topPct  = ((y / rect.height) * 100).toFixed(1) + '%';

            // Auto-increment delay (0.2s per hotspot)
            const delay = (this.hotspots.length * 0.2).toFixed(1) + 's';

            this.hotspots.push({
                left_percent: leftPct,
                top_percent:  topPct,
                bg_color:     this.nextColor,
                delay:        delay,
            });
        },

        removeHotspot(index) {
            this.hotspots.splice(index, 1);
        },
    };
}
</script>
@endpush
