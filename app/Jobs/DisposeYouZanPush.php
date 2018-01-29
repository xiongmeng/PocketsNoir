<?php

namespace App\Jobs;

use App\JobBuffer;
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
            case 'TRADE_ORDER_REFUND':
            case 'TRADE_ORDER_REMARK':
            case 'TRADE_ORDER_EXPRESS':
                $trade = YouZanService::tradeGet($json['id']);
                JobBuffer::addYouZanParseUid($trade['fans_info']['buyer_id']);
                break;
            case 'TRADE':   //V1版交易回调，官方文档说在1231结束，但是到目前为止还没有，且和TRADE并不完全重复
                $data = json_decode(urldecode($json['msg']), true);
                JobBuffer::addYouZanParseUid($data['trade']['fans_info']['buyer_id']);
                break;
            case 'SCRM_CUSTOMER_CARD':
                JobBuffer::addYouZanCardActivatedQuery($json['id']);
                break;
            case 'SCRM_CUSTOMER_EVENT':
                $data = json_decode(urldecode($json['msg']), true);
                if($data['account_type'] == 'YouZanAccount'){
                    JobBuffer::addYouZanParseUid($data['account_id']);
                }
                break;
            default:
                break;
        }
    }
}
