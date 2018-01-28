<?php

namespace App\Jobs;

use App\Libiary\Context\Fact\FactException;
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

    protected $sequence = [60,60,1800,1800,3600,3600,36000,72000]; //暂时不回调,开启这个时需要配合 php artsian queue:work 命令

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

        $card = YouZanService::getCustomerInfoByCardNo($this->cardNo);

        if(empty($card['mobile'])){
           $stop = false;
        }else{
            dispatch(new RecalculateVip($card['mobile']))->onConnection('sync');
        }

        return $stop;
    }
}
