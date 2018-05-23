<?php
/**
 * Created by PhpStorm.
 * User: fangyushuai
 * Date: 2018/5/22
 * Time: 下午2:29
 */

namespace App\Services;

use App\LotteryCoupon;
use App\LotteryPresent;
use Illuminate\Http\Request;
use App\LotteryMember;
use App\YzUidMobileMap;
use Youzan\Open\Client;
use App\Services\YouZanService;


class LotteryService
{

    public static function  saveLottery()
    {


        /*  储存 抽奖信息*/


    }

     /*face++ 人脸绑定*/
    public static function pushFacePlusPlus($mobile){
        $where = array('phone' => $mobile, 'status' => '1');
        $lotteryMember = LotteryMember::where($where)->first();
        if($lotteryMember){
            $faceId = $lotteryMember->imageID;
        }
        try      {
        $res = \App\Services\VipFaceImportService::bindVipFace($faceId, $mobile);
            return response()->json(['code' => 0, 'data' => $res]);
        }catch (\Exception $e) {
            \App\Libiary\Context\Fact\FactException::instance()->recordException($e);
            return response()->json(['code' => $e->getCode(), 'msg' => $e->getMessage()]);
        }

    }

    public static function  sendLottery($mobile)
    {
        /*返回的用户信息*/
        /*查询bannerID*/
        /*核对用户信息  如果手机号在发奖人表内 则发奖  借用status字段  如果stasus 为1 未发奖 如果status为2 已发奖*/
        /*如何区分发劵还是发奖？   根据presentID 如果是0  则发奖劵*/
        $where = array('phone' => $mobile, 'status' => '1');
        $lotteryMember = LotteryMember::where($where)->first();
        if ($lotteryMember) {
            /*查询bannerID*/
            $presentId = $lotteryMember->present_id;
            if($presentId != 0){

                $present = LotteryPresent::where('id', $presentId)->first();
                $activityId = $present->activity_id;
                $yzMember = YzUidMobileMap::where('mobile', $mobile)->first();
                /*发奖*/
                $buyerId = $yzMember->yz_uid;    //获取用户id
                /*后续修改 添加奖品id*/
                LotteryService::UmpPresentGive($buyerId,$activityId);
            }else{
                $coupon = LotteryCoupon::where('id',1)->first();
                $couponId = $coupon->coupon_id;
                LotteryService::CouponTake($mobile,$couponId);

            }
        }

    }

     /*赠送赠品*/
    public static function UmpPresentGive($buyerId, $activityId)
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.ump.present.give';
        $apiVersion = '3.0.0';

        $params = [
            'activity_id' => $activityId,
            'buyer_id' => $buyerId
//            'account' => json_encode(["account_type"=>"Mobile", "account_id"=>"18611367408"]),
//            'account' => '{"account_type":"Mobile", "account_id":"18611367408"}',
//            'customer_update' => json_encode(["contact_address"=>['address' => '北京']])
        ];

        $response = $client->get($method, $apiVersion, $params);
        $result = $response['response'];
        var_dump($result);
    }

    /*赠送奖券*/
    public static function CouponTake($mobile,$couponId){


    $accessToken = YouZanService::accessToken();
    $client = new Client($accessToken);

    $method = 'youzan.ump.coupon.take'; //要调用的api名称
    $api_version = '3.0.0'; //要调用的api版本号

    $params = [
        'mobile' => $mobile,
        'coupon_group_id' => $couponId,
    ];
    $response =  $client->post($method, $api_version, $params);
    $result = $response['response'];
    var_dump($result);

}


}