<?php

namespace App\Jobs;

use App\JobBuffer;
use App\Services\YouZanService;
use App\Vip;

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
        if(empty($json['type'])){
            return;
        }

        switch ($json['type']){
            case 'TRADE_ORDER_STATE':   //新版交易时间回调
            case 'TRADE_ORDER_REFUND':
            case 'TRADE_ORDER_REMARK':
            case 'TRADE_ORDER_EXPRESS':
                $trade = YouZanService::tradeGet($json['id']);
//                JobBuffer::addYouZanParseUid($trade['fans_info']['buyer_id']);
                dispatch(new DisposeChangesWithYZUid($trade['fans_info']['buyer_id']));
                break;
            case 'TRADE':   //V1版交易回调，官方文档说在1231结束，但是到目前为止还没有，且和TRADE并不完全重复
                $data = json_decode(urldecode($json['msg']), true);
//                JobBuffer::addYouZanParseUid($data['trade']['fans_info']['buyer_id']);
                dispatch(new DisposeChangesWithYZUid($data['trade']['fans_info']['buyer_id']));
                break;
            case 'POINTS':
                $data = json_decode(urldecode($json['msg']), true);
                if(!empty($data['mobile'])){
                    dispatch(new SingleRecalculateVip($data['mobile']));
                }
                break;
            case 'SCRM_CUSTOMER_CARD':
                //如果是删除卡或者是发卡事件（目前已知渠道为程序自己发的）则不处理
                if(in_array($json['status'], ['CUSTOMER_CARD_DELETED' , 'CUSTOMER_CARD_GIVEN'])){
                    break;
                }
                $data = json_decode(urldecode($json['msg']), true);

                //如果是用户领卡，则马上判断是否符合规则，不符合规则则删除。防止勿发卡。
                if(!empty($data['mobile']) && in_array($json['status'], ['CUSTOMER_CARD_TAKEN'])){
                    $vip = Vip::find($data['mobile']);
                    if(Vip::isYouZanCardOver($data['card_alias'], $vip ? $vip->card : Vip::CARD_1)){
                        \Log::info("DeleteCardBecauseTakenOver", $data);
                        YouZanService::userCardDelete($data['mobile'], $data['card_alias']);
                    }
                }else if(in_array($json['status'], ['CUSTOMER_CARD_TAKEN'])){
//                    如果是领卡了，则立即启动卡的查询
                    dispatch(new YouZanCardActivatedQuery($json['id']));
                }else{
//                    JobBuffer::addYouZanCardActivatedQuery($json['id']);
                    dispatch(new YouZanCardActivatedQuery($json['id']));
                }

                break;
            case 'SCRM_CUSTOMER_EVENT':
                $data = json_decode(urldecode($json['msg']), true);
                if($data['account_type'] == 'YouZanAccount'){
//                    JobBuffer::addYouZanParseUid($data['account_id']);
                    if($json['status'] == 'CUSTOMER_UPDATED'){
                        dispatch(new DisposeChangesWithYZUid($data['account_id']));
                    }else{
                        JobBuffer::addYouZanParseUid($data['account_id']);
                    }
                }
                break;
            default:
                break;
        }
    }
}
