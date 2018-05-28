<?php

namespace App\Jobs;

use App\JobBuffer;
use App\Libiary\Context\Fact\FactException;
use App\LotteryMember;
use App\Services\LotteryService;
use App\Services\YouZanService;
use App\Vip;

class DuiJiangQueryForChouJIangGame extends SequenceQueueJob
{
    const INTERVAL_FIRST_QUERY = 30;

    private $mobile = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * 5分钟内十秒一次发放
     * @var array
     */
    protected $sequence = [90,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10]; //暂时不回调,开启这个时需要配合 php artsian queue:work 命令

    /**
     * @param array $sequence
     */
    public function setSequence(array $sequence)
    {
        $this->sequence = $sequence;
        return $this;
    }

    /**
     * 返回是否停止Schedule
     * @return bool
     */
    public function business()
    {
        $stop = true;

        try{
            $where = array('phone' => $this->mobile, 'status' => '1');

            $lotteryMember = LotteryMember::where($where)->first();

            if(!empty($lotteryMember)){
                LotteryService::sendLotteryByMobile($this->mobile);
            }
        }catch (\Exception $e){
            FactException::instance()->recordException($e);
            return false;
        }

        return $stop;
    }
}
