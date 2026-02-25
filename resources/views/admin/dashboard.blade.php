<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">{{ __('Admin Dashboard') }}</h2>
    </x-slot>

    <div class="min-h-screen font-sans">

        {{-- HERO --}}
        <div class="relative overflow-hidden rounded-2xl mb-6" style="background:linear-gradient(135deg,#1e1b4b,#4338ca,#6366f1)">
            <div class="absolute inset-0 opacity-10" style="background-image:radial-gradient(circle at 20% 50%,white 1px,transparent 1px);background-size:40px 40px"></div>
            <div class="relative px-5 py-6 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <span class="text-white/80 text-xs font-semibold uppercase tracking-widest">Admin Panel</span>
                    </div>
                    <h1 class="text-2xl font-black text-white">MANAJEMEN PENGGUNA</h1>
                    <p class="text-indigo-200 text-sm font-medium mt-0.5">Kelola akun Admin dan Petugas sistem</p>
                </div>
                <div class="hidden sm:flex w-16 h-16 rounded-2xl bg-white/15 border border-white/20 items-center justify-center">
                    <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
        </div>

        {{-- FLASH --}}
        @if(session('success'))
        <div class="mb-4 flex items-center gap-3 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-700 text-emerald-800 dark:text-emerald-300 px-4 py-3 rounded-xl" x-data x-init="setTimeout(()=>$el.remove(),5000)">
            <div class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></div>
            <p class="text-sm font-semibold">{{ session('success') }}</p>
        </div>
        @endif
        @if(session('error'))
        <div class="mb-4 flex items-center gap-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-300 px-4 py-3 rounded-xl">
            <div class="w-7 h-7 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0"><svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg></div>
            <p class="text-sm font-semibold">{{ session('error') }}</p>
        </div>
        @endif

        {{-- TABLE SECTION --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="px-5 py-4 flex items-center justify-between border-b border-gray-100 dark:border-gray-700" style="background:linear-gradient(135deg,#1e1b4b,#4338ca)">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/></svg></div>
                    <div>
                        <h2 class="text-white font-bold text-sm uppercase tracking-wide">Daftar Akun Pengguna</h2>
                        <p class="text-indigo-200 text-xs">Kelola Admin dan Petugas</p>
                    </div>
                </div>
                <button x-data="" x-on:click.prevent="$dispatch('open-modal','create-user-modal')"
                        class="flex items-center gap-1.5 px-4 py-2 bg-white/20 hover:bg-white/30 text-white text-xs font-bold rounded-xl border border-white/30 transition backdrop-blur-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Buat Akun
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                            <th class="py-3 px-5 text-left text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Nama / Akun</th>
                            <th class="py-3 px-4 text-left text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="py-3 px-4 text-center text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="py-3 px-4 text-center text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($users as $user)
                        <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-900/10 transition-colors">
                            <td class="py-3.5 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center text-white font-black text-lg uppercase shadow-sm"
                                         style="background:linear-gradient(135deg,#4338ca,#6366f1)">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">Bergabung {{ $user->created_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4"><p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p></td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $user->role === 'admin' ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800' : 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus akun {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.');" class="inline-block">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/50 border border-red-200 dark:border-red-800 text-xs font-bold rounded-lg transition active:scale-95">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                @else
                                    <div class="flex items-center justify-center gap-1.5">
                                        <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                                        <span class="text-xs text-gray-400 dark:text-gray-500 italic font-medium">Akun Anda</span>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MODAL BUAT AKUN --}}
        <x-modal name="create-user-modal" :show="$errors->isNotEmpty()" focusable>
            <div class="p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:linear-gradient(135deg,#1e1b4b,#6366f1)">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900 dark:text-white uppercase">Buat Akun Baru</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Tambah Admin atau Petugas baru</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <x-input-label for="name" value="Nama Lengkap"/>
                            <x-text-input id="name" class="block mt-1 w-full bg-white dark:bg-gray-700 text-black dark:text-white" type="text" name="name" :value="old('name')" required autofocus/>
                            <x-input-error :messages="$errors->get('name')" class="mt-1"/>
                        </div>
                        <div>
                            <x-input-label for="email" value="Email"/>
                            <x-text-input id="email" class="block mt-1 w-full bg-white dark:bg-gray-700 text-black dark:text-white" type="email" name="email" :value="old('email')" required/>
                            <x-input-error :messages="$errors->get('email')" class="mt-1"/>
                        </div>
                        <div>
                            <x-input-label for="role" value="Role"/>
                            <select id="role" name="role" class="block mt-1 w-full border border-gray-300 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 rounded-xl shadow-sm text-gray-900 dark:text-white bg-white dark:bg-gray-700 px-4 py-2.5 text-sm">
                                <option value="user">User (Petugas)</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="password" value="Password"/>
                            <div class="relative mt-1">
                                <x-text-input id="password" class="block w-full bg-white dark:bg-gray-700 text-black dark:text-white pr-10" type="password" name="password" required/>
                                <button type="button" onclick="togglePassword('password','eyeIcon1','eyeOffIcon1')" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                    <svg id="eyeIcon1" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg id="eyeOffIcon1" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 012.197-3.608M6.47 6.47A9.97 9.97 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-1.88 3.18M6.47 6.47L3 3m3.47 3.47l11.06 11.06M3 3l18 18"/></svg>
                                </button>
                            </div>
                        </div>
                        <div>
                            <x-input-label for="password_confirmation" value="Konfirmasi Password"/>
                            <div class="relative mt-1">
                                <x-text-input id="password_confirmation" class="block w-full bg-white dark:bg-gray-700 text-black dark:text-white pr-10" type="password" name="password_confirmation" required/>
                                <button type="button" onclick="togglePassword('password_confirmation','eyeIcon2','eyeOffIcon2')" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                    <svg id="eyeIcon2" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg id="eyeOffIcon2" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 012.197-3.608M6.47 6.47A9.97 9.97 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-1.88 3.18M6.47 6.47L3 3m3.47 3.47l11.06 11.06M3 3l18 18"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-2">
                        <button type="submit" class="flex-1 text-white font-bold py-3.5 rounded-xl text-sm uppercase transition active:scale-95" style="background:linear-gradient(135deg,#1e1b4b,#6366f1)">Simpan Akun</button>
                        <button type="button" x-on:click="$dispatch('close')" class="flex-1 border-2 border-gray-200 dark:border-gray-600 font-bold uppercase rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm py-3.5 transition">Batal</button>
                    </div>
                </form>
            </div>
        </x-modal>

    </div>

    <script>
        function togglePassword(inputId, eyeId, eyeOffId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(eyeId);
            const eyeOff = document.getElementById(eyeOffId);
            if (input.type === 'password') { input.type = 'text'; eye.classList.add('hidden'); eyeOff.classList.remove('hidden'); }
            else { input.type = 'password'; eye.classList.remove('hidden'); eyeOff.classList.add('hidden'); }
        }
    </script>

</x-app-layout>
