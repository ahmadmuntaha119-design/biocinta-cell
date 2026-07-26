@extends('layouts.app')
@section('title', 'About Us')
@section('meta_description', 'Tentang BioCinta Cell — Platform Media Pembelajaran Digital Inovatif untuk Struktur dan Fungsi Sel di MAN 2 Kebumen.')

@section('content')

{{-- ============================================================
     HERO SECTION
     ============================================================ --}}
<header class="relative overflow-hidden bg-gradient-to-br from-emerald-950 via-emerald-900 to-emerald-950 py-16 text-center">
    {{-- Decorative Background Pattern Overlay --}}
    <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden">
        <svg class="absolute top-10 left-10 w-96 h-96 opacity-5 text-emerald-100 fill-current" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path d="M41.3,-71.5C53.7,-64.5,64,-53.1,71.2,-39.9C78.4,-26.7,82.5,-11.7,81.4,2.9C80.3,17.4,74.1,31.6,65.3,43.5C56.6,55.5,45.3,65.1,32.3,71.6C19.3,78.1,4.6,81.4,-10.1,80.1C-24.9,78.8,-39.8,72.9,-51.9,64.2C-64,55.5,-73.2,43.9,-78.9,30.5C-84.6,17.1,-86.7,1.8,-84.6,-13.2C-82.5,-28.3,-76.3,-43.2,-66,-53.8C-55.7,-64.4,-41.3,-70.7,-27.6,-76.9C-13.9,-83.1,-0.9,-89.1,12.1,-88.1C25.1,-87.1,30.1,-78.5,41.3,-71.5Z" transform="translate(100 100)" />
        </svg>
    </div>

    <div class="px-4 md:px-16 max-w-[1440px] mx-auto relative z-10">
        <h1 class="font-serif font-bold text-white text-3xl md:text-4xl">About Us</h1>
    </div>
    
    {{-- Premium Wave Divider --}}
    <div class="absolute z-20 bottom-0 left-0 w-full overflow-hidden leading-[0]">
        <svg class="relative block w-full h-[30px]" viewBox="0 0 1440 100" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path fill="#f7f9fb" d="M0,40 C420,115 980,-15 1440,55 L1440,100 L0,100 Z"></path>
        </svg>
    </div>
</header>

{{-- ============================================================
     MAIN CONTENT CONTAINER
     ============================================================ --}}
<main class="px-4 md:px-16 py-8 max-w-[1440px] mx-auto -mt-4 relative z-20">
    <div class="max-w-4xl mx-auto">
        
        {{-- ABOUT US CARD --}}
        <section class="glass-card rounded-2xl p-8 mb-6">
            <h2 class="font-bold text-xl md:text-2xl leading-none tracking-wide mb-4 pb-2 border-b-2 border-amber-500 inline-block"><span class="text-primary">BIO</span><span class="text-amber-500">CINTA</span> <span class="text-amber-500">CELL</span></h2>
            <p class="text-on-surface-variant text-sm md:text-base leading-relaxed text-justify mb-4">
                <strong>BioCinta Cell</strong> merupakan platform media pembelajaran digital inovatif yang dirancang khusus untuk siswa MAN 2 Kebumen dalam mempelajari materi Biologi, khususnya pada topik Struktur dan Fungsi Sel Kelas XI. Platform ini mengintegrasikan sains biologi modern dengan nilai-nilai luhur Kurikulum CINTA, sehingga siswa tidak hanya cerdas secara kognitif, melainkan juga memiliki kedalaman refleksi spiritual (tadabbur) terhadap kesempurnaan ciptaan Allah SWT.
            </p>
            <p class="text-on-surface-variant text-sm md:text-base leading-relaxed text-justify">
                Dengan mengusung konsep interaktif, BioCinta Cell menyediakan berbagai modul komprehensif mulai dari pemantauan materi terstruktur, simulasi laboratorium digital (Virtual Lab), visualisasi video animasi 3D, zona evaluasi kuis dan game interaktif, hingga ruang kolaborasi projek kelompok. Kehadiran platform ini diharapkan mampu memberikan pengalaman belajar sains yang menyenangkan, presisi, dan berkarakter mulia.
            </p>
        </section>
    </div>
</main>
@endsection