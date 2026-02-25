<?php

namespace App\Exports;

use App\Models\Guest;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GuestsExport implements FromCollection, WithHeadings, WithMapping
{
    // Variabel untuk penomoran urut (counter)
    private $onlineCount = 0;
    private $physicalCount = 0;

    public function collection()
    {
        // Ambil data milik user yang login
        return Guest::where('user_id', Auth::id())->get();
    }

    // Memetakan data baris per baris untuk logika penomoran
    public function map($guest): array
    {
        // Jika diundang online, naikkan counter dan tampilkan angkanya
        $onlineValue = $guest->is_online_invited ? ++$this->onlineCount : '';

        // Jika diundang fisik, naikkan counter dan tampilkan angkanya
        $physicalValue = $guest->is_physical_invited ? ++$this->physicalCount : '';

        return [
            $guest->name,
            $guest->pax,
            $onlineValue,   // Hasil: 1, 2, 3... atau kosong
            $physicalValue, // Hasil: 1, 2, 3... atau kosong
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Tamu', 
            'Jumlah Pax', 
            'Undangan Online (No. Urut)', 
            'Undangan Fisik (No. Urut)'
        ];
    }
}