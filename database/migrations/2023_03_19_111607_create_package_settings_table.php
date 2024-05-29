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
        Schema::create('package_settings', function (Blueprint $table) {
            $table->id();
            $table->string('from_destination');
            $table->text('from_dest_place_id');
            $table->string('to_destination');
            $table->string('to_dest_place_id');
            $table->string('status')->default('Active');
            $table->integer('trip_type')->default(1);
            $table->text('image')->nullable();
            $table->text('vehicle_type')->nullable();
            $table->integer('starting_price')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_settings');
    }
};
