<?php

namespace Tests\Unit;

use App\Jobs\DisposeChangesWithYZUid;
use App\Jobs\DisposeGuanJiaPoPush;
use App\Jobs\DisposeYouZanPush;
use App\Jobs\DisposeZuLinPush;
use App\Jobs\RecalculateVip;
use App\Jobs\SyncVip;
use App\Libiary\Sign\Md5Zulin;
use App\Libiary\Utility\CurlWrapper;
use App\Services\ZuLinService;
use Tests\TestCase;

class ZulinTest extends TestCase
{
    public function testCardGrant()
    {
        ZuLinService::grantVip('18611367408', '蓝口袋');
    }

    public function testPointSync()
    {
        ZuLinService::syncPoints('18611367408', 6);
    }

    public function testSendCoupon()
    {
        ZuLinService::sendCoupon('18611367408', 11);
    }

    public function testRefreshZuLin()
    {
        $max = 61;

        for ($index = 0; $index<$max; $index++){
            CurlWrapper::post([
                'id' => $index,
                'type' => 'SCRM_CUSTOMER_PROFILE',
                'status' => 'REGISTER'
            ], 'http://dc.sylicod.com/zulin/push');
        }
    }
}
