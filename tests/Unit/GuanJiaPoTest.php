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

    /**
     * 修改会员积分接口
     */
    public function testModifyIntegral()
    {
        $sercet = "Grasp010-00335";
        $mobile = '18611367408';
        $integral = '100';
        $date = date("Ymd");
        $signature = strtoupper(md5("{$sercet}{$mobile}{$integral}{$date}"));

        $res = CurlWrapper::get(['vipcardcode' => $mobile, 'signature' => $signature, 'integral' => $integral],
            "http://120.76.188.76:82/tdy/Integral/ModifyIntegral");

        print_r($res);
    }

//    17687917185

    public function testRefundRBbyVip()
    {
        $sercet = "Grasp010-00333";
        $mobile = '15911094367';
        $date = date("Ymd");
        $signature = strtoupper(md5("{$sercet}{$mobile}{$date}"));

        $res = CurlWrapper::get(['mobile' => $mobile, 'signature' => $signature],
            "http://120.76.188.76:82/tdy/RefundRBbyVip/RefundRBbyVip");

        print_r($res);
    }

}
