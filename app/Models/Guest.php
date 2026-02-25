<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    /**
     * $fillable menentukan kolom mana saja yang boleh diisi secara massal.
     * Pastikan 'user_id' dan 'souvenir_pax' ada di sini.
     */
    protected $fillable = [
        'user_id',            
        'name',
        'pax',
        'pax_online',         
        'pax_physical',       
        'actual_pax',         
        'souvenir_pax',       
        'is_online_invited',
        'is_physical_invited',
        'server_number',
        'check_in_at',
        'photo_path',         // Untuk upload foto tamu
    ];

    // Cast tipe data agar otomatis dikonversi oleh Laravel
    protected $casts = [
        'check_in_at' => 'datetime',
        'is_online_invited' => 'boolean',
        'is_physical_invited' => 'boolean',
        'pax_online' => 'integer',
        'pax_physical' => 'integer',
    ];

    /**
     * Relasi: Setiap data Tamu dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}