<?php

use App\Models\Pet;
use App\Models\Schedule;
use App\Models\TypeScheduling;
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
        Schema::create('schedulings', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->unsignedBigInteger('pet_id');
            $table->unsignedBigInteger('schedule_id');
            $table->unsignedBigInteger('ong_id');
            $table->unsignedBigInteger('type_scheduling_id');
            $table->timestamps();

            $table->foreign('ong_id')->references('id')->on('ongs');
            $table->foreign('pet_id')->references('id')->on('pets');
            $table->foreign('schedule_id')->references('id')->on('schedules');
            $table->foreign('type_scheduling_id')->references('id')->on('types_schedulings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedulings');
    }
};
