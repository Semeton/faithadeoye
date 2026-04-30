<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <title>{{ filled($title ?? null) ? $title.' — '.config('app.name') : \App\Models\SiteSetting::get('seo_title', config('app.name')) }}</title>
        <meta name="description" content="{{ \App\Models\SiteSetting::get('seo_description', '') }}" />

        @php $ogImage = \App\Models\SiteSetting::get('seo_og_image'); @endphp
        @if($ogImage)
            <meta property="og:image" content="{{ asset('storage/'.$ogImage) }}" />
        @endif
        <meta property="og:title" content="{{ filled($title ?? null) ? $title : \App\Models\SiteSetting::get('seo_title', config('app.name')) }}" />
        <meta property="og:description" content="{{ \App\Models\SiteSetting::get('seo_description', '') }}" />
        <meta property="og:type" content="website" />
        <meta name="twitter:card" content="summary_large_image" />

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @php $ga4Id = \App\Models\SiteSetting::get('integration_ga4_id'); @endphp
        @if($ga4Id)
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4Id }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '{{ $ga4Id }}');
            </script>
        @endif
    </head>
    <body class="bg-white text-zinc-900 antialiased">

        {{-- Navigation --}}
        <header class="fixed top-0 inset-x-0 z-50 bg-white/90 backdrop-blur-sm border-b border-zinc-100">
            <nav class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
                <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-2.5 group">
                    {{-- Monogram mark --}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28" class="h-7 w-7 shrink-0" aria-hidden="true">
                        <rect width="28" height="28" rx="6" fill="#09090b"/>
                        <rect x="7" y="7" width="3.5" height="14" rx="1.75" fill="white"/>
                        <rect x="7" y="7" width="13" height="3.5" rx="1.75" fill="white"/>
                        <rect x="7" y="13.5" width="9" height="3.5" rx="1.75" fill="white"/>
                        <circle cx="22" cy="22" r="3" fill="#f59e0b"/>
                    </svg>
                    <span class="text-sm font-semibold tracking-wide text-zinc-900 group-hover:text-zinc-600 transition-colors">
                        Faith O. Adeoye
                    </span>
                </a>
                <div class="flex items-center gap-8">
                    <a href="{{ route('projects') }}" wire:navigate
                       class="text-sm font-medium text-zinc-600 hover:text-zinc-900 transition-colors {{ request()->routeIs('projects*') ? 'text-zinc-900' : '' }}">
                        Projects
                    </a>
                    <a href="{{ route('home') }}#contact" wire:navigate
                       class="text-sm font-medium text-zinc-600 hover:text-zinc-900 transition-colors">
                        Contact Me
                    </a>
                </div>
            </nav>
        </header>

        {{-- Page content --}}
        <main class="pt-[65px]">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="bg-zinc-950 text-zinc-400 py-10">
            <div class="max-w-6xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm">
                <div class="flex items-center gap-3">
                    {{-- Footer mark (white version) --}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 22" class="h-5 w-5 shrink-0 opacity-70" aria-hidden="true">
                        <rect width="22" height="22" rx="5" fill="white" fill-opacity="0.12"/>
                        <rect x="5.5" y="5.5" width="2.75" height="11" rx="1.375" fill="white"/>
                        <rect x="5.5" y="5.5" width="10" height="2.75" rx="1.375" fill="white"/>
                        <rect x="5.5" y="10.5" width="7" height="2.75" rx="1.375" fill="white"/>
                        <circle cx="17" cy="17" r="2.25" fill="#f59e0b"/>
                    </svg>
                    <span>&copy; {{ date('Y') }} Faith O. Adeoye. All rights reserved.</span>
                </div>
                <div class="flex items-center gap-6">
                    <a href="mailto:{{ \App\Models\SiteSetting::get('contact_email', 'faithadeoye@gmail.com') }}"
                       class="hover:text-white transition-colors">
                        {{ \App\Models\SiteSetting::get('contact_email', 'faithadeoye@gmail.com') }}
                    </a>
                    <a href="{{ \App\Models\SiteSetting::get('hero_linkedin_url', '#') }}"
                       target="_blank" rel="noopener noreferrer"
                       class="hover:text-white transition-colors">
                        LinkedIn
                    </a>
                </div>
            </div>
        </footer>

        @livewireScripts
    </body>
</html>
