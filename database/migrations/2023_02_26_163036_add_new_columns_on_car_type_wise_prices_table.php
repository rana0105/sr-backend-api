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
            $table->string('car_type_name')->after('car_type_id')->nullable();
            $table->string('sit_capacity')->after('car_model')->nullable();
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
            $table->dropColumn('car_type_name');
            $table->dropColumn('sit_capacity');
        });
    }
};
