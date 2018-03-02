<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnumVipLevelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enum_vip_level', function (Blueprint $table) {
            $table->integer('card');
            $table->string('name');
            $table->primary('card');
        });

        DB::table('enum_vip_level')->insert(['card' => 1, 'name'=>'青口袋']);
        DB::table('enum_vip_level')->insert(['card' => 2, 'name'=>'蓝口袋']);
        DB::table('enum_vip_level')->insert(['card' => 3, 'name'=>'银口袋']);
        DB::table('enum_vip_level')->insert(['card' => 4, 'name'=>'灰口袋']);
        DB::table('enum_vip_level')->insert(['card' => 5, 'name'=>'黑口袋']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enum_vip_level');
    }
}
