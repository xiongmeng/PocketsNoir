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
            $table->smallInteger('manual_marked')->default(0)->comment('人工打标');
            $table->smallInteger('card')->comment('卡类型');

            $table->decimal('consumes', 10, 2)->default(0)->comment('订单消费记录');
            $table->decimal('consumes_youzan', 10, 2)->default(0)->comment('订单消费记录-有赞');
            $table->decimal('consumes_guanjiapo', 10, 2)->default(0)->comment('订单消费记录-管家婆');

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
