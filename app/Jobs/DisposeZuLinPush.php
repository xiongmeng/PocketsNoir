<?php

namespace App\Jobs;

use App\Vip;

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
                    dispatch(new SingleRecalculateVip($customer->phone));
                }
                break;
            case 'TRADE_ORDER_STATE':
                $odr = $zuLinDb->table('Order')->find($json['id']);
                if(empty($odr)){
                    break;
                }
                $customer = $zuLinDb->table('User')->find($odr->customerId);
                if(!empty($customer) && !empty($customer->phone)){
                    dispatch(new SingleRecalculateVip($customer->phone));
                }
                break;
            default:
                break;
        }
    }
}
