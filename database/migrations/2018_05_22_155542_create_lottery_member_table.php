<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLotteryMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('lottery_member', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id');
            $table->integer('present_id');
//            $table->integer('prize_type');    //奖品
//            $table->string('prize_name');
            $table->string('imageID');
            $table->string('phone');
            $table->string('member_name');
            $table->tinyInteger('status');
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
        Schema::table('lottery_member', function (Blueprint $table) {
            //
        });
    }
}
