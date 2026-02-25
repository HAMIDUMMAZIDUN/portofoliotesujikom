<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id', 
        'pax', 
        'activity',   // 'attendance' atau 'souvenir'
        'created_at'  // WAJIB: Karena di controller kita manual set waktu (Carbon::now())
    ];

    // Relasi balik ke Guest agar bisa ambil nama tamu dari log
    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }
}