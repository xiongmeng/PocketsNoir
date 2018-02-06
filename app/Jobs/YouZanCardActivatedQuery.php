<?php

namespace App\Jobs;

use App\JobBuffer;
use App\Services\YouZanService;

class YouZanCardActivatedQuery extends SequenceQueueJob
{
    const INTERVAL_FIRST_QUERY = 30;

    private $cardNo = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($cardNo)
    {
        $this->cardNo = $cardNo;
    }

    protected $sequence = [5,10,10,10,10,10,10,30,60,60,1800,1800,3600,3600,36000,72000]; //暂时不回调,开启这个时需要配合 php artsian queue:work 命令

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
            $card = YouZanService::getCustomerInfoByCardNo($this->cardNo);

            if(empty($card['mobile'])){
                $stop = false;
            }else{
                JobBuffer::addRecalculateVip($card['mobile']);
            }
        }catch (\Exception $e){
//            错误 141502107 为卡不存在（此种情况为卡已删除）
            if($e->getCode() <> '141502107'){
                throw $e;
            }
        }

        return $stop;
    }
}
