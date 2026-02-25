<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Tamu
            $table->integer('pax')->default(1); // Jumlah Tamu (Orang)
            
            // Status Undangan (Checklist di Halaman List Tamu)
            $table->boolean('is_online_invited')->default(false);
            $table->boolean('is_physical_invited')->default(false);
            
            // Server (1 atau 2) untuk pengelompokan
            $table->integer('server_number')->nullable(); 
            
            // Waktu Masuk (Jika null = belum hadir, Jika terisi = hadir)
            $table->timestamp('check_in_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};