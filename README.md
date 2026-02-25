<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

📖 Digital Guestbook BY BIRU ID

Aplikasi Buku Tamu Digital berbasis Web yang dibangun dengan Laravel 11. Dirancang untuk mempercepat proses registrasi tamu, manajemen kuota kehadiran, hingga distribusi souvenir secara real-time menggunakan teknologi QR Code.

✨ Fitur Utama

Manajemen Tamu Terpusat: Tambah, edit, dan hapus data tamu secara manual atau massal dengan antarmuka yang bersih.

Sistem QR Code Dinamis: Generate kode QR unik berdasarkan nama tamu untuk proses check-in yang instan.

Scanner Kehadiran (Server 1): Antarmuka scanner khusus pintu masuk untuk mencatat jumlah personil (pax) yang datang secara akurat.

Pos Souvenir (Server 2): Kontrol distribusi souvenir untuk memastikan setiap tamu mendapatkan jatah yang sesuai (mencegah duplikasi).

Rekapitulasi PDF Otomatis: Download laporan kehadiran lengkap dengan statistik ringkasan dan foto wajah tamu.

Integrasi Excel: Fitur Impor dari Excel untuk data massal dan Ekspor untuk laporan administrasi.

Multi-User & Multi-Event: Data setiap user terisolasi dengan aman, memungkinkan penggunaan untuk berbagai acara berbeda.

🛠️ Teknologi yang Digunakan

Core: Laravel 11

Frontend: Tailwind CSS & Alpine.js (untuk reaktivitas modal & scanner)

Tools: Vite

Database: MySQL

Library:

barryvdh/laravel-dompdf - Pembuatan laporan PDF.

maatwebsite/excel - Pengolahan data Excel.

QR Server API - Generator gambar QR Code.

🚀 Panduan Instalasi

Pastikan komputer Anda memiliki XAMPP (PHP 8.2+), Composer, dan Node.js.

Clone Repositori

git clone [https://github.com/username/undanganpernikahan.git](https://github.com/username/undanganpernikahan.git)
cd undanganpernikahan


Instalasi Dependensi

composer install
npm install && npm run build


Konfigurasi Database

Buat database baru di phpMyAdmin (contoh: undangan_db).

Salin .env.example ke .env dan atur koneksi database Anda.

DB_DATABASE=undangan_db
DB_USERNAME=root
DB_PASSWORD=


Inisialisasi Aplikasi

php artisan key:generate
php artisan migrate


Konfigurasi PHP (Penting)
Buka php.ini dan aktifkan ekstensi berikut:

extension=gd (Untuk pemrosesan PDF & QR)

extension=zip (Untuk ekspor/impor Excel)

Jalankan Server

php artisan serve


Akses di: http://localhost:8000

📸 Tampilan Aplikasi

Scan Kehadiran

Manajemen Tamu

Laporan PDF







About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects.

👨‍💻 Kontributor

BY BIRU ID - Lead Developer - Instagram

WhatsApp: 0895-2621-6334

📄 License

The Laravel framework is open-sourced software licensed under the MIT license. Seluruh hak cipta tampilan dan desain aset "Digital Guestbook" dimiliki oleh BY BIRU ID.
