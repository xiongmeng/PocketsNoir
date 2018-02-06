<?php

namespace Tests\Unit;

use App\Libiary\Utility\CurlWrapper;
use App\Services\GuanJiaPoService;
use Tests\TestCase;

class GuanJiaPoTest extends TestCase
{
    public function testRetailBillByVip()
    {
//        $sercet = "Grasp010-00333";
//        $mobile = '15911094367';
//        $date = date("Ymd");
//        $md5Before = "{$sercet}{$mobile}{$date}";
//        $md5 = strtoupper(md5($md5Before));
//
//        $res = CurlWrapper::get(['mobile' => $mobile, 'MD5' => $md5],
//            "http://120.76.188.76:82/tdy/RetailBillByVip/RetailBillByVip");
//
//        print_r($res);

        $res = GuanJiaPoService::getLingShouDanByMobile('15210264742');
        print_r($res);
    }

    public function testVipAuthorizationUpGrade()
    {
//        $sercet = "Grasp010-00334";
//        $mobile = '15911094367';
//        $card_name = '金卡';
//        $date = date("Ymd");
//        $md5 = strtoupper(md5("{$sercet}{$mobile}{$card_name}{$date}"));
//
//        $res = CurlWrapper::get(['mobile' => $mobile, 'MD5' => $md5, 'card_name' => $card_name],
//            "http://120.76.188.76:82/tdy/VipAuthorization/VipAuthorization");
//
//        print_r($res);

        $res = GuanJiaPoService::grantVip('15911094367', '金卡');
        print_r($res);
    }

    public function testVipAuthorizationUnExist()
    {
        $sercet = "Grasp010-00334";
        $mobile = '18611367408';
        $card_name = '普卡';
        $date = date("Ymd");
        $md5 = strtoupper(md5("{$sercet}{$mobile}{$card_name}{$date}"));

        $res = CurlWrapper::get(['mobile' => $mobile, 'MD5' => $md5, 'card_name' => $card_name],
            "http://120.76.188.76:82/tdy/VipAuthorization/VipAuthorization");

        print_r($res);
    }
}
