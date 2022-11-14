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
        Schema::create('ongs_specialities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ong_id');
            $table->unsignedBigInteger('speciality_id');

            $table->foreign('ong_id')->references('id')->on('ongs');
            $table->foreign('speciality_id')->references('id')->on('specialities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ongs_specialities');
    }
};
