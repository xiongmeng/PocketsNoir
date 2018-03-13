<?php

namespace App\Jobs;

abstract class SingleQueueJob extends Job
{
    protected $identity = null;

    /**
     * @var int 以分钟为单位
     */
    protected $pendInterval = 1;

    /**
     * 处理business
     * @return mixed
     */
    abstract function business();

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(is_null($this->identity)){
            throw new \Exception("identity can't be null");
        }
        if(!is_int($this->pendInterval)){
            throw new \Exception("pendInterval must be interval");
        }

        $class = get_class($this);
        $key = "single_{$class}_{$this->identity}";
        $state = \Cache::get($key);

        \Log::info("{$key} Start, State: {$state}");
        if(is_null($state)){
            \Cache::put($key, 'running', 6000);

            \Log::info("{$key} running");

            try{
                $this->business();
            }catch (\Exception $e){
                throw $e;
            }finally{
                \Cache::put($key, 'sleeping', $this->pendInterval);
                \Log::info("{$key} sleeping");
            }

        }else if($state == 'running' || $state == 'sleeping'){
            \Log::info("{$key} pending");
            \Cache::put($key, 'pending', $this->pendInterval);

            /**
             * clone 当前job并且设置job为null
             */
            $tmp = $this->job;
            $this->job = null;
            $job = clone $this;
            $this->job = $tmp;

            /**
             * 设置延时和connection
             */
            $job->delay($this->pendInterval * 60 + 2);

            /**
             * 重新分发job
             */
            dispatch($job);
        }else{
            \Log::info("{$key} doNothingAsPended");
        }
        \Log::info("{$key} End");
    }
}
