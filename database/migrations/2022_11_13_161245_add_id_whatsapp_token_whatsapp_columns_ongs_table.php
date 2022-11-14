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
        Schema::table('ongs', function (Blueprint $table) {
            $table->unsignedBigInteger('id_whatsapp')->unique()->nullable();
            $table->string('token_whatsapp')->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ongs', function (Blueprint $table) {
            $table->dropColumn(['id_whatsapp', 'token_whatsapp']);
        });
    }
};
