<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('guest_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('guest_id')->constrained()->onDelete('cascade');
        $table->integer('pax'); // Jumlah orang yang masuk pada jam ini
        $table->timestamps(); // created_at adalah waktu scan
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_logs');
    }
};
