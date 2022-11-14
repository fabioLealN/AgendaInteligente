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
        if (Schema::hasColumn('schedules', 'interval_time')) {
            Schema::table('schedules', function($table) {
                $table->dropColumn('interval_time');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('schedules', 'interval_time')) {
            Schema::table('schedules', function($table) {
                $table->time('interval_time', 0);
            });
        }
    }
};
