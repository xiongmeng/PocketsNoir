<?php

namespace App\Jobs;

use App\Vip;

class DisposeGuanJiaPoPush extends Job
{
    private $post = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($post)
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $json = $this->post;

        switch ($json['type']){
            case 'TRADE_ORDER_STATE':
                if(!empty($json['buyer_mobile'] && $json['buyer_mobile'] <> 'null')){
                    dispatch(new SingleRecalculateVip($json['buyer_mobile']));
                }
                break;
            case 'SCRM_CUSTOMER_CARD':
                if(!empty($json['id'])){
                    $mobile = $json['id'];
                    $card = $json['status'];

                    $revert = array_flip(Vip::$GuanJiaPoCardMaps);
                    if(empty($revert[$card])){
                        throw new \Exception("卡类型在管家婆不存在：{$json['status']}");
                    }

                    $vip = Vip::find($mobile);
                    if(empty($vip) || $vip->card <> $revert[$card]){
                        throw new \Exception("管家婆添加过卡且卡等级和数据中心不一致：{$mobile}");
                    }
                }
                break;
            default:
                break;
        }
    }
}
