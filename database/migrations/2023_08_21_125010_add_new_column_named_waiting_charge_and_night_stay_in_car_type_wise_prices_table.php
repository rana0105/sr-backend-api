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
        Schema::table('car_type_wise_prices', function (Blueprint $table) {
            $table->integer('waiting_charge')->after('per_km_fare')->default(0);
            $table->integer('night_stay_charge')->after('waiting_charge')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_type_wise_prices', function (Blueprint $table) {
            $table->dropColumn('waiting_charge');
            $table->dropColumn('night_stay_charge');
        });
    }
};
