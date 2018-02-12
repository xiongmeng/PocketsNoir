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
                    dispatch(new RecalculateVip($json['buyer_mobile']))->onConnection('database');
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
                    if(empty($vip)){
                        $vip = new Vip();
                        $vip->mobile = $mobile;
                        $vip->card = $revert[$card];
                        $vip->manual_marked = Vip::MANUAL_MARK_GUANJIAPO;
                        $vip->save();

                        dispatch(new RecalculateVip($mobile))->onConnection('database');
                    }else{
                        throw new \Exception("卡在数据中心已经存在：{$mobile}");
                    }
                }
                break;
            default:
                break;
        }
    }
}
