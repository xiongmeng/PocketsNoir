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
        $this->filePath = $post;
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
            case 'TRADE_ORDER_STATE':
            case 'TRADE_ORDER_REFUND':
            case 'TRADE_ORDER_REMARK':
            case 'TRADE_ORDER_EXPRESS':
                $trade = YouZanService::tradeGet($json['id']);
                $buyerId = $trade['fans_info']['buyer_id'];

                dispatch(new DisposeChangesWithYZUid($buyerId))->onQueue('default')->onConnection('sync');
                break;
            case 'SCRM_CUSTOMER_CARD':
                $card = YouZanService::getCustomerInfoByCardNo($json['id']);

//                mobile
                break;
            case 'SCRM_CUSTOMER_EVENT':
                $msg = urldecode($json['msg']);
                $data = [];
                parse_str($msg, $data);
                if($data['account_type'] == 'YouZanAccount'){
                    dispatch(new DisposeChangesWithYZUid($data['account_id']))->onQueue('default')->onConnection('sync');
                }
                break;
            default:
                break;
        }
    }
}
