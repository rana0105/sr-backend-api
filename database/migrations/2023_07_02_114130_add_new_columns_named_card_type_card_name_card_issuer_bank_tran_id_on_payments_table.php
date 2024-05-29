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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('card_type')->after('payment_type')->nullable();
            $table->string('card_no')->after('card_type')->nullable();
            $table->string('card_issuer')->after('card_no')->nullable();
            $table->string('bank_tran_id')->after('card_issuer')->nullable();
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
            $table->dropColumn('card_type');
            $table->dropColumn('card_no');
            $table->dropColumn('card_issuer');
            $table->dropColumn('bank_tran_id');
        });
    }
};
