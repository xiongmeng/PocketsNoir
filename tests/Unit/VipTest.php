<?php

namespace Tests\Unit;

use App\Services\YouZanService;
use App\Vip;
use Tests\TestCase;
use Youzan\Open\Client;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAccessToken()
    {
        $accessToken = YouZanService::accessToken();

        //拿到的是一个mobile
        $mobile = '18611367408';

        $vip = Vip::find($mobile);
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
        $youZanTrades = YouZanService::getTradeListByYouZanAccountId($vip->YouZanAccount);
        foreach ($youZanTrades as $youZanTrade){
            $consumeYouZan += $youZanTrade['payment'];
        }
        //加载erp订单
        $consumeGuanJiaPo = 0;

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

        if($targetVip <> $vip->card){
            $vip->card = $targetVip;
            $vip->save();
        }

        /**
         * 同步卡到有赞
         */
        $youZanCards = YouZanService::getUserCardListByMobile($mobile);
        $cardExisted = false;
        foreach ($youZanCards as $youZanCard){
            $cardAlias = $youZanCard['card_alias'];
            if(Vip::$youZanCardMaps[$youZanCard['card_alias']] <> $targetVip){
                YouZanService::userCardDelete($mobile, $cardAlias);
            }else{
                $cardExisted = true;
            }
        }
        if(!$cardExisted){
            $cardRevertMaps = array_reverse(Vip::$youZanCardMaps);
            YouZanService::userCardGrant($mobile, $cardRevertMaps[$targetVip]);
        }

        /**
         * 管家婆的同理
         */
    }
}
