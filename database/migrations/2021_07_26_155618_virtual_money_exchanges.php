<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VirtualMoneyExchanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virtual_money_exchanges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('virtual_money_id')->references('id')->on('virtual_moneys');
            $table->boolean('type');
            $table->float('amount');
            $table->string('info')->nullable();
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
        Schema::dropIfExists('virtual_money_exchanges');
    }
}
