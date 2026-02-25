<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek dulu apakah kolom 'event_date' sudah ada
            if (!Schema::hasColumn('users', 'event_date')) {
                $table->string('event_date')->nullable()->after('email');
            }
            
            // Lakukan hal yang sama untuk kolom lain di file ini (misal: event_location)
            if (!Schema::hasColumn('users', 'event_location')) {
                $table->string('event_location')->nullable()->after('event_date');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom saat rollback
            $table->dropColumn(['event_date', 'event_location']);
        });
    }
};
