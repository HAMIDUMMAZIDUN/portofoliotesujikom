@props(['title' => 'Digital Guestbook'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title }} - By Biru ID</title>

    <link rel="icon" href="{{ asset('img/bybiru.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        // Apply theme IMMEDIATELY to avoid flash
        (function() {
            const saved = localStorage.getItem('color-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved === 'dark' || (!saved && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <style>
        /* Smooth theme transition for all elements */
        *, *::before, *::after {
            transition-property: background-color, border-color, color;
            transition-duration: 200ms;
            transition-timing-function: ease;
        }
        /* Override transition on elements that should animate instantly */
        [x-cloak] { display: none !important; }

        /* Theme toggle button styles */
        .theme-toggle-btn {
            position: relative;
            width: 52px;
            height: 28px;
            border-radius: 14px;
            cursor: pointer;
            border: none;
            outline: none;
            transition: background-color 0.3s ease;
        }
        .theme-toggle-btn .thumb {
            position: absolute;
            top: 3px;
            left: 3px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: white;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.3);
        }
        html.dark .theme-toggle-btn .thumb {
            transform: translateX(24px);
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white pb-24 antialiased font-sans">

    <nav class="bg-white/95 dark:bg-gray-800/95 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 p-4 fixed top-0 w-full z-40">
        <div class="max-w-screen-xl mx-auto flex justify-between items-center">
            {{-- Logo & Title --}}
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/bybiru.png') }}" alt="Logo Biru ID"
                     class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover border border-gray-200 dark:border-gray-600 shadow-sm">
                <h1 class="text-lg md:text-xl font-bold tracking-tight text-gray-900 dark:text-white leading-tight">
                    DIGITAL GUESTBOOK BY BIRU ID
                    <span class="block md:inline text-xs md:text-sm font-normal text-blue-600 dark:text-blue-400 md:ml-2">
                        IG : BY BIRU ID - WA : 0895707266665
                    </span>
                </h1>
            </div>

            {{-- Right Side Actions --}}
            <div class="flex items-center gap-1 sm:gap-2">

                {{-- ☀️🌙 DARK/LIGHT MODE TOGGLE --}}
                <button id="theme-toggle"
                        onclick="toggleTheme()"
                        title="Ganti Tema"
                        class="relative flex items-center p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group"
                        aria-label="Toggle dark mode">

                    {{-- Sun icon (visible in dark mode) --}}
                    <svg id="icon-sun"
                         class="w-5 h-5 sm:w-6 sm:h-6 text-amber-500 hidden dark:block transition-all duration-300 group-hover:rotate-45"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>

                    {{-- Moon icon (visible in light mode) --}}
                    <svg id="icon-moon"
                         class="w-5 h-5 sm:w-6 sm:h-6 text-gray-500 dark:hidden transition-all duration-300 group-hover:-rotate-12"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>

                    {{-- Tooltip --}}
                    <span class="absolute -bottom-8 left-1/2 -translate-x-1/2 bg-gray-800 dark:bg-gray-200 text-white dark:text-gray-900 text-[10px] font-semibold px-2 py-1 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                        <span class="dark:hidden">Mode Gelap</span>
                        <span class="hidden dark:inline">Mode Terang</span>
                    </span>
                </button>

                {{-- Edit Profile --}}
                <a href="{{ route('profile.edit') }}"
                   class="p-2 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                   title="Edit Profil">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </a>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="p-2 rounded-full text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors"
                            title="Logout"
                            onclick="return confirm('Apakah anda yakin ingin keluar?');">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="p-4 pt-24 max-w-screen-xl mx-auto min-h-screen">
        {{ $slot }}
    </main>

    {{-- Bottom Navigation Bar --}}
    <div class="fixed bottom-0 left-0 z-50 w-full h-20 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.07)]">
        <div class="flex justify-between h-full max-w-lg mx-auto font-medium">

            <a href="{{ route('guests.index') }}"
               class="flex-1 flex flex-col items-center justify-center px-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 group transition-colors
                      {{ request()->routeIs('guests.index') ? 'text-blue-600 dark:text-blue-400 border-t-2 border-blue-500 dark:border-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
                <svg class="w-6 h-6 mb-1 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="text-xs font-semibold">List Tamu</span>
            </a>

            <a href="{{ route('server1') }}"
               class="flex-1 flex flex-col items-center justify-center px-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 group transition-colors
                      {{ request()->routeIs('server1') ? 'text-emerald-600 dark:text-emerald-400 border-t-2 border-emerald-500 dark:border-emerald-400' : 'text-gray-500 dark:text-gray-400' }}">
                <svg class="w-6 h-6 mb-1 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                <span class="text-xs font-semibold">Scan</span>
            </a>

            <a href="{{ route('souvenir') }}"
               class="flex-1 flex flex-col items-center justify-center px-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 group transition-colors
                      {{ request()->routeIs('souvenir') ? 'text-purple-600 dark:text-purple-400 border-t-2 border-purple-500 dark:border-purple-400' : 'text-gray-500 dark:text-gray-400' }}">
                <svg class="w-6 h-6 mb-1 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span class="text-xs font-semibold">Souvenir</span>
            </a>

            <a href="{{ route('attendance') }}"
               class="flex-1 flex flex-col items-center justify-center px-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 group transition-colors
                      {{ request()->routeIs('attendance') ? 'text-blue-600 dark:text-blue-400 border-t-2 border-blue-500 dark:border-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
                <svg class="w-6 h-6 mb-1 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <span class="text-xs font-semibold">Tamu Hadir</span>
            </a>

        </div>
    </div>

    <script>
        // ===== THEME TOGGLE FUNCTION =====
        function toggleTheme() {
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');

            if (isDark) {
                html.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        }
    </script>

</body>
</html>