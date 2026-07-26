<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#003527">

    <title>@yield('title', 'Beranda') — BioCinta Cell</title>
    <meta name="description" content="@yield('meta_description', 'BioCinta Cell: Media Pembelajaran Sel Hewan & Tumbuhan Berbasis Kurikulum CINTA MAN 2 Kebumen')">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    {{-- Material Symbols Outlined (Google Icons) --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="bg-surface font-sans text-on-surface" x-data="appLayout()">

    {{-- =========================================================
         TOP NAVIGATION BAR — Frosted Glass + Scroll Shrink Effect
         ========================================================= --}}
    <nav
        id="top-nav"
        class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-200/40 shadow-sm transition-all duration-300"
        style="height: 80px;"
        role="navigation"
        aria-label="Navigasi utama BioCinta Cell"
    >
        <div class="flex justify-between items-center px-4 md:px-16 h-full max-w-[1440px] mx-auto">

            {{-- Brand --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span class="font-bold text-xl md:text-2xl leading-none tracking-wide"><span class="text-primary">BIO</span><span class="text-amber-500">CINTA</span> <span class="text-amber-500">CELL</span></span>
                </a>
            </div>

            {{-- Actions: Admin Link (for admin users) + Logout + Avatar --}}
            <div class="flex items-center gap-3">

                {{-- Admin Panel link — only shown to role:admin --}}
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a
                        href="{{ route('admin.materials.index') }}"
                        class="hidden sm:flex items-center gap-1.5 px-3 py-2 rounded-full bg-amber-50 border border-amber-200 text-amber-700
                               hover:bg-amber-100 transition-all text-sm font-semibold min-h-[44px]"
                    >
                        <span class="material-symbols-outlined" style="font-size:16px;">admin_panel_settings</span>
                        Panel Admin
                    </a>
                @endif

                {{-- Logout Button --}}
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button
                        type="submit"
                        id="btn-logout"
                        class="flex items-center gap-2 px-4 py-2 rounded-full border border-primary text-primary
                               hover:bg-primary hover:text-white transition-all text-sm font-medium min-h-[44px]"
                    >
                        <span class="material-symbols-outlined" style="font-size: 18px;">logout</span>
                        <span class="hidden sm:inline">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- Spacer navbar --}}
    <div style="height: 80px;"></div>

    {{-- =========================================================
         FLASH MESSAGES (Auto-dismiss 5 detik)
         ========================================================= --}}
    @if (session()->hasAny(['success', 'info', 'warning', 'error']))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 5000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="fixed top-[88px] left-1/2 -translate-x-1/2 z-50 w-[90vw] max-w-lg"
        >
            @if(session('success'))
                <div class="flash-success px-5 py-3.5 rounded-2xl shadow-xl flex items-center gap-3 text-sm font-medium">
                    <span class="text-xl flex-shrink-0">✅</span>
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="ml-auto opacity-70 hover:opacity-100 flex-shrink-0">✕</button>
                </div>
            @endif
            @if(session('info'))
                <div class="flash-info px-5 py-3.5 rounded-2xl shadow-xl flex items-center gap-3 text-sm font-medium">
                    <span class="text-xl flex-shrink-0">ℹ️</span>
                    <span>{{ session('info') }}</span>
                    <button @click="show = false" class="ml-auto opacity-70 hover:opacity-100 flex-shrink-0">✕</button>
                </div>
            @endif
            @if(session('warning'))
                <div class="flash-warning px-5 py-3.5 rounded-2xl shadow-xl flex items-center gap-3 text-sm font-medium">
                    <span class="text-xl flex-shrink-0">⚠️</span>
                    <span>{{ session('warning') }}</span>
                    <button @click="show = false" class="ml-auto opacity-70 hover:opacity-100 flex-shrink-0">✕</button>
                </div>
            @endif
            @if(session('error'))
                <div class="flash-error px-5 py-3.5 rounded-2xl shadow-xl flex items-center gap-3 text-sm font-medium">
                    <span class="text-xl flex-shrink-0">❌</span>
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="ml-auto opacity-70 hover:opacity-100 flex-shrink-0">✕</button>
                </div>
            @endif
        </div>
    @endif

    {{-- =========================================================
         MAIN CONTENT
         ========================================================= --}}
    <main id="main-content" role="main">
        @yield('content')
    </main>

    {{-- =========================================================
         FOOTER — Light, Clean
         ========================================================= --}}
    <footer class="bg-white border-t border-outline-variant/20">
        <div class="flex flex-col md:flex-row justify-between items-center px-4 md:px-16 py-6 max-w-[1440px] mx-auto w-full gap-4">
            <div class="flex flex-col items-center md:items-start">
                <span class="font-bold text-xl md:text-2xl leading-none tracking-wide"><span class="text-primary">BIO</span><span class="text-amber-500">CINTA</span> <span class="text-amber-500">CELL</span></span>
                <p class="text-on-surface-variant text-xs opacity-70">
                    &copy; {{ date('Y') }}. All Rights Reserved.
                </p>
            </div>
            <div class="flex items-center gap-6">
                <a href="{{ route('about') }}" class="text-sm font-semibold text-secondary hover:text-primary transition-all">
                    About Us
                </a>
            </div>
        </div>
    </footer>

    @stack('scripts')

    <script>
        // Scroll shrink effect for frosted glass navbar
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('top-nav');
            if (window.scrollY > 20) {
                nav.style.height = '64px';
                nav.classList.add('shadow-md');
                nav.classList.remove('shadow-sm');
            } else {
                nav.style.height = '80px';
                nav.classList.remove('shadow-md');
                nav.classList.add('shadow-sm');
            }
        }, { passive: true });

        function appLayout() {
            return {};
        }
    </script>
</body>
</html>
