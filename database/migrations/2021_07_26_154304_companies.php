<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Companies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('siret')->nullable();
            $table->string('vat_number')->nullable();
            $table->boolean('vat_applicable')->nullable();
            $table->integer('type');
            $table->integer('activity');
            $table->string('address');
            $table->integer('zip');
            $table->string('city');
            $table->string('social_network')->nullable();
            $table->integer('status_id')->nullable()->default(1);
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
        Schema::dropIfExists('companies');
    }
}
