<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vip', function (Blueprint $table) {
            $table->string('mobile', 32)->comment('手机号-会员唯一约束');
            $table->smallInteger('gender')->comment('性别。0:未知；1:男；2:女');
            $table->string('birthday', 32)->comment('生日');
            $table->smallInteger('manual_marked')->comment('人工打标');
            $table->smallInteger('card')->comment('卡类型');
            $table->string('card_no_youzan', 64)->comment('有赞的carId');
            $table->string('card_no_guanjiapo', 64)->comment('管家婆的carId');
            $table->string('YouZanAccount', 32)->comment('有赞AccountId');

            $table->decimal('consumes')->comment('订单消费记录');
            $table->decimal('consumes_youzan')->comment('订单消费记录-有赞');
            $table->decimal('consumes_guanjiapo')->comment('订单消费记录-管家婆');

            $table->timestamps();

            $table->primary('mobile');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vip');
    }
}
