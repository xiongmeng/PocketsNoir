<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYzUidMobileMapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yz_uid_mobile_map', function (Blueprint $table) {
            $table->string('yz_uid', 32)->comment('有赞的uid对应YouZanAccount，交易中的fans_info中buyer_id');
            $table->string('mobile_last', 32)->default('')->comment('上一次记录的手机号');
            $table->string('mobile', 32)->comment('最新一次的手机号');

            $table->timestamps();

            $table->primary('yz_uid');
            $table->unique('mobile');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yz_uid_mobile_map');
    }
}
