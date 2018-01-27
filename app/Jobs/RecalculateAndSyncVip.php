<?php

namespace App\Jobs;

use App\Libiary\Context\Fact\FactException;
use App\Services\GuanJiaPoService;
use App\Services\YouZanService;
use App\Vip;
use App\YzUidMobileMap;

class RecalculateAndSyncVip extends Job
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
        $mobile = $this->mobile;
        $vip = Vip::find($this->mobile);
        if(empty($vip)){
            $vip = new Vip();
            $vip->mobile = $mobile;
            $vip->card = Vip::CARD_1;
            $vip->save();
        }

        /**
         * 加载两边订单
         */
        //加载有赞订单
        $consumeYouZan = 0;
        /** @var YzUidMobileMap $map */
        $map = YzUidMobileMap::whereMobile($mobile)->get();
        if(!empty($map)){
            $youZanTrades = YouZanService::getTradeListByYouZanAccountId($map->yz_uid);
            foreach ($youZanTrades as $youZanTrade){
                $consumeYouZan += $youZanTrade['payment'];
            }
        }
        //加载erp订单
        $consumeGuanJiaPo = 0;
        $gjpTrades = GuanJiaPoService::getLingShouDanByMobile($mobile);
        foreach ($gjpTrades as $gjpTrade){
            $consumeYouZan += $gjpTrade['payment'];
        }

        $consume = $consumeYouZan + $consumeGuanJiaPo;
        $targetVip = Vip::CARD_1;
        if($consume < 1500){
            $targetVip = Vip::CARD_2;
        }elseif ($consume < 5000){
            $targetVip = Vip::CARD_3;
        }elseif ($consume < 50000){
            $targetVip = Vip::CARD_4;
        }elseif ($consume < 100000){
            $targetVip = Vip::CARD_5;
        }
        if($targetVip < $vip->card && $vip->manual_marked){
            $targetVip = $vip->card;
        }

        $vip->consumes = $consume;
        $vip->consumes_youzan = $consumeYouZan;
        $vip->consumes_guanjiapo = $consumeGuanJiaPo;
        if($targetVip <> $vip->card){
            $vip->card = $targetVip;
        }
        $vip->save();

        /**
         * 同步卡到有赞
         */
        $youZanCards = YouZanService::getUserCardListByMobile($mobile);
        $targetCardAlias = Vip::$youZanCardMaps[$targetVip];
        $cardExisted = false;
        foreach ($youZanCards as $youZanCard){
            $cardAlias = $youZanCard['card_alias'];
            if($cardAlias <> $targetCardAlias){
                YouZanService::userCardDelete($mobile, $cardAlias);
            }else{
                $cardExisted = true;
            }
        }
        if(!$cardExisted){
            YouZanService::userCardGrant($mobile, $targetCardAlias);
        }

        /**
         * 同步到管家婆
         */
        GuanJiaPoService::grantVip($mobile, Vip::$GuanJiaPoCardMaps[$targetVip]);
    }
}
