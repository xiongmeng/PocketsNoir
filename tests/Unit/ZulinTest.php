<?php

namespace Tests\Unit;

use App\Jobs\DisposeChangesWithYZUid;
use App\Jobs\DisposeGuanJiaPoPush;
use App\Jobs\DisposeYouZanPush;
use App\Jobs\RecalculateVip;
use App\Jobs\SyncVip;
use App\Libiary\Sign\Md5Zulin;
use App\Libiary\Utility\CurlWrapper;
use Tests\TestCase;

class ZulinTest extends TestCase
{
    public function testCardGrant()
    {
        $params = [
            'mobile' => '18611367408',
            'card' => '蓝口袋'
        ];

//        ksort($params);
//        $str = http_build_query($params);
//
//        $signBefore = $str . "&key=9MsjF78BwvZSOnbFHXCOrlxev8ASnO30";
//        $sign = strtoupper(md5($signBefore));

        $md5 = new Md5Zulin();
        $sign = $md5->sign($params, "9MsjF78BwvZSOnbFHXCOrlxev8ASnO30");
        $params['sign'] = $sign;
        $res = CurlWrapper::post($params,"https://wxapp.sylicod.com/api/v1/data-center/user/card");

    }

    public function testPointSync()
    {
        $params = [
            'mobile' => '18611367408',
            'point' => 6
        ];

//        ksort($params);
//        $str = http_build_query($params);
//
//        $signBefore = $str . "&key=9MsjF78BwvZSOnbFHXCOrlxev8ASnO30";
//        $sign = strtoupper(md5($signBefore));

        $md5 = new Md5Zulin();
        $sign = $md5->sign($params, "9MsjF78BwvZSOnbFHXCOrlxev8ASnO30");
        $params['sign'] = $sign;
        $res = CurlWrapper::post($params,"https://wxapp.sylicod.com/api/v1/data-center/user/point");

    }
}
