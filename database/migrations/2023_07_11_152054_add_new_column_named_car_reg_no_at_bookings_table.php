<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('driver_name')->after('driver_phone')->nullable();
            $table->string('driver_email')->after('driver_name')->nullable();
            $table->string('car_reg_no')->after('driver_email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('driver_name');
            $table->dropColumn('driver_email');
            $table->dropColumn('car_reg_no');
        });
    }
};
