{{-- _game.blade.php — The actual Click-to-Match puzzle grid --}}
<div class="grid grid-cols-12 gap-5 md:gap-6">

    {{-- ═══════════════════════════════════════════════════
         LEFT COLUMN: Target Fungsi Organel (7/12)
         ═══════════════════════════════════════════════════ --}}
    <div class="col-span-12 md:col-span-7 space-y-4">

        {{-- Score Header --}}
        <div class="flex justify-between items-center bg-white px-5 py-4 rounded-2xl shadow-sm border border-slate-100/80">
            <span class="font-bold text-slate-800 text-sm md:text-base flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-500" style="font-size:20px;">target</span>
                Fungsi Organel
            </span>
            <div class="flex items-center gap-2">
                {{-- Show progress before submission --}}
                <template x-if="!submitted">
                    <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 font-semibold px-3 py-1.5 rounded-xl text-xs md:text-sm shadow-sm">
                        Progres: <span x-text="targets.filter(t => t.matchedWith !== null).length" class="text-emerald-950 font-bold">0</span>/4 Terisi
                    </div>
                </template>
                {{-- Show score after submission --}}
                <template x-if="submitted">
                    <div class="bg-amber-50 border border-amber-200 text-amber-800 font-bold px-3 py-1.5 rounded-xl text-xs md:text-sm flex items-center gap-1 shadow-sm">
                        <span>Skor:</span>
                        <span x-text="calculateCorrectCount()" class="text-amber-900">0</span>/4
                        <span class="mx-1 text-amber-300">|</span>
                        <span x-text="calculateScore()" class="text-amber-900">0</span> PTS
                    </div>
                </template>
            </div>
        </div>

        {{-- Target Boxes --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 gap-4">
            <template x-for="(target, index) in targets" :key="target.id">
                <div
                    @click="selectTarget(index)"
                    :class="{
                        'border-dashed border-slate-200 bg-white hover:border-emerald-300 hover:shadow-md': !target.matchedWith && selectedTargetIndex !== index,
                        'ring-2 ring-amber-400 border-amber-300 bg-amber-50/50 shadow-md': selectedTargetIndex === index,
                        'border-solid border-emerald-500/30 bg-emerald-50/10 shadow-sm': target.matchedWith && !submitted,
                        'border-solid border-emerald-300 bg-emerald-50/20 shadow-sm': target.matchedWith && submitted && target.matchedWith.name === target.correctName,
                        'border-solid border-rose-200 bg-rose-50/20 shadow-sm': target.matchedWith && submitted && target.matchedWith.name !== target.correctName,
                    }"
                    class="p-5 bg-white rounded-2xl border-2 flex flex-col justify-between gap-3.5 transition-all duration-300 select-none hover:-translate-y-0.5 cursor-pointer"
                >
                    {{-- Description text on top --}}
                    <div class="flex items-start gap-2.5">
                        <div class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-800 flex-shrink-0 flex items-center justify-center text-xs font-bold font-serif border border-emerald-100" x-text="index + 1"></div>
                        <p x-text="target.text" class="text-slate-700 text-xs md:text-sm font-medium leading-relaxed"></p>
                    </div>

                    {{-- Answer slot underneath --}}
                    <div
                        :class="{
                            'border-dashed border-slate-200 text-slate-400 bg-slate-50/50': !target.matchedWith && selectedTargetIndex !== index,
                            'border-dashed border-amber-400 text-amber-700 bg-amber-50/70 font-medium': selectedTargetIndex === index && !target.matchedWith,
                            'border-solid border-emerald-600 text-emerald-900 bg-emerald-50 font-semibold shadow-sm': target.matchedWith && !submitted,
                            'border-solid border-emerald-500 text-emerald-950 bg-emerald-100/80 font-bold shadow-sm': target.matchedWith && submitted && target.matchedWith.name === target.correctName,
                            'border-solid border-rose-400 text-rose-950 bg-rose-100/80 font-bold shadow-sm': target.matchedWith && submitted && target.matchedWith.name !== target.correctName,
                        }"
                        class="w-full p-2.5 rounded-xl text-center border-2 min-h-[44px] flex items-center justify-center text-xs transition-all duration-350"
                    >
                        <template x-if="target.matchedWith">
                            <div class="flex items-center gap-1.5">
                                <template x-if="submitted">
                                    <span class="flex items-center">
                                        <span x-show="target.matchedWith.name === target.correctName" class="text-emerald-600 flex items-center">
                                            <span class="material-symbols-outlined" style="font-size:16px; font-weight:bold;">check_circle</span>
                                        </span>
                                        <span x-show="target.matchedWith.name !== target.correctName" class="text-rose-600 flex items-center">
                                            <span class="material-symbols-outlined" style="font-size:16px; font-weight:bold;">cancel</span>
                                        </span>
                                    </span>
                                </template>
                                <span x-text="target.matchedWith.name"></span>
                            </div>
                        </template>
                        <template x-if="!target.matchedWith">
                            <span x-text="selectedTargetIndex === index ? 'Pilih organel' : 'Taruh jawaban di sini'" class="opacity-80"></span>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         RIGHT COLUMN: Pilihan Organel (5/12)
         ═══════════════════════════════════════════════════ --}}
    <div class="col-span-12 md:col-span-5">
        <div class="bg-white p-5 md:p-6 rounded-2xl shadow-sm border border-slate-100/80 sticky top-[96px] transition-all hover:shadow-md">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 font-serif flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-600" style="font-size:18px;">apps</span>
                Pilihan Nama Organel
            </h3>

            {{-- Choice Buttons --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 gap-3 mb-5">
                <template x-for="organelle in organelles" :key="organelle.id">
                    <button
                        @click="matchOrganelle(organelle)"
                        :disabled="organelle.isUsed || submitted"
                        :class="{
                            'bg-emerald-50/80 border-emerald-250 text-emerald-800 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 hover:shadow-md': !organelle.isUsed && !submitted,
                            'bg-slate-50 text-slate-350 border-slate-200 cursor-not-allowed shadow-none': organelle.isUsed,
                            'ring-2 ring-amber-400 border-amber-300': selectedTargetIndex !== null && !organelle.isUsed,
                        }"
                        class="w-full min-h-[48px] p-3 rounded-xl font-semibold text-xs md:text-sm transition-all duration-300 text-left px-4 flex justify-between items-center border hover:-translate-y-0.5"
                    >
                        <span x-text="organelle.name"></span>
                        <span x-show="!organelle.isUsed && selectedTargetIndex !== null" class="text-amber-500 font-bold animate-pulse text-sm">←</span>
                        <span x-show="organelle.isUsed" class="material-symbols-outlined text-slate-350" style="font-size:18px;">check_circle</span>
                    </button>
                </template>
            </div>

            {{-- Result message --}}
            <div x-show="resultMessage" x-transition
                 class="bg-emerald-50 border border-emerald-250 rounded-xl px-4 py-3 text-sm text-emerald-900 mb-4 shadow-sm"
                 x-text="resultMessage" style="display:none;">
            </div>

            {{-- Submit & Reset Actions --}}
            <div class="space-y-3 pt-4 border-t border-slate-100">
                {{-- Submit button --}}
                <button
                    @click="submitPuzzleScore()"
                    x-show="isGameFinished() && !submitted"
                    :disabled="submitting"
                    :class="submitting ? 'opacity-60 cursor-wait' : ''"
                    class="w-full bg-amber-500 text-white min-h-[48px] p-3 rounded-xl font-bold shadow-md hover:bg-amber-600 hover:-translate-y-0.5 transition-all duration-300 text-sm flex items-center justify-center gap-2"
                    style="display:none;"
                >
                    <span class="material-symbols-outlined text-white" style="font-size:18px;" x-show="!submitting">send</span>
                    <svg x-show="submitting" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    <span x-text="submitting ? 'Mengirim...' : (practiceMode ? 'Cek Hasil Latihan' : 'Kirim Skor Anda')"></span>
                </button>

                {{-- Reset button --}}
                <button
                    @click="resetPuzzle()"
                    x-show="!submitted && targets.some(t => t.matchedWith !== null)"
                    class="w-full bg-teal-50 border border-teal-200 text-teal-800 min-h-[44px] p-3 rounded-xl font-semibold text-sm hover:bg-teal-100 hover:text-teal-900 hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2 shadow-sm"
                >
                    <span class="material-symbols-outlined" style="font-size:16px;">replay</span>
                    <span>Reset Pilihan</span>
                </button>

                {{-- After submit: back to home --}}
                <a x-show="submitted"
                   href="{{ route('home') }}"
                   class="w-full block bg-emerald-950 text-white min-h-[44px] p-3 rounded-xl font-semibold text-sm hover:bg-emerald-900 hover:-translate-y-0.5 transition-all duration-300 text-center shadow-md"
                   style="display:none;"
                >
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

</div>{{-- End game grid --}}

{{-- Score progress bar at bottom --}}
<div class="mt-6 bg-white rounded-2xl border border-slate-100 shadow-sm p-5 hover:shadow-md transition-all duration-300">
    <div class="flex items-center justify-between text-xs font-semibold text-slate-500 mb-2.5">
        <span class="flex items-center gap-1">
            <span class="material-symbols-outlined text-emerald-600" style="font-size:14px;">insights</span>
            Progress Jawaban
        </span>
        <span x-text="submitted ? (calculateCorrectCount() + ' / 4 Benar') : (targets.filter(t => t.matchedWith !== null).length + ' / 4 Terisi')" class="text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-100"></span>
    </div>
    <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden shadow-inner">
        <div
            class="h-full rounded-full bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-600 transition-all duration-500 shadow-sm"
            :style="`width: ${submitted ? calculateScore() : (targets.filter(t => t.matchedWith !== null).length * 25)}%`"
        ></div>
    </div>
    <div class="flex justify-between items-center mt-2.5 text-xs text-slate-400">
        <span>0%</span>
        <span class="font-bold text-slate-600" x-text="submitted ? (calculateScore() + ' / 100 Poin') : (targets.filter(t => t.matchedWith !== null).length + ' / 4 Jawaban')"></span>
        <span>100%</span>
    </div>
</div>
