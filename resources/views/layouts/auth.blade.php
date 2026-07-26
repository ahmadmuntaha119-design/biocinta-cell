<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'BioCinta Cell' }} — Platform Belajar Biologi Interaktif</title>
    <meta name="description" content="BioCinta Cell: Media Pembelajaran Sel Hewan & Tumbuhan Berbasis Kurikulum CINTA MAN 2 Kebumen">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-700 min-h-screen">

    {{-- Flash Messages --}}
    @if (session('success') || session('info') || session('warning') || session('error'))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 5000)"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-[90vw] max-w-md"
        >
            @if (session('success'))
                <div class="bg-emerald-900 text-white px-5 py-3 rounded-xl shadow-lg flex items-center gap-3 text-sm font-medium">
                    <span class="text-lg">✅</span> {{ session('success') }}
                </div>
            @endif
            @if (session('info'))
                <div class="bg-blue-600 text-white px-5 py-3 rounded-xl shadow-lg flex items-center gap-3 text-sm font-medium">
                    <span class="text-lg">ℹ️</span> {{ session('info') }}
                </div>
            @endif
            @if (session('warning'))
                <div class="bg-amber-500 text-white px-5 py-3 rounded-xl shadow-lg flex items-center gap-3 text-sm font-medium">
                    <span class="text-lg">⚠️</span> {{ session('warning') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-600 text-white px-5 py-3 rounded-xl shadow-lg flex items-center gap-3 text-sm font-medium">
                    <span class="text-lg">❌</span> {{ session('error') }}
                </div>
            @endif
        </div>
    @endif

    @yield('content')

</body>
</html>
