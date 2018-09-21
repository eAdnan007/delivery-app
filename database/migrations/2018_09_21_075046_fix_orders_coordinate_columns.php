<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixOrdersCoordinateColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
	        $table->decimal('origin_lat', 10, 7)->default(0)->change();
	        $table->decimal('origin_lon', 10, 7)->default(0)->change();
	        $table->decimal('destination_lat', 10, 7)->default(0)->change();
	        $table->decimal('destination_lon', 10, 7)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
	        $table->decimal('origin_lat', 10, 7)->default(null)->change();
	        $table->decimal('origin_lon', 10, 7)->default(null)->change();
	        $table->decimal('destination_lat', 10, 7)->default(null)->change();
	        $table->decimal('destination_lon', 10, 7)->default(null)->change();
        });
    }
}
