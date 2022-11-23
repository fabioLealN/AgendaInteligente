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
        Schema::create('user_speciality_ongs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_speciality_id');
            $table->unsignedBigInteger('ong_id');
            $table->timestamps();

            $table->foreign('user_speciality_id')->references('id')->on('user_specialities')->onDelete('cascade');
            $table->foreign('ong_id')->references('id')->on('ongs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_speciality_ongs');
    }
};
