@extends('layouts.app')
@section('title', 'Alat Mikroskop & Jenis-jenisnya')
@section('meta_description', 'Materi teori alat mikroskop: Mikroskop Cahaya vs Elektron, Monokuler vs Binokuler, dan komponen mekanik & optik — BioCinta Cell MAN 2 Kebumen')

@section('content')
<div x-data="microscopePage()">

{{-- ============================================================
     BREADCRUMB
     ============================================================ --}}
<div class="bg-white border-b border-outline-variant/20">
    <div class="max-w-[1440px] mx-auto px-4 md:px-16 py-3 flex items-center gap-2 text-xs text-on-surface-variant">
        <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
        <span class="material-symbols-outlined" style="font-size:14px;">chevron_right</span>
        <span class="text-primary font-semibold">Alat Mikroskop</span>
    </div>
</div>

{{-- ============================================================
     HERO
     ============================================================ --}}
<header class="bg-gradient-to-br from-slate-900 to-emerald-950 text-white py-14 px-4 md:px-16">
    <div class="max-w-[1440px] mx-auto">
        <div class="flex flex-col md:flex-row items-center gap-8">
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 bg-white/10 px-4 py-1.5 rounded-full border border-white/20 mb-5 text-xs font-medium">
                    <span class="material-symbols-outlined text-tertiary-fixed" style="font-size:16px;">science</span>
                    Materi Prasyarat Laboratorium
                </div>
                <h1 class="font-serif font-bold text-3xl md:text-4xl leading-tight mb-4">
                    Alat Mikroskop dan Jenis-jenisnya
                </h1>
                <p class="text-slate-300 text-sm md:text-base leading-relaxed max-w-xl">
                    Pelajari perbedaan Mikroskop Cahaya vs Elektron, Monokuler vs Binokuler, dan kenali fungsi setiap komponen sebelum praktikum.
                </p>
            </div>
        </div>

        {{-- Quick Nav dalam Hero --}}
        <div class="mt-8 flex flex-wrap gap-3">
            @foreach($sections as $index => $section)
            <button
                @click="scrollToSection({{ $index }})"
                class="flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20
                       text-white text-xs font-medium px-4 py-2 rounded-full transition-all min-h-[40px]"
            >
                <span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px] font-bold">{{ $index + 1 }}</span>
                {{ $section->title }}
            </button>
            @endforeach
        </div>
    </div>
</header>

{{-- ============================================================
     TABEL PERBANDINGAN CEPAT: Cahaya vs Elektron
     ============================================================ --}}
<section class="bg-white border-b border-outline-variant/20">
    <div class="max-w-[1440px] mx-auto px-4 md:px-16 py-8">
        <h2 class="font-serif font-bold text-xl text-primary mb-5 flex items-center gap-2">
            <span class="material-symbols-outlined" style="font-size:22px;">compare_arrows</span>
            Perbandingan Sekilas
        </h2>
        <div class="overflow-x-auto rounded-2xl border border-outline-variant/30">
            <table class="w-full text-sm min-w-[520px]">
                <thead>
                    <tr class="bg-primary text-white">
                        <th class="px-4 py-3 text-left font-semibold">Aspek</th>
                        <th class="px-4 py-3 text-center font-semibold">🔭 Mikroskop Cahaya</th>
                        <th class="px-4 py-3 text-center font-semibold">⚡ Mikroskop Elektron</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @php
                    $comparison = [
                        ['Sumber Penyinaran', 'Cahaya tampak (visible light)', 'Berkas elektron'],
                        ['Pembesaran Maks.', '1.000× (minyak imersi)', '1.000.000×'],
                        ['Resolusi', '~200 nm (0,2 µm)', '~0,1 nm'],
                        ['Kondisi Sampel', 'Bisa sel hidup & mati', 'Hanya sel mati (vakum)'],
                        ['Warna Gambar', 'Berwarna (asli/pewarnaan)', 'Hitam-putih (false color)'],
                        ['Harga', 'Terjangkau (Rp 2–50 juta)', 'Sangat mahal (> Rp 5 miliar)'],
                        ['Penggunaan', 'Sekolah, klinik, lab umum', 'Riset tingkat lanjut'],
                    ];
                    @endphp
                    @foreach($comparison as $i => $row)
                    <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-surface-container-low' }}">
                        <td class="px-4 py-3 font-semibold text-on-surface">{{ $row[0] }}</td>
                        <td class="px-4 py-3 text-center text-on-surface-variant">{{ $row[1] }}</td>
                        <td class="px-4 py-3 text-center text-on-surface-variant">{{ $row[2] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- ============================================================
     VIDEO PANDUAN PENGGUNAAN MIKROSKOP
     ============================================================ --}}
<section class="bg-surface-container-low border-b border-outline-variant/20 py-10">
    <div class="max-w-[1440px] mx-auto px-4 md:px-16">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="font-serif font-bold text-xl md:text-2xl text-primary flex items-center gap-2">
                    Video Panduan: Cara Kerja & Penggunaan Mikroskop
                </h2>
                <p class="text-xs text-on-surface-variant mt-1">Tonton video demo praktikum ini untuk mempelajari cara mengoperasikan mikroskop dengan benar sebelum masuk laboratorium.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            {{-- Video Frame --}}
            <div class="lg:col-span-2 rounded-2xl overflow-hidden aspect-video bg-black shadow-lg border border-slate-200">
                {{-- YouTube Embed --}}
                <iframe
                    class="w-full h-full"
                    src="https://www.youtube.com/embed/Ectrd0ADaZE"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                ></iframe>
            </div>

            {{-- Info / Deskripsi Video --}}
            <div class="space-y-4 h-full flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="glass-card rounded-xl p-5 border border-outline-variant/30">
                        <h3 class="font-bold text-xs text-slate-800 mb-2 flex items-center gap-1.5 uppercase tracking-wide">
                            <span class="material-symbols-outlined text-emerald-700" style="font-size:16px;">science</span>
                            Instruksi Praktikum
                        </h3>
                        <p class="text-xs text-on-surface-variant leading-relaxed">
                            Video ini memperagakan cara meletakkan kaca preparat, mengatur perbesaran lensa objektif dari yang terkecil, memutar fokus kasar (makrometer), hingga merawat lensa mikroskop setelah dipakai.
                        </p>
                    </div>

                    <div class="bg-primary/5 rounded-xl p-4 border border-primary/10">
                        <h4 class="font-bold text-xs text-primary uppercase tracking-wide flex items-center gap-1.5 mb-1.5">
                            <span class="material-symbols-outlined" style="font-size:16px;">warning</span>
                            Keselamatan Kerja
                        </h4>
                        <p class="text-xs text-slate-700 leading-relaxed">
                            Jangan menaikkan stage/meja preparat terlalu cepat saat menggunakan lensa objektif perbesaran tinggi agar tidak memecahkan kaca objek.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
     AKORDION MATERI — Alpine.js
     ============================================================ --}}
<section class="max-w-[1440px] mx-auto px-4 md:px-16 py-10">
    <h2 class="font-serif font-bold text-2xl text-primary mb-6 flex items-center gap-2">
        <span class="material-symbols-outlined" style="font-size:26px;">menu_book</span>
        Materi Lengkap
    </h2>

    <div class="space-y-4" id="accordion-container">
        @foreach($sections as $index => $section)
        <div
            x-data="{ open: {{ $index === 0 ? 'true' : 'false' }} }"
            class="glass-card rounded-2xl overflow-hidden"
            :id="`section-{{ $index }}`"
        >
            {{-- Header Akordion --}}
            <button
                @click="open = !open"
                class="w-full flex items-center justify-between px-5 py-4 text-left hover:bg-surface-container/50 transition-colors min-h-[64px] group"
            >
                <div class="flex items-center gap-4">
                    <div class="w-9 h-9 rounded-xl bg-primary flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        {{ $index + 1 }}
                    </div>
                    <span class="font-serif font-semibold text-base md:text-lg text-primary">{{ $section->title }}</span>
                </div>
                <span
                    class="material-symbols-outlined text-on-surface-variant transition-transform duration-300"
                    :class="open ? 'rotate-180' : 'rotate-0'"
                    style="font-size:22px;"
                >expand_more</span>
            </button>

            {{-- Body Akordion --}}
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="border-t border-outline-variant/20"
            >
                <div class="px-5 py-5 space-y-6">

                    {{-- Konten deskripsi utama --}}
                    <div class="prose prose-sm max-w-none text-on-surface leading-relaxed">
                        @foreach(explode("\n\n", $section->description) as $para)
                            @php $para = trim($para); @endphp
                            @if(str_starts_with($para, '**') && str_ends_with($para, '**'))
                                <h4 class="font-bold text-primary text-sm mt-4 mb-2">{{ trim($para, '*') }}</h4>
                            @elseif(str_contains($para, '**'))
                                <div class="bg-surface-container-low rounded-xl p-4 text-sm space-y-2">
                                    @foreach(explode("\n", $para) as $line)
                                        @php $line = trim($line); @endphp
                                        @if($line)
                                            @if(preg_match('/^\*\*(.+)\*\*(.*)$/', $line, $m))
                                                <p><span class="font-bold text-primary">{{ $m[1] }}</span>{{ $m[2] }}</p>
                                            @else
                                                <p>{{ $line }}</p>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm leading-relaxed">{{ $para }}</p>
                            @endif
                        @endforeach
                    </div>

                    {{-- Sejarah Penemu --}}
                    @if($section->history_founder)
                    <div class="border-l-4 border-amber-400 pl-4 bg-amber-50 rounded-r-xl p-4">
                        <p class="text-xs font-bold text-amber-700 uppercase tracking-wide mb-2">🔍 Sejarah Penemu</p>
                        <p class="text-sm text-on-surface leading-relaxed">{{ $section->history_founder }}</p>
                    </div>
                    @endif

                    {{-- Tadabbur Quran --}}
                    @if($section->quran_verse)
                    <div class="bg-gradient-to-br from-emerald-900 to-teal-900 rounded-xl p-5 text-white relative overflow-hidden">
                        <span class="material-symbols-outlined absolute top-2 right-3 text-white/10 select-none" style="font-size:60px;">format_quote</span>
                        <p class="text-xs font-bold text-tertiary-fixed uppercase tracking-wide mb-2 relative z-10">✨ Tadabbur — Renungan Islami</p>
                        <p class="text-sm leading-relaxed italic relative z-10">{{ $section->quran_verse }}</p>
                    </div>
                    @endif

                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

</div>{{-- End x-data --}}
@endsection

@push('scripts')
<script>
function microscopePage() {
    return {
        scrollToSection(index) {
            const el = document.getElementById(`section-${index}`);
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                // Buka accordion jika belum terbuka
                setTimeout(() => {
                    const btn = el.querySelector('button');
                    if (btn) btn.click();
                }, 400);
            }
        },

        async sendFeedback() {
            if (this.msg.length < 10) return;
            this.loading = true;
            try {
                const res = await fetch('/materials/1/feedback', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ message: this.msg })
                });
                const data = await res.json();
                if (data.success) this.sent = true;
            } catch(e) {
                alert('Gagal mengirim. Coba lagi ya.');
            } finally {
                this.loading = false;
            }
        }
    };
}
</script>
@endpush
