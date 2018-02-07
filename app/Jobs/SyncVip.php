<?php

namespace App\Jobs;

use App\Libiary\Context\Fact\FactException;
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
        $youZanCards = [];
        try{
//            补货发卡异常
            $youZanCards = YouZanService::getUserCardListByMobile($mobile);
        }catch (\Exception $e){
            if($e->getCode() <> '141500101' AND $e->getMessage() <> 'invalid params'){
                FactException::instance()->recordException($e);
            }
        }
        $targetCardAlias = Vip::$youZanCardMaps[$vip->card];
        $cardExisted = false;
        foreach($youZanCards as $youZanCard){
            $cardAlias = $youZanCard['card_alias'];
            if($cardAlias <> $targetCardAlias){
                if(Vip::isYouZanCardOver($cardAlias, $vip->card)){
                    YouZanService::userCardDelete($mobile, $cardAlias);
                }
            }else{
                $cardExisted = true;
            }
        }
//        只有卡号不为空
        if(!$cardExisted && $vip->card <> Vip::CARD_1){
            YouZanService::userCardGrant($mobile, $targetCardAlias);
        }

        /**
         * 同步到管家婆
         */
        GuanJiaPoService::grantVip($mobile, Vip::$GuanJiaPoCardMaps[$vip->card]);
    }
}
