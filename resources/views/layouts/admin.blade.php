<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#003527">

    <title>@yield('title', 'Admin') — BioCinta Cell Admin Panel</title>
    <meta name="description" content="Admin Panel BioCinta Cell — Kelola materi, siswa, dan konten pembelajaran.">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    {{-- Material Symbols Outlined --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

<body class="bg-slate-100 font-sans text-slate-800 min-h-screen" x-data="adminLayout()">

    {{-- =====================================================
    TOPBAR ADMIN
    ===================================================== --}}
    <header class="fixed top-0 w-full z-50 bg-white text-slate-800 border-b border-slate-200/50 shadow-sm"
        style="height:64px;">
        <div class="flex items-center justify-between h-full px-4 md:px-8 max-w-[1600px] mx-auto">

            {{-- Brand --}}
            <div class="flex items-center gap-3">
                {{-- Mobile hamburger --}}
                <button @click="sidebarOpen = !sidebarOpen"
                    class="md:hidden w-10 h-10 rounded-lg hover:bg-slate-100 flex items-center justify-center transition-colors"
                    aria-label="Toggle Sidebar">
                    <span class="material-symbols-outlined text-slate-700">menu</span>
                </button>

                <a href="{{ route('admin.materials.index') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-900 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-white" style="font-size:18px;">school</span>
                    </div>
                    <div class="hidden sm:block">
                        <span class="font-serif font-bold text-base leading-none text-slate-900">BioCinta Cell</span>
                        <span
                            class="text-[10px] text-emerald-700 font-bold uppercase tracking-wider block leading-none mt-0.5">Admin
                            Panel</span>
                    </div>
                </a>
            </div>

            {{-- User info + Logout --}}
            <div class="flex items-center gap-3">
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-all min-h-[40px]">
                        <span class="material-symbols-outlined text-slate-500" style="font-size:18px;">logout</span>
                        <span class="hidden sm:inline">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    {{-- =====================================================
    SIDEBAR (Desktop fixed | Mobile overlay)
    ===================================================== --}}
    {{-- Mobile backdrop --}}
    <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
        class="fixed inset-0 bg-black/50 z-30 md:hidden" style="display:none;"></div>

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
        class="fixed left-0 top-0 pt-16 h-full w-64 bg-emerald-950 z-40 transition-transform duration-300 overflow-y-auto">
        <nav class="p-4 space-y-1">
            <p class="text-xs font-semibold text-emerald-400 uppercase tracking-wider px-3 py-2">Menu Utama</p>

            {{-- Link ke Beranda Siswa --}}
            <a href="{{ route('home') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-emerald-200 hover:bg-emerald-800 hover:text-white transition-all group">
                <span class="material-symbols-outlined text-emerald-400 group-hover:text-amber-400"
                    style="font-size:20px;">home</span>
                Lihat Beranda Siswa
            </a>

            <p class="text-xs font-semibold text-emerald-400 uppercase tracking-wider px-3 py-3 mt-3">Konten Akademis
            </p>

            {{-- Materi CRUD --}}
            <a href="{{ route('admin.materials.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group
                  {{ request()->routeIs('admin.materials.*') ? 'bg-emerald-800 text-white' : 'text-emerald-200 hover:bg-emerald-800 hover:text-white' }}">
                <span
                    class="material-symbols-outlined {{ request()->routeIs('admin.materials.*') ? 'text-amber-400' : 'text-emerald-400 group-hover:text-amber-400' }}"
                    style="font-size:20px;">biotech</span>
                Materi Sel & Mikroskop
            </a>

            {{-- Penilaian Projek --}}
            <a href="{{ route('admin.projects.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group
                  {{ request()->routeIs('admin.projects.*') ? 'bg-emerald-800 text-white' : 'text-emerald-200 hover:bg-emerald-800 hover:text-white' }}">
                <span
                    class="material-symbols-outlined {{ request()->routeIs('admin.projects.*') ? 'text-amber-400' : 'text-emerald-400 group-hover:text-amber-400' }}"
                    style="font-size:20px;">video_library</span>
                Penilaian Projek
            </a>

            {{-- Bank Soal Puzzle --}}
            <a href="{{ route('admin.puzzles.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group
                  {{ request()->routeIs('admin.puzzles.*') ? 'bg-emerald-800 text-white' : 'text-emerald-200 hover:bg-emerald-800 hover:text-white' }}">
                <span
                    class="material-symbols-outlined {{ request()->routeIs('admin.puzzles.*') ? 'text-amber-400' : 'text-emerald-400 group-hover:text-amber-400' }}"
                    style="font-size:20px;">extension</span>
                Bank Soal Puzzle
            </a>

            {{-- Jurnal Refleksi Siswa --}}
            <a href="{{ route('admin.reflections.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group
                  {{ request()->routeIs('admin.reflections.*') ? 'bg-emerald-800 text-white' : 'text-emerald-200 hover:bg-emerald-800 hover:text-white' }}">
                <span
                    class="material-symbols-outlined {{ request()->routeIs('admin.reflections.*') ? 'text-amber-400' : 'text-emerald-400 group-hover:text-amber-400' }}"
                    style="font-size:20px;">edit_note</span>
                Jurnal Refleksi Siswa
            </a>

            {{-- Laboratorium Maya --}}
            <a href="{{ route('admin.lab.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group
                  {{ request()->routeIs('admin.lab.*') ? 'bg-emerald-800 text-white' : 'text-emerald-200 hover:bg-emerald-800 hover:text-white' }}">
                <span
                    class="material-symbols-outlined {{ request()->routeIs('admin.lab.*') ? 'text-amber-400' : 'text-emerald-400 group-hover:text-amber-400' }}"
                    style="font-size:20px;">science</span>
                Lab Virtual
            </a>

            {{-- Manajemen Siswa --}}
            <a href="{{ route('admin.students.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group
                  {{ request()->routeIs('admin.students.*') ? 'bg-emerald-800 text-white' : 'text-emerald-200 hover:bg-emerald-800 hover:text-white' }}">
                <span
                    class="material-symbols-outlined {{ request()->routeIs('admin.students.*') ? 'text-amber-400' : 'text-emerald-400 group-hover:text-amber-400' }}"
                    style="font-size:20px;">group</span>
                Manajemen Siswa
            </a>

            {{-- Papan Peringkat --}}
            <a href="{{ route('admin.leaderboard.index') }}"
                class="flex-1 flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group
                  {{ request()->routeIs('admin.leaderboard.*') ? 'bg-emerald-800 text-white' : 'text-emerald-200 hover:bg-emerald-800 hover:text-white' }}">
                <span
                    class="material-symbols-outlined {{ request()->routeIs('admin.leaderboard.*') ? 'text-amber-400' : 'text-emerald-400 group-hover:text-amber-400' }}"
                    style="font-size:20px;">leaderboard</span>
                Papan Peringkat
            </a>

            {{-- Manajemen Kuis --}}
            <a href="{{ route('admin.quizzes.index') }}"
                class="flex-1 flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group
                  {{ request()->routeIs('admin.quizzes.*') || request()->routeIs('admin.questions.*') ? 'bg-emerald-800 text-white' : 'text-emerald-200 hover:bg-emerald-800 hover:text-white' }}">
                <span
                    class="material-symbols-outlined {{ request()->routeIs('admin.quizzes.*') || request()->routeIs('admin.questions.*') ? 'text-amber-400' : 'text-emerald-400 group-hover:text-amber-400' }}"
                    style="font-size:20px;">quiz</span>
                Manajemen Kuis
            </a>

            {{-- Umpan Balik --}}
            <a href="{{ route('admin.feedbacks.index') }}"
                class="flex-1 flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group
                  {{ request()->routeIs('admin.feedbacks.*') ? 'bg-emerald-800 text-white' : 'text-emerald-200 hover:bg-emerald-800 hover:text-white' }}">
                <span
                    class="material-symbols-outlined {{ request()->routeIs('admin.feedbacks.*') ? 'text-amber-400' : 'text-emerald-400 group-hover:text-amber-400' }}"
                    style="font-size:20px;">rate_review</span>
                Umpan Balik Siswa
            </a>

            {{-- Pengaturan Situs (Google Form, dsb.) --}}
            <a href="{{ route('admin.settings.index') }}"
                class="flex-1 flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all group
                  {{ request()->routeIs('admin.settings.*') ? 'bg-emerald-800 text-white' : 'text-emerald-200 hover:bg-emerald-800 hover:text-white' }}">
                <span
                    class="material-symbols-outlined {{ request()->routeIs('admin.settings.*') ? 'text-amber-400' : 'text-emerald-400 group-hover:text-amber-400' }}"
                    style="font-size:20px;">settings</span>
                Pengaturan Situs
            </a>

        </nav>
    </aside>

    {{-- =====================================================
    MAIN CONTENT AREA
    ===================================================== --}}
    <div class="md:ml-64 pt-16 min-h-screen">
        {{-- Flash Messages --}}
        @if (session()->hasAny(['success', 'info', 'warning', 'error']))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="mx-4 md:mx-8 mt-4"
                style="display:none;">
                @if(session('success'))
                    <div
                        class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-xl shadow-sm text-sm font-medium">
                        <span class="text-emerald-600 flex-shrink-0">✅</span>
                        <span>{{ session('success') }}</span>
                        <button @click="show = false" class="ml-auto text-emerald-400 hover:text-emerald-700">✕</button>
                    </div>
                @endif
                @if(session('error'))
                    <div
                        class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-5 py-3.5 rounded-xl shadow-sm text-sm font-medium">
                        <span class="flex-shrink-0">❌</span>
                        <span>{{ session('error') }}</span>
                        <button @click="show = false" class="ml-auto text-red-400 hover:text-red-700">✕</button>
                    </div>
                @endif
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mx-4 md:mx-8 mt-4">
                <div class="bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl shadow-sm text-sm">
                    <div class="flex items-center gap-2 font-semibold mb-2">
                        <span>⚠️</span> Terdapat {{ $errors->count() }} kesalahan validasi:
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <main class="p-4 md:p-8">
            @yield('content')
        </main>
    </div>

    @stack('scripts')

    <script>
        function adminLayout() {
            return {
                sidebarOpen: false,
            };
        }
    </script>
</body>

</html>