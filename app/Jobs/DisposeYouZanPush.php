<?php

namespace App\Jobs;

use App\Services\YouZanService;

class DisposeYouZanPush extends Job
{
    private $post = '';

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
        $json = json_decode($this->post, true);

        switch ($json['type']){
            case 'TRADE_ORDER_STATE':   //新版交易时间回调
            case 'TRADE':   //V1版交易回调，官方文档说在1231结束，但是到目前为止还没有，且和TRADE并不完全重复
            case 'TRADE_ORDER_REFUND':
            case 'TRADE_ORDER_REMARK':
            case 'TRADE_ORDER_EXPRESS':
                $trade = YouZanService::tradeGet($json['id']);
                $buyerId = $trade['fans_info']['buyer_id'];

                dispatch(new DisposeChangesWithYZUid($buyerId))->onQueue('default')->onConnection('sync');
                break;
            case 'SCRM_CUSTOMER_CARD':
                dispatch(new YouZanCardActivatedQuery($json['id']))->onConnection('sync');
                break;
            case 'SCRM_CUSTOMER_EVENT':
                $data = json_decode(urldecode($json['msg']), true);
                if($data['account_type'] == 'YouZanAccount'){
                    dispatch(new DisposeChangesWithYZUid($data['account_id']))->onQueue('default')->onConnection('sync');
                }
                break;
            default:
                break;
        }
    }
}
