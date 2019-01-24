<?php

namespace App\Providers;

use App\Libiary\Context\Fact\FactLaravelQueue;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * 监听队列执行失败事件
         */
        \Queue::failing(function(JobFailed $event){
            FactLaravelQueue::instance()->recordFailed($event);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
