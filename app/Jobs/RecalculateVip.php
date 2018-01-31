<?php

namespace App\Jobs;

use App\Libiary\Context\Fact\FactException;
use App\Services\GuanJiaPoService;
use App\Services\YouZanService;
use App\Vip;
use App\YzUidMobileMap;

class RecalculateVip extends Job
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
            $res = $vip->save();
            \Log::info("DEBUG_RECALCULATE_" . 'Save1', ['res' => $res, 'vip' => $vip->toArray()]);

            $vip = Vip::find($this->mobile);
        }

        /**
         * 加载两边订单
         */
        //加载有赞订单
        $consumeYouZan = 0;
        /** @var YzUidMobileMap $map */
        $map = YzUidMobileMap::whereMobile($mobile)->first();
        if(!empty($map)){
            $youZanTrades = YouZanService::getTradeListByYouZanAccountId($map->yz_uid);
            foreach ($youZanTrades as $youZanTrade){
                \Log::info("DEBUG_RECALCULATE_" . "GotTrade", $youZanTrade);
                \Log::info("DEBUG_RECALCULATE_" . "GotTradeStatus:" . $youZanTrade['status']);
                \Log::info("DEBUG_RECALCULATE_" . "GotTradePayment:" . $youZanTrade['payment']);

                if($youZanTrade['status'] == 'TRADE_BUYER_SIGNED'){
                    \Log::info("DEBUG_RECALCULATE_" . "GotTradeAddMoneyBefore:" . $consumeYouZan);
                    $consumeYouZan += $youZanTrade['payment'];
                    \Log::info("DEBUG_RECALCULATE_" . "GotTradeAddMoneyAfter:" . $consumeYouZan);
                }
            }
        }
        //加载erp订单
        $consumeGuanJiaPo = 0;
        $gjpTrades = GuanJiaPoService::getLingShouDanByMobile($mobile);
        foreach ($gjpTrades as $gjpTrade){
            $consumeGuanJiaPo += $gjpTrade['payment'];
        }

        \Log::info("DEBUG_RECALCULATE_" . "SumMoneyBefore:" ,['consumeYouZan' => $consumeYouZan, 'consumeGuanJiaPo' => $consumeGuanJiaPo]);

        $consume = $consumeYouZan + $consumeGuanJiaPo;
        \Log::info("DEBUG_RECALCULATE_" . "SumMoneyBefore:" ,['consume' => $consume, 'consumeYouZan' => $consumeYouZan, 'consumeGuanJiaPo' => $consumeGuanJiaPo]);

        $targetVip = Vip::CARD_1;
        if($consume >= 1500){
//        if($consume > 0){
            $targetVip = Vip::CARD_2;
        }elseif ($consume >= 5000){
            $targetVip = Vip::CARD_3;
        }elseif ($consume >= 50000){
            $targetVip = Vip::CARD_4;
        }elseif ($consume >= 100000){
            $targetVip = Vip::CARD_5;
        }
        \Log::info("DEBUG_RECALCULATE_" . "Calc1:" ,['targetVip' => $targetVip, 'consume' => $consume,
            'consumeYouZan' => $consumeYouZan, 'consumeGuanJiaPo' => $consumeGuanJiaPo,
            'vip_card' => $vip->card, 'vip_manual_marked' => $vip->manual_marked
        ]);

//        如果目标会员卡低于当前卡级别，而且此卡是人工设定（众筹员工奖励），则不降级
        if($targetVip < $vip->card && $vip->manual_marked){
            $targetVip = $vip->card;
        }

        \Log::info("DEBUG_RECALCULATE_" . "Calc2:" ,['targetVip' => $targetVip, 'consume' => $consume, 'consumeYouZan' => $consumeYouZan, 'consumeGuanJiaPo' => $consumeGuanJiaPo, 'vip_card' => $vip->card, 'vip_manual_marked' => $vip->manual_marked]);

        $vip->consumes = $consume;
        $vip->consumes_youzan = $consumeYouZan;
        $vip->consumes_guanjiapo = $consumeGuanJiaPo;
        if($targetVip <> $vip->card){
            $vip->card = $targetVip;
        }
        \Log::info("DEBUG_RECALCULATE_" . "Calc3:" ,['targetVip' => $targetVip, 'consume' => $consume, 'consumeYouZan' => $consumeYouZan, 'consumeGuanJiaPo' => $consumeGuanJiaPo, 'vip_card' => $vip->card, 'vip_manual_marked' => $vip->manual_marked]);

        $res = $vip->save();

        \Log::info("DEBUG_RECALCULATE_" . 'Save2', ['res' => $res, 'vip' => $vip->toArray()]);

        /**
         * 同步卡
         */
        dispatch(new SyncVip($mobile))->onConnection('sync');
    }
}
