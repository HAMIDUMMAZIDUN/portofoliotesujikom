<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            if (!Schema::hasColumn('guests', 'pax_online')) {
                $table->integer('pax_online')->default(0)->after('pax');
            }
            if (!Schema::hasColumn('guests', 'pax_physical')) {
                $table->integer('pax_physical')->default(0)->after('pax_online');
            }
        });
    }

    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn(['pax_online', 'pax_physical']);
        });
    }
};
