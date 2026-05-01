<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Page Not Found — Faith O. Adeoye</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-white antialiased min-h-screen flex flex-col items-center justify-center px-6">

    {{-- Logo mark --}}
    <a href="{{ route('home') }}" class="mb-12 group">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28" class="h-10 w-10" aria-hidden="true">
            <rect width="28" height="28" rx="6" fill="#09090b"/>
            <rect width="28" height="28" rx="6" fill="white" fill-opacity="0.06"/>
            <rect x="7" y="7" width="3.5" height="14" rx="1.75" fill="white"/>
            <rect x="7" y="7" width="13" height="3.5" rx="1.75" fill="white"/>
            <rect x="7" y="13.5" width="9" height="3.5" rx="1.75" fill="white"/>
            <circle cx="22" cy="22" r="3" fill="#f59e0b"/>
        </svg>
    </a>

    {{-- 404 display --}}
    <div class="text-center max-w-md">
        <p class="text-xs font-semibold tracking-widest uppercase text-zinc-500 mb-4">404</p>
        <h1 class="text-4xl sm:text-5xl font-bold tracking-tight mb-4 leading-tight">
            This page doesn't exist.
        </h1>
        <p class="text-zinc-400 leading-relaxed mb-10">
            You may have followed a broken link, or the page has been moved. Let's get you back on track.
        </p>

        <div class="flex flex-wrap justify-center gap-3">
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 bg-white text-zinc-900 font-semibold px-6 py-3 rounded-lg hover:bg-zinc-100 transition-colors text-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Go home
            </a>
            <a href="{{ route('projects') }}"
               class="inline-flex items-center gap-2 border border-white/15 text-white font-medium px-6 py-3 rounded-lg hover:bg-white/8 transition-colors text-sm">
                View projects
            </a>
        </div>
    </div>

    {{-- Decorative amber dot --}}
    <div class="absolute bottom-12 left-1/2 -translate-x-1/2 w-1.5 h-1.5 rounded-full bg-amber-400 opacity-60"></div>

</body>
</html>
