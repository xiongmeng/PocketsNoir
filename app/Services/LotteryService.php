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
use Cache;


class LotteryService
{


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
            $lotteryMember->status = 2;
            $lotteryMember->save();
            return response()->json("领奖成功！");
        }else{
            throw new \Exception('未找到中奖信息！');
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

    /*赠送赠品*/
    public static function UmpPresentGiveByFansId($fansId, $activityId)
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.ump.present.give';
        $apiVersion = '3.0.0';

        $params = [
            'activity_id' => $activityId,
            'fans_id' => $fansId,
//            'account' => json_encode(["account_type"=>"Mobile", "account_id"=>"18611367408"]),
//            'account' => '{"account_type":"Mobile", "account_id":"18611367408"}',
//            'customer_update' => json_encode(["contact_address"=>['address' => '北京']])
        ];

        $response = $client->get($method, $apiVersion, $params);
        $result = $response['response'];
        return $result;
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
    return $result;

}
/*根据用户手机号 查询是否注册   如果注册 调用发奖*/
    public static function OpenidGet($mobile)
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);
        $method = 'youzan.user.weixin.openid.get';
        $apiVersion = '3.0.0';
        $params = [
            'mobile' => $mobile,
        ];
        $response = $client->get($method, $apiVersion, $params);
        if(isset($response['response'])){
//            $result = $response['response'];
//            LotteryService::sendLottery($mobile);
              $openid = $response['response']['open_id'];
              return $openid;
//

        }else{
//            $result = $response['error_response'];
//            throw new \Exception($result['msg']);
             return false;
        }
    }

    public static function UserWeixinFollower($openid){


        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.users.weixin.follower.get'; //要调用的api名称
        $api_version = '3.0.0'; //要调用的api版本号

        $params = [
            'weixin_openid' => $openid,
//            'weixin_openid' => 'op-3Cw_oi1zJCeUFsjuvWdgmt8Uo',
//            'coupon_group_id' => '2507415',
        ];
        $response =  $client->post($method, $api_version, $params);
        $fansId = $response['response']['user']['user_id'];  //粉丝id
        return $fansId;

//        5519138128
    }


    public static function sendLotteryByMobile($mobile)
    {
        $openId = LotteryService::OpenidGet($mobile);
        if($openId){
            $fansId = LotteryService::UserWeixinFollower($openId);
            LotteryService::sendLotteryByFansId($fansId,$mobile);
        }else{
            self::sendLottery($mobile);
        }



    }

    public static function  sendLotteryByFansId($fansId,$mobile)
    {
        $where = array('phone' => $mobile, 'status' => '1');
        $lotteryMember = LotteryMember::where($where)->first();

        if ($lotteryMember) {
            /*查询bannerID*/
            $presentId = $lotteryMember->present_id;
            if($presentId != 0){
                $present = LotteryPresent::where('id', $presentId)->first();
                $activityId = $present->activity_id;

                /*后续修改 添加奖品id*/
                $result = LotteryService::UmpPresentGiveByFansId($fansId,$activityId);
            }else{
                $coupon = LotteryCoupon::where('id',1)->first();
                $couponId = $coupon->coupon_id;
                $result = LotteryService::CouponTake($mobile,$couponId);

            }
            $lotteryMember->status = 2;   // 重复领奖的开关
            $lotteryMember->save();
        }else{
            return response()->json("未找到中奖信息！");
        }

    }

    //验证手机号
    public static function checkIn($code,$mobile){

        if (empty($code)) {
            throw new \Exception("短信验证码不能为空!");
        }
        if (empty($mobile)) {
            throw new \Exception("必须传入手机号!");
        }

        $cacheKey = "vip_mobile_code_$mobile";
        $codeExpected = Cache::get($cacheKey);
        if (empty($codeExpected)) {
            throw new \Exception("短信验证码不存在或已过期，请重新获取！");
        }
        if ($codeExpected <> $code) {
            throw new \Exception("验证码输入错误！");
        }


    }


    
    


}