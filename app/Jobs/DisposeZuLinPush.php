<?php

namespace App\Jobs;

use App\Services\ZuLinService;
use App\Vip;
use App\ZuLinUser;

class DisposeZuLinPush extends Job
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

        $zuLinDb = \DB::connection('zulin');
        switch ($json['type']){
            case 'SCRM_CUSTOMER_PROFILE':
                $customer = $zuLinDb->table('User')->find($json['id']);
                if(!empty($customer) && !empty($customer->phone)){
//                    dispatch(new SingleRecalculateVip($customer->phone));
                    Vip::createFromZuLin($customer->phone);
                }
                $couponId = env('ZULIN_RECOMMEND_COUPON_KEY');
                if(!empty($couponId) && !empty($customer->registrationCode) && $json['status'] == 'REGISTER'){
//                    dispatch((new ZulinInvitationSendCouponJob($json['id'])));
                    $user = ZuLinUser::where('invitationCode', '=', $customer->registrationCode)->first();
                    if(!empty($user)){
                        ZuLinService::sendCoupon($user->phone, $couponId);
                    }
                }
                break;
            case 'TRADE_ORDER_STATE':
                $odr = $zuLinDb->table('Order')->find($json['id']);
                if(empty($odr)){
                    break;
                }
                $customer = $zuLinDb->table('User')->find($odr->customerId);
                if(!empty($customer) && !empty($customer->phone)){
                    Vip::createFromZuLin($customer->phone);
//                    dispatch(new SingleRecalculateVip($customer->phone));
                }
                break;
            default:
                break;
        }
    }
}
