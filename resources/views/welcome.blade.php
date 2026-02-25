<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>The Wedding of Us</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Montserrat:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* === VARIABEL WARNA & KONFIGURASI === */
        :root {
            --primary-color: #2c2c2c;       /* Abu tua gelap untuk teks utama */
            --accent-color: #bfa37c;        /* Warna Emas/Krem tua untuk tombol & highlight */
            --bg-color: #fcfbf9;            /* Putih gading/hangat */
            --white: #ffffff;
            --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        /* === RESET & BASE === */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--bg-color);
            color: var(--primary-color);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        a { text-decoration: none; color: inherit; transition: var(--transition); }
        ul { list-style: none; }

        /* === ANIMASI FADE IN === */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-in {
            animation: fadeInUp 1s ease forwards;
            opacity: 0; /* Mulai hidden */
        }
        .delay-1 { animation-delay: 0.2s; }
        .delay-2 { animation-delay: 0.4s; }
        .delay-3 { animation-delay: 0.6s; }

        /* === LAYOUT UTAMA === */
        .main-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
            position: relative;
        }

        /* === HEADER / NAVIGASI === */
        .global-header {
            position: absolute;
            top: 0; left: 0; width: 100%;
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 4%;
            z-index: 100;
            /* Efek Kaca (Glassmorphism) halus */
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(5px); 
        }

        .logo-text {
            font-family: 'Great Vibes', cursive;
            font-size: 1.8rem;
            color: var(--primary-color);
        }

        .nav-links {
            display: flex;
            gap: 35px;
        }

        .nav-links a {
            font-size: 0.75rem;
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
            position: relative;
            color: var(--primary-color);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0; height: 1px;
            bottom: -5px; left: 0;
            background-color: var(--accent-color);
            transition: var(--transition);
        }
        .nav-links a:hover { color: var(--accent-color); }
        .nav-links a:hover::after { width: 100%; }

        /* Tombol Login Styling Baru */
        .btn-login-outline {
            padding: 10px 28px;
            border: 1px solid var(--primary-color);
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            background: transparent;
        }
        .btn-login-outline:hover {
            background-color: var(--primary-color);
            color: var(--white);
            border-color: var(--primary-color);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* === BAGIAN KIRI (KONTEN) === */
        .left-section {
            width: 50%;
            display: flex;
            align-items: center;
            padding: 0 8%;
            background-color: var(--bg-color);
            position: relative;
            z-index: 10;
        }

        .content-box { max-width: 550px; }

        .pre-title {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-size: 1.1rem;
            color: var(--accent-color);
            margin-bottom: 10px;
            display: block;
        }

        .main-title {
            font-family: 'Playfair Display', serif;
            font-size: 5rem;
            line-height: 1;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 25px;
            letter-spacing: -1px;
        }

        .main-title span {
            font-family: 'Great Vibes', cursive;
            font-weight: 400;
            color: var(--accent-color);
            font-size: 0.7em; /* Sedikit lebih kecil relatif thd judul */
            display: block;
            margin-top: -15px;
            margin-bottom: 10px;
        }

        .description {
            font-size: 0.9rem;
            line-height: 1.8;
            color: #666;
            margin-bottom: 40px;
            font-weight: 300;
            max-width: 80%;
        }

        .action-area {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 15px 40px;
            border-radius: 0; /* Kotak agar lebih elegan/modern */
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        .btn-primary:hover {
            background-color: var(--accent-color);
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(191, 163, 124, 0.4);
        }

        /* Social Icons Minimalis */
        .socials { display: flex; gap: 20px; }
        .socials a {
            color: #999;
            font-size: 1.1rem;
        }
        .socials a:hover { color: var(--accent-color); }

        /* === BAGIAN KANAN (GAMBAR & CURVE) === */
        .right-section {
            width: 50%;
            position: relative;
            background-image: url("{{ asset('img/bgpernikahan.jpg') }}");
            background-size: cover;
            background-position: center;
        }

        /* Overlay Gradien Halus pada Gambar agar teks nav terbaca jika layar kecil */
        .right-section::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(to right, rgba(0,0,0,0.2), transparent);
            z-index: 1;
        }

        /* SVG Curve Divider yang Lebih Halus */
        .curve-container {
            position: absolute;
            top: 0; bottom: 0; left: -1px;
            width: 150px;
            height: 100%;
            z-index: 5;
            pointer-events: none;
        }
        .curve-svg {
            height: 100%;
            width: 100%;
            fill: var(--bg-color);
        }

        /* === DATE BADGE (Optional Modern Touch) === */
        .date-badge {
            position: absolute;
            bottom: 40px; right: 40px;
            background: rgba(255,255,255,0.9);
            padding: 20px;
            text-align: center;
            z-index: 20;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            min-width: 120px;
        }
        .date-badge h3 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            line-height: 1;
            margin-bottom: 5px;
        }
        .date-badge p {
            font-size: 0.7rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--accent-color);
            margin: 0;
        }

        /* === RESPONSIVE TABLET & MOBILE === */
        @media (max-width: 1024px) {
            .global-header { padding: 0 20px; }
            .nav-links { display: none; /* Sembunyikan menu di tablet, bisa diganti hamburger menu */ }
            .left-section { width: 60%; }
            .right-section { width: 40%; }
            .main-title { font-size: 3.5rem; }
        }

        @media (max-width: 768px) {
            .main-wrapper { flex-direction: column; }
            
            .global-header {
                position: relative;
                height: 70px;
                background: var(--bg-color);
                justify-content: space-between;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            }
            
            .nav-links { display: none; } /* Simpelkan untuk HP */
            
            .left-section {
                width: 100%;
                padding: 60px 30px;
                order: 2;
                text-align: center;
            }
            .content-box { margin: 0 auto; }
            .main-title { font-size: 3rem; }
            .action-area { justify-content: center; flex-direction: column; }
            
            .right-section {
                width: 100%;
                height: 350px;
                order: 1;
            }
            
            /* Putar SVG untuk Mobile agar di bawah gambar */
            .curve-container {
                width: 100%; height: 80px;
                top: auto; bottom: -1px; left: 0;
                transform: rotate(0deg);
            }
            .curve-svg { transform: scaleY(-1); /* Balik agar melengkung ke atas */ }

            .date-badge { bottom: 20px; right: 20px; padding: 10px; min-width: 100px; }
            .date-badge h3 { font-size: 1.5rem; }
        }
    </style>
</head>
<body>

    <div class="main-wrapper">

        <header class="global-header animate-in">
            <div class="logo-text">Us.</div> <nav class="nav-links">
                <a href="#">The Couple</a>
                <a href="#">Events</a>
                <a href="#">Gallery</a>
                <a href="#">RSVP</a>
            </nav>

            <div class="auth-container">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-login-outline">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-login-outline">Guest Login</a>
                    @endauth
                @endif
            </div>
        </header>
        
        <section class="left-section">
            <div class="content-box">
                <span class="pre-title animate-in delay-1">Save The Date</span>
                
                <h1 class="main-title animate-in delay-1">
                    Romeo <br> <span>&</span> Juliet
                </h1>
                
                <p class="description animate-in delay-2">
                    Kami mengundang Anda untuk merayakan momen spesial pernikahan kami. 
                    Bergabunglah bersama kami dalam kebahagiaan dan cinta.
                </p>

                <div class="action-area animate-in delay-3">
                    <a href="#" class="btn-primary">Open Invitation</a>
                    
                    <div class="socials">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="far fa-envelope"></i></a>
                    </div>
                </div>
            </div>
        </section>

        <section class="right-section animate-in">
            <div class="date-badge animate-in delay-3">
                <h3>24</h3>
                <p>Aug 2025</p>
            </div>

            <div class="curve-container">
                <svg class="curve-svg" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0,0 L0,100 C50,100 20,0 100,0 Z" fill="currentColor"/>
                </svg>
            </div>
        </section>

    </div>

</body>
</html>