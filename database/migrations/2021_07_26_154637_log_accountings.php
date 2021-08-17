<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LogAccountings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_accountings', function (Blueprint $table) {
            $table->id();
            $table->integer('action');
            $table->integer('type');
            $table->integer('reference_id');
            $table->integer('reference_number');
            $table->integer('author_id');
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
        Schema::dropIfExists('log_accountings');
    }
}
