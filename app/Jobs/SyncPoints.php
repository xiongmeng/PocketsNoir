<?php

namespace App\Jobs;

use App\Libiary\Context\Fact\FactException;
use App\Services\GuanJiaPoService;
use App\Services\YouZanService;
use App\Services\ZuLinService;
use App\Vip;

class SyncPoints extends Job
{
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

    public function handle()
    {
        /** @var Vip $vip */
        $vip = Vip::find($this->mobile);
        $mobile = $vip->mobile;

        $points = 6 + round($vip->consumes);

        try{
            YouZanService::userPointsSync($mobile, $points);
        }catch (\Exception $e){
            FactException::instance()->recordException($e);
        }

        try{
            GuanJiaPoService::syncPoints($mobile, $points);
        }catch (\Exception $e){
            FactException::instance()->recordException($e);
        }

        try{
            ZuLinService::syncPoints($mobile, $points);
        }catch (\Exception $e){
            FactException::instance()->recordException($e);
        }
    }
}
