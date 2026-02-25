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
        $table->integer('actual_pax')->default(0)->after('pax'); // Menyimpan jumlah yang sudah masuk
    });
}

public function down()
{
    Schema::table('guests', function (Blueprint $table) {
        $table->dropColumn('actual_pax');
    });
}
};
