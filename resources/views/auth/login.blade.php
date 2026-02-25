<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - By Biru ID</title>
    <link rel="icon" href="{{ asset('img/bybiru.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body, html {
            height: 100%;
            width: 100%;
            overflow: hidden;
            font-family: 'Montserrat', sans-serif;
        }

        body {

            background-image: url("https://www.labuanbajoproductions.com/wp-content/uploads/2025/03/Pose-Foto-Pengantin-Bergandengan-Tangan-Labuan-Bajo-Productioss.webp");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .font-script { font-family: 'Great Vibes', cursive; }
        .font-sans-serif { font-family: 'Montserrat', sans-serif; }
        .glass-shadow {
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body class="antialiased">
    <div class="fixed inset-0 bg-black/30 z-0"></div>

    <div class="relative z-10 flex flex-col justify-center items-center h-full w-full">
        
        <div class="w-full sm:max-w-md px-8 py-10 sm:rounded-[30px] relative overflow-hidden glass-shadow
                    bg-white/20              
                    backdrop-blur-md">
            
            <div class="absolute -top-10 -left-10 w-32 h-32 bg-white/20 rounded-full blur-xl pointer-events-none"></div>

            <div class="text-center mb-8 relative">
                <img src="{{ asset('img/bybiru.png') }}" alt="Logo" class="w-12 h-12 mx-auto mb-2 rounded-full border-2 border-white/50 shadow-sm">   
                <h2 class="font-script text-5xl text-white drop-shadow-md mb-1">Selamat Datang</h2>
                <p class="font-sans-serif text-[10px] uppercase tracking-[4px] text-white/90 font-bold">Digital Guestbook By Biru ID</p>
            </div>
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-5">
                    <label for="email" class="block font-sans-serif text-[11px] font-bold uppercase tracking-wider text-white/90 mb-2 ml-1">Email</label>
                    <input id="email" 
                           class="block w-full rounded-xl px-4 py-3 text-sm transition-all font-sans-serif 
                                  bg-white/60 placeholder-gray-600 text-gray-900 shadow-inner
                                  border-transparent focus:border-[#15803d] focus:ring-[#15803d] focus:bg-white/90" 
                           type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@gmail.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mb-6">
                    <label for="password" class="block font-sans-serif text-[11px] font-bold uppercase tracking-wider text-white/90 mb-2 ml-1">Password</label>
                    
                    <div class="relative">
                        <input id="password" 
                               class="block w-full rounded-xl pl-4 pr-10 py-3 text-sm transition-all font-sans-serif 
                                      bg-white/60 placeholder-gray-600 text-gray-900 shadow-inner
                                      border-transparent focus:border-[#15803d] focus:ring-[#15803d] focus:bg-white/90"
                               type="password"
                               name="password"
                               required autocomplete="current-password" 
                               placeholder="••••••••" />
                        
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 hover:text-[#15803d] focus:outline-none transition-colors">
                            <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            
                            <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 hidden">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <button type="submit" class="w-full bg-[#15803d] hover:bg-[#14532d] text-white font-sans-serif font-bold text-xs uppercase tracking-[2px] py-4 rounded-full shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                    {{ __('Masuk') }}
                </button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
</body>
</html>