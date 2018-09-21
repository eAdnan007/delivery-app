<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrdersCoordinateColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('origin_lat', 10, 7)->after('distance');
            $table->decimal('origin_lon', 10, 7)->after('origin_lat');
            $table->decimal('destination_lat', 10, 7)->after('origin_lon');
            $table->decimal('destination_lon', 10, 7)->after('destination_lat');
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
	        $table->dropColumn('origin_lat');
			$table->dropColumn('origin_lon');
			$table->dropColumn('destination_lat');
			$table->dropColumn('destination_lon');
        });
    }
}
