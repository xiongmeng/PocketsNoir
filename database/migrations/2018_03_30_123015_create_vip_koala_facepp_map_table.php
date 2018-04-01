<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVipKoalaFaceppMapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vip_koala_facepp_map', function (Blueprint $table) {
            $table->string('mobile', 32)->comment('手机号-会员唯一约束');
            $table->string('koala_id', 64)->comment('在KoalaService中的用户id');
            $table->string('face_url', 1024)->default('')->comment('图片路径');
            $table->string('koala_photo_id', 32)->default('')->comment('人脸id');
            $table->string('face_token', 64)->default('')->comment('在face++中人脸token');
            $table->string('faceset_outer_id', 64)->default('')->comment('存储在face++中faceset的outer_id');

            $table->timestamps();

            $table->primary('mobile');
            $table->unique('face_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vip_koala_facepp_map');
    }
}
