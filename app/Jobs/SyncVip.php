<?php

namespace App\Jobs;

use App\Services\GuanJiaPoService;
use App\Services\YouZanService;
use App\Vip;

class SyncVip extends Job
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

        /**
         * 同步卡到有赞
         */
        $youZanCards = YouZanService::getUserCardListByMobile($mobile);
        $targetCardAlias = Vip::$youZanCardMaps[$vip->card];
        $cardExisted = false;
        foreach ($youZanCards as $youZanCard){
            $cardAlias = $youZanCard['card_alias'];
            if($cardAlias <> $targetCardAlias){
                $mobile== '18611367408' && YouZanService::userCardDelete($mobile, $cardAlias);
            }else{
                $cardExisted = true;
            }
        }
        if(!$cardExisted){
            $mobile== '18611367408' && YouZanService::userCardGrant($mobile, $targetCardAlias);
        }

        /**
         * 同步到管家婆
         */
        GuanJiaPoService::grantVip($mobile, Vip::$GuanJiaPoCardMaps[$vip->card]);
    }
}
