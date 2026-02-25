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
    Schema::table('guests', function (Blueprint $table) {
        // Menambahkan kolom photo_path yang boleh kosong (nullable)
        $table->string('photo_path')->nullable()->after('server_number');
    });
}

public function down()
{
    Schema::table('guests', function (Blueprint $table) {
        $table->dropColumn('photo_path');
    });
}
};
