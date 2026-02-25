<?php

namespace App\Imports;

use App\Models\Guest;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GuestsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // LOGIKA PINTAR: Mencari berbagai kemungkinan nama kolom.
        // Catatan: Nama kolom dari file Export adalah 'Nama_Tamu', 'Jumlah_Pax', 'Undangan_Online_(No._Urut)', 'Undangan_Fisik_(No._Urut)'
        // Laravel Excel akan mengkonversi header ini menjadi slug/snake_case.
        
        $name = $row['nama_tamu'] ?? $row['name'] ?? $row['nama'] ?? null;

        if (!$name) {
            return null;
        }

        $pax = $row['jumlah_pax'] ?? $row['pax'] ?? 1;
        
        // Cek Kolom Undangan Online (dari export: Undangan_Online_(No._Urut))
        // Jika nilainya angka > 0, dianggap TRUE.
        $online_header = 'undangan_online_no_urut';
        $online_value = $row[$online_header] ?? false;
        $is_online = (int)$online_value > 0;
        
        // Cek Kolom Undangan Fisik (dari export: Undangan_Fisik_(No._Urut))
        $fisik_header = 'undangan_fisik_no_urut';
        $fisik_value = $row[$fisik_header] ?? false;
        $is_fisik = (int)$fisik_value > 0;


        // SIMPAN KE DATABASE
        return new Guest([
            'user_id'             => Auth::id(), // ID Pemilik Akun (Multi-User)
            'name'                => $name, 
            'pax'                 => $pax,
            'pax_online'          => $is_online ? (int)$online_value : 0,
            'pax_physical'        => $is_fisik  ? (int)$fisik_value  : 0,
            'actual_pax'          => 0, // Default 0 (belum hadir)
            'souvenir_pax'        => 0, // Default 0 (belum ambil)
            'is_online_invited'   => $is_online,
            'is_physical_invited' => $is_fisik,
        ]);
    }
}