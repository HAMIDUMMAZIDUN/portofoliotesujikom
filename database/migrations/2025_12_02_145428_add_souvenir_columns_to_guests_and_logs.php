<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambahkan kolom 'souvenir_pax' ke tabel 'guests'
        Schema::table('guests', function (Blueprint $table) {
            if (!Schema::hasColumn('guests', 'souvenir_pax')) {
                $table->integer('souvenir_pax')->default(0)->after('actual_pax');
            }
        });

        // 2. Tambahkan kolom 'activity' ke tabel 'guest_logs'
        Schema::table('guest_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('guest_logs', 'activity')) {
                // activity akan diisi 'attendance' atau 'souvenir'
                $table->string('activity')->default('attendance')->after('pax');
            }
        });
    }

    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            if (Schema::hasColumn('guests', 'souvenir_pax')) {
                $table->dropColumn('souvenir_pax');
            }
        });

        Schema::table('guest_logs', function (Blueprint $table) {
            if (Schema::hasColumn('guest_logs', 'activity')) {
                $table->dropColumn('activity');
            }
        });
    }
};