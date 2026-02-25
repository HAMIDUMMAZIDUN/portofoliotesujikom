<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom khusus untuk menghitung souvenir
        Schema::table('guests', function (Blueprint $table) {
            if (!Schema::hasColumn('guests', 'souvenir_pax')) {
                $table->integer('souvenir_pax')->default(0)->after('actual_pax');
            }
        });

        // 2. Tambah kolom penanda aktivitas di log (agar riwayat tidak tertukar)
        Schema::table('guest_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('guest_logs', 'activity')) {
                $table->string('activity')->default('attendance')->after('pax'); // Values: 'attendance' or 'souvenir'
            }
        });
    }

    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn('souvenir_pax');
        });
        Schema::table('guest_logs', function (Blueprint $table) {
            $table->dropColumn('activity');
        });
    }
};