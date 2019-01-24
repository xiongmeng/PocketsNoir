<?php

namespace App\Providers;

use App\Libiary\Context\Fact\FactLaravelQueue;
use Illuminate\Queue\Events\JobProcessed;
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
         * 监听队列执行完毕事件
         */
        \Queue::after(function(JobProcessed $event){
            FactLaravelQueue::instance()->recordAfter($event);
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
