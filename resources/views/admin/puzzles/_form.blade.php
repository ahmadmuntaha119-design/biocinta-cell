{{--
    _form.blade.php — Partial Form CRUD Bank Soal Puzzle
    Props: $puzzle (PuzzleBank instance), $formAction, $isEdit
--}}
@php $isEdit = $puzzle->exists; @endphp

<form action="{{ $formAction }}" method="POST" id="form-puzzle">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT: Tanggal --}}
        <div class="space-y-5">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-serif font-semibold text-base text-emerald-900 flex items-center gap-2 mb-5">
                    <span class="material-symbols-outlined text-emerald-600" style="font-size:20px;">calendar_today</span>
                    Tanggal Tayang
                </h3>
                <div>
                    <label for="publish_date" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Tanggal Publish <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="date"
                        name="publish_date"
                        id="publish_date"
                        value="{{ old('publish_date', $puzzle->publish_date?->format('Y-m-d')) }}"
                        min="{{ now()->format('Y-m-d') }}"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                        required
                    >
                    @error('publish_date') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    <p class="text-xs text-slate-400 mt-2">
                        Soal ini akan otomatis muncul di halaman siswa pada tanggal yang dipilih.
                    </p>
                </div>

                {{-- Quick date shortcuts --}}
                <div class="mt-4 space-y-2">
                    <p class="text-xs font-semibold text-slate-500">Pintasan Tanggal:</p>
                    @foreach([
                        ['label' => 'Hari Ini',        'date' => now()->format('Y-m-d')],
                        ['label' => 'Besok',           'date' => now()->addDay()->format('Y-m-d')],
                        ['label' => 'Lusa',            'date' => now()->addDays(2)->format('Y-m-d')],
                        ['label' => 'Senin Depan',     'date' => now()->next('Monday')->format('Y-m-d')],
                    ] as $shortcut)
                        <button
                            type="button"
                            onclick="document.getElementById('publish_date').value = '{{ $shortcut['date'] }}'"
                            class="w-full text-left px-3 py-2 text-xs rounded-lg bg-slate-50 border border-slate-200 text-slate-600 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-800 transition-all font-medium"
                        >
                            📅 {{ $shortcut['label'] }} <span class="text-slate-400 ml-1">{{ \Carbon\Carbon::parse($shortcut['date'])->format('d M Y') }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- RIGHT: 4 Soal --}}
        <div class="lg:col-span-2 space-y-4">
            @for($i = 1; $i <= 4; $i++)
                @php
                    $colors = [
                        1 => ['bg' => 'bg-indigo-50', 'border' => 'border-indigo-200', 'accent' => 'text-indigo-700', 'ring' => 'focus:ring-indigo-400', 'num' => 'bg-indigo-600'],
                        2 => ['bg' => 'bg-orange-50', 'border' => 'border-orange-200', 'accent' => 'text-orange-700', 'ring' => 'focus:ring-orange-400', 'num' => 'bg-orange-500'],
                        3 => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'accent' => 'text-emerald-700', 'ring' => 'focus:ring-emerald-400', 'num' => 'bg-emerald-600'],
                        4 => ['bg' => 'bg-purple-50', 'border' => 'border-purple-200', 'accent' => 'text-purple-700', 'ring' => 'focus:ring-purple-400', 'num' => 'bg-purple-600'],
                    ];
                    $c = $colors[$i];
                @endphp
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg {{ $c['num'] }} flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            {{ $i }}
                        </div>
                        <h3 class="font-semibold text-sm text-slate-800">Soal Organel ke-{{ $i }}</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Deskripsi Fungsi --}}
                        <div>
                            <label for="target_text_{{ $i }}" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                🎯 Deskripsi Fungsi (Pertanyaan) <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                name="target_text_{{ $i }}"
                                id="target_text_{{ $i }}"
                                rows="3"
                                placeholder="Contoh: Pusat respirasi seluler yang menghasilkan energi (ATP) bagi seluruh aktivitas sel."
                                class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 {{ $c['ring'] }} focus:border-transparent transition-all resize-none"
                                required
                            >{{ old("target_text_$i", $puzzle->{"target_text_$i"}) }}</textarea>
                            @error("target_text_$i") <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Nama Organel Benar --}}
                        <div>
                            <label for="correct_organelle_{{ $i }}" class="block text-xs font-semibold text-slate-600 mb-1.5">
                                ✅ Nama Organel (Jawaban Benar) <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="correct_organelle_{{ $i }}"
                                id="correct_organelle_{{ $i }}"
                                value="{{ old("correct_organelle_$i", $puzzle->{"correct_organelle_$i"}) }}"
                                placeholder="Contoh: Mitokondria"
                                class="w-full border border-slate-300 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 {{ $c['ring'] }} focus:border-transparent transition-all"
                                required
                            >
                            @error("correct_organelle_$i") <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                            {{-- Quick suggestions --}}
                            <div class="flex flex-wrap gap-1 mt-2">
                                @foreach(['Nukleus', 'Mitokondria', 'Kloroplas', 'Ribosom', 'RE Kasar', 'RE Halus', 'Badan Golgi', 'Lisosom', 'Vakuola', 'Membran Sel', 'Dinding Sel', 'Sentriol'] as $org)
                                    <button
                                        type="button"
                                        onclick="document.getElementById('correct_organelle_{{ $i }}').value = '{{ $org }}'"
                                        class="px-2 py-0.5 text-[10px] rounded-md bg-slate-100 text-slate-600 hover:bg-emerald-100 hover:text-emerald-800 transition-colors font-medium border border-slate-200"
                                    >{{ $org }}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endfor

            {{-- Submit --}}
            <div class="flex items-center gap-3 pt-2">
                <button
                    type="submit"
                    class="flex items-center gap-2 bg-emerald-900 text-white px-8 py-3 rounded-xl font-semibold text-sm hover:bg-emerald-800 transition-all shadow-sm min-h-[44px]"
                >
                    <span class="material-symbols-outlined" style="font-size:20px;">save</span>
                    {{ $isEdit ? 'Perbarui Soal Puzzle' : 'Simpan Soal Puzzle' }}
                </button>
                <a href="{{ route('admin.puzzles.index') }}"
                   class="flex items-center gap-2 bg-slate-100 text-slate-700 px-6 py-3 rounded-xl font-semibold text-sm hover:bg-slate-200 transition-all min-h-[44px]">
                    Batal
                </a>
            </div>
        </div>

    </div>
</form>
