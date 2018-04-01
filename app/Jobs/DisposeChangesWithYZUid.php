<?php

namespace App\Jobs;

use App\JobBuffer;
use App\Services\YouZanService;
use App\Vip;
use App\YzUidMobileMap;

class DisposeChangesWithYZUid extends Job
{
    private $yzUid = '';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($yzUid)
    {
        $this->yzUid = $yzUid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $yzUid = $this->yzUid;
        $customer = YouZanService::getCustomerByYouZanAccount($yzUid);
        if(!empty($customer['mobile'])){
            $map = YzUidMobileMap::find($yzUid);
            if(empty($map)){
                $map = new YzUidMobileMap();
                $map->yz_uid = $yzUid;
                $map->mobile = $customer['mobile'];
                $map->save();
            }else if($map->mobile <> $customer['mobile']){
                $map->mobile_last = $map->mobile;
                $map->mobile = $customer['mobile'];
                $map->save();
            }

            Vip::createNormal($map->mobile);
            if(!empty($map->mobile_last) && $map->mobile_last <> $map->mobile){
                Vip::createNormal($map->mobile_last);
            }

//            dispatch(new SingleRecalculateVip($map->mobile));
//            if(!empty($map->mobile_last) && $map->mobile_last <> $map->mobile){
//                dispatch(new SingleRecalculateVip($map->mobile_last));
//            }
        }
    }
}
