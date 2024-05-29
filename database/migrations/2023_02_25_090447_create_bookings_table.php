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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->double('total_distance')->nullable();
            $table->integer('package_id')->nullable();
            $table->integer('way_type')->comment('1 = One way, 2 = Round Trip');
            $table->integer('car_type');
            $table->double('price');
            $table->double('paid_amount')->default(0);
            $table->string('status')->nullable();
            $table->string('driver_id')->nullable();
            $table->dateTime('pickup_date_time');
            $table->dateTime('return_date_time')->nullable();
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
        Schema::dropIfExists('bookings');
    }
};
