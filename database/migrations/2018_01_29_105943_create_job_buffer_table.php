<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobBufferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_buffer', function (Blueprint $table) {
            $table->increments('id');
            $table->string('job_name', 128)->comment('job名称');
            $table->string('job_id', 128)->comment('job标识');
            $table->string('status', 32)->comment('状态值');
            $table->timestamps();

            $table->index('status');
            $table->index(['job_id', 'job_name']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_buffer');
    }
}
