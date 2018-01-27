<?php

namespace App\Jobs;

use App\Libiary\Context\Fact\FactSequenceQueueCompleted;

abstract class SequenceQueueJob extends Job
{
    /**
     * @var int 当前位移
     */
    protected $offset = 0;

    /**
     * @var array
     */
    protected $sequence = [];

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
        $res = $this->business();
        if($res){
            $this->stop();
        }else{
            if($this->offset >= count($this->sequence)){
                $this->complete();
            }else{
                /**
                 * 获取下一个
                 */
                $next = $this->sequence[$this->offset];
                $this->offset ++ ;

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
                $job->delay($next)->onConnection('database');

                /**
                 * 重新分发job
                 */
                dispatch($job);
            }
        }
    }

    /**
     * 停止函数
     */
    protected function stop()
    {

    }

    /**
     * 结束函数
     */
    protected function complete()
    {
        FactSequenceQueueCompleted::instance()->recordJob($this->job);
    }
}
