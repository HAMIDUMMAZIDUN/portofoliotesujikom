<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key check sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // =====================
        // AKUN ADMIN
        // =====================
        User::create([
            'name'           => 'Admin Sistem',
            'email'          => 'admin@wedding.com',
            'password'       => Hash::make('admin123'),
            'role'           => 'admin',
            'event_date'     => null,
            'event_location' => null,
        ]);

        // =====================
        // AKUN USER (Mempelai)
        // =====================
        User::create([
            'name'           => 'Mempelai (User)',
            'email'          => 'user@wedding.com',
            'password'       => Hash::make('user123'),
            'role'           => 'user',
            'event_date'     => '2025-11-01',
            'event_location' => 'Gedung Serbaguna, Jakarta',
        ]);
    }
}