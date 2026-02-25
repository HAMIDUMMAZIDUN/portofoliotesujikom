<x-app-layout title="Edit Profil">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Profil Pengguna') }}</h2>
    </x-slot>

    <div class="min-h-screen font-sans">

        {{-- HERO --}}
        <div class="relative overflow-hidden rounded-2xl mb-6" style="background:linear-gradient(135deg,#4338ca,#6366f1,#818cf8)">
            <div class="absolute inset-0 opacity-10" style="background-image:radial-gradient(circle at 20% 50%,white 1px,transparent 1px);background-size:40px 40px"></div>
            <div class="relative px-5 py-6 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <span class="text-white/80 text-xs font-semibold uppercase tracking-widest">Pengaturan Akun</span>
                    </div>
                    <h1 class="text-2xl font-black text-white">Profil Pengguna</h1>
                    <p class="text-indigo-200 text-sm font-medium mt-0.5">Kelola informasi akun dan keamanan Anda</p>
                </div>
                <div class="hidden sm:flex w-16 h-16 rounded-2xl bg-white/15 border border-white/20 items-center justify-center">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="max-w-2xl space-y-5">

            {{-- Tampilan Tema --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-5 py-4 flex items-center gap-3 border-b border-gray-100 dark:border-gray-700">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">Tampilan Tema</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Pilih tema terang atau gelap</p>
                    </div>
                </div>
                <div class="p-5">
                    <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600">
                        <div class="flex items-center gap-3">
                            {{-- Sun --}}
                            <svg class="w-5 h-5 text-amber-500 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            {{-- Moon --}}
                            <svg class="w-5 h-5 text-indigo-400 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">
                                    <span class="dark:hidden">Mode Terang</span>
                                    <span class="hidden dark:inline">Mode Gelap</span>
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    <span class="dark:hidden">Klik untuk beralih ke mode gelap</span>
                                    <span class="hidden dark:inline">Klik untuk beralih ke mode terang</span>
                                </p>
                            </div>
                        </div>
                        {{-- Toggle Switch --}}
                        <button onclick="toggleTheme()" id="theme-toggle-profile"
                                class="relative inline-flex w-14 h-7 items-center rounded-full transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                                :class="document.documentElement.classList.contains('dark') ? 'bg-indigo-600' : 'bg-gray-300'"
                                x-data
                                :class="$el.closest('html').classList.contains('dark') ? 'bg-indigo-600' : 'bg-gray-300'"
                                style="background-color: var(--toggle-bg, #d1d5db)">
                            <span class="inline-block w-5 h-5 transform rounded-full bg-white shadow-md transition-transform duration-300"
                                  id="toggle-thumb-profile"></span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Informasi Profil --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-5 py-4 flex items-center gap-3 border-b border-gray-100 dark:border-gray-700">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">Informasi Profil</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Perbarui nama, email, dan info acara</p>
                    </div>
                </div>
                <div class="p-5">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Update Password --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-5 py-4 flex items-center gap-3 border-b border-gray-100 dark:border-gray-700">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">Keamanan Password</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Perbarui password akun Anda</p>
                    </div>
                </div>
                <div class="p-5">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Hapus Akun --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-red-200 dark:border-red-900/50 shadow-sm overflow-hidden">
                <div class="px-5 py-4 flex items-center gap-3 border-b border-red-100 dark:border-red-900/30">
                    <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-red-700 dark:text-red-400">Zona Berbahaya</h3>
                        <p class="text-xs text-red-500 dark:text-red-500/80">Hapus akun secara permanen</p>
                    </div>
                </div>
                <div class="p-5">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>

    <script>
        // Sync toggle switch on profile page
        function updateProfileToggle() {
            const isDark = document.documentElement.classList.contains('dark');
            const thumb = document.getElementById('toggle-thumb-profile');
            const btn = document.getElementById('theme-toggle-profile');
            if (thumb) {
                thumb.style.transform = isDark ? 'translateX(28px)' : 'translateX(2px)';
            }
            if (btn) {
                btn.style.backgroundColor = isDark ? '#4f46e5' : '#d1d5db';
            }
        }

        // Override global toggleTheme to also update profile toggle
        const _originalToggleTheme = window.toggleTheme;
        window.toggleTheme = function() {
            _originalToggleTheme();
            setTimeout(updateProfileToggle, 50);
        };

        // Init on load
        document.addEventListener('DOMContentLoaded', updateProfileToggle);
    </script>

</x-app-layout>