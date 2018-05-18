<?php

namespace App\Jobs;

use App\Services\ZuLinService;

class ZulinInvitationSendCouponJob extends SingleQueueJob
{
    private $phone = null;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($customerId, $phone)
    {
        $this->identity = $customerId;

        $this->phone = $phone;
    }

    /**
     * 返回是否停止Schedule
     * @return bool
     */
    public function business()
    {
//        ZuLinService::sendCoupon($this->phone, );
    }
}
