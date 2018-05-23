<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPresentIdToLotteryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lottery', function (Blueprint $table) {
            //
            $table->integer('first_present_id')->defalut(0);
            $table->integer('second_present_id')->defalut(0);
            $table->integer('third_present_id')->defalut(0);
            $table->integer('forth_present_id')->defalut(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lottery_table', function (Blueprint $table) {
            //
        });
    }
}
