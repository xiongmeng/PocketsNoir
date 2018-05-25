<?php

namespace Tests\Unit;

use App\Services\YouZanService;
use App\Services\LotteryService;
use App\VipShuaFen;
use Tests\TestCase;
use Youzan\Open\Client;
use App\LotteryMember;
use App\LotteryPresent;
use App\YzUidMobileMap;

class YouZanTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAccessToken()
    {
        $accessToken1 = YouZanService::accessToken();
        $accessToken2 = YouZanService::accessToken();

        print_r([1 => $accessToken1, 2 => $accessToken2]);
    }

    public function testTradeList()
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.trades.sold.get';
        $apiVersion = '3.0.0';

        $params = [
//            'alias' => 'fa8989ad342k',
//            'buyer_id' => '737287904',
//            'type' => 'FIXED'
//            'type' => 'QRCODE'

            'buyer_id' => '742033847',
            'type' => 'QRCODE'
        ];

        $response = $client->get($method, $apiVersion, $params);
        var_dump($response);
    }

    public function testCardList()
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.scrm.card.list';
        $apiVersion = '3.0.0';

        $params = [
//            'alias' => 'fa8989ad342k',
        ];

        $response = $client->get($method, $apiVersion, $params);
        var_dump($response['response']);
    }

    public function testCardUsers()
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.scrm.customer.search';
        $apiVersion = '3.0.0';


        $cardList = YouZanService::getCardList();
        foreach ($cardList as $card) {
            $params = [
                'page' => 1,
                'card_alias' => $card['card_alias'],
            ];

            $files = [];
            $response = $client->post($method, $apiVersion, $params, $files);
            $result = $response['response'];
            print_r($result);
         }


//        var_dump($result);
//        $my_files = [
//        ];
//
//        $client = new \YZTokenClient($accessToken);
//        $result = $client->post($method, $apiVersion, $params, $my_files);
    }

    public function testCardDetail()
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.scrm.card.get';
        $apiVersion = '3.0.0';

        $cardList = YouZanService::getCardList();
        foreach ($cardList['items'] as $card) {
            $params = [
                'card_alias' => $card['card_alias'],
            ];

            $files = [];
            $response = $client->get($method, $apiVersion, $params);
            $result = $response['response'];
            var_dump($result);
        }
    }

    public function testCustomerCardList()
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.scrm.customer.card.list';
        $apiVersion = '3.0.0';

        $params = [
            'page' => 1,
            'mobile' => '15911094370',
        ];

        $response = $client->get($method, $apiVersion, $params);
        $result = $response['response'];
        var_dump($result);
    }

    public function testCustomerInfo()
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.scrm.customer.info.get';
        $apiVersion = '3.0.0';

        $params = [
            'card_no' => '233967531307746141',
        ];

        $response = $client->post($method, $apiVersion, $params);
        $result = $response['response'];
        var_dump($result);
    }

    public function testCardGrant()
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.scrm.customer.card.grant';
        $apiVersion = '3.0.0';

        $params = [
            'mobile' => '18519399410', //15911094367
            'card_alias' => '365dfnbl8ly1yD',
            'fans_type' => 1,
            'fans_id' => 0
        ];

        $response = $client->post($method, $apiVersion, $params);
        $result = $response['response'];
        var_dump($result);
    }

    public function testCardDelete()
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.scrm.customer.card.delete';
        $apiVersion = '3.0.0';

        $params = [
            'mobile' => '18519399410', //15911094367
            'card_alias' => '365dfnbl8ly1yD',
            'card_no' => '',
            'fans_type' => 1,
            'fans_id' => 0
        ];

        $response = $client->post($method, $apiVersion, $params);
        $result = $response['response'];
        var_dump($result);
    }

    public function testCardEnable()
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

//        for($index = 0 ; $index < 25; $index++){
//            $method = $index % 2 ? 'youzan.scrm.card.enable' : 'youzan.scrm.card.disable';
//
//            $apiVersion = '3.0.0';
//
//            $params = [
//                'card_alias' => '365dfnbl8ly1yD',
//            ];
//
//            $response = $client->post($method, $apiVersion, $params);
//            $result = $response['response'];
//            var_dump($result);
//        }
        $method = 'youzan.scrm.card.enable';

//        $method = 'youzan.scrm.card.disable';
        $apiVersion = '3.0.0';

        $params = [
            'card_alias' => '365dfnbl8ly1yD',
        ];

        $response = $client->post($method, $apiVersion, $params);
        $result = $response['response'];
        var_dump($result);
    }

    public function testTradeGet()
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.trade.get';
        $apiVersion = '3.0.0';

        $params = [
            'tid' => 'E20180423170104057700006',
        ];

        $response = $client->get($method, $apiVersion, $params);
        $result = $response['response'];
        var_dump($result);

//        fans_id => 2022742744
//        buyer_id => 304316094
    }

    public function testOpenidGet()
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.user.weixin.openid.get';
        $apiVersion = '3.0.0';

        $params = [
            'mobile' => '18500353096',
//            'mobile' => '13709413994',

        ];

        $response = $client->get($method, $apiVersion, $params);
        if(isset($response['response'])){
            $result = $response['response'];
            LotteryService::sendLottery("18500353096");

        }else{
            $result = $response['error_response'];
            throw new Exception($result['massage']);
        }

        var_dump($result);
    }

    public function testCustomerCreate()
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.scrm.customer.create';
        $apiVersion = '3.0.0';

        $params = [
            'mobile' => '18500300265',
            'customer_create' => json_encode(['name' => '马助']),
        ];

        $response = $client->post($method, $apiVersion, $params);
        $result = $response['response'];
        var_dump($result);
    }

    public function testCustomerGet()
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.scrm.customer.get';
        $apiVersion = '3.1.0';

        $params = [
//            'account' => json_encode(["account_type"=>"Mobile", "account_id"=>"18611367408"]),
            'account' => '{"account_type":"Mobile", "account_id":"17098931296"}',
        ];

        $response = $client->get($method, $apiVersion, $params);
        $result = $response['response'];
        var_dump($result);
    }

    public function testCustomerGetByYouZanAccount()
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.scrm.customer.get';
        $apiVersion = '3.1.0';

        $params = [
//            'account' => json_encode(["account_type"=>"Mobile", "account_id"=>"18611367408"]),
            'account' => '{"account_type":"YouZanAccount", "account_id":"725462007"}'
//            'account' => '{"account_type":"FansID", "account_id":"2022742744"}'
        ];
//        fans_id => 2022742744
//        buyer_id => 304316094

        $response = $client->get($method, $apiVersion, $params);
        $result = $response['response'];
        var_dump($result);
    }

    public function testCustomerGetByFansId()
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.scrm.customer.get';
        $apiVersion = '3.1.0';

        $params = [
//            'account' => json_encode(["account_type"=>"Mobile", "account_id"=>"18611367408"]),
            'account' => '{"account_type":"FansID", "account_id":"4874214531"}'
//            'account' => '{"account_type":"FansID", "account_id":"2022742744"}'
        ];
//        fans_id => 2022742744
//        buyer_id => 304316094

        $response = $client->get($method, $apiVersion, $params);
        $result = $response['response'];
        var_dump($result);
    }

    public function testGetCustomerGouCiByMobile()
    {
        $res = YouZanService::getCustomerGouCiByMobile('13903008198');
    }

    public function testCustomerUpdate()
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.scrm.customer.update';
        $apiVersion = '3.0.0';

        $params = [
//            'account' => json_encode(["account_type"=>"Mobile", "account_id"=>"18611367408"]),
            'account' => '{"account_type":"Mobile", "account_id":"18500300265"}',
            'customer_update' => json_encode(["contact_address"=>['address' => '广州']])
        ];

        $response = $client->get($method, $apiVersion, $params);
        $result = $response['response'];
        var_dump($result);
    }

    public function testPointsSync()
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.crm.customer.points.sync';
        $apiVersion = '3.0.0';

        $params = [
            'mobile' => '18611367408',
            'points' => 6,
            'reason' => '同步积分'
        ];

        $response = $client->post($method, $apiVersion, $params);
        $result = $response['response'];
        var_dump($result);
    }

    public function testPointsGet()
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.crm.fans.points.get';
        $apiVersion = '3.0.0';

        $params = [
            'mobile' => '18611367408'
//            'account' => json_encode(["account_type"=>"Mobile", "account_id"=>"18611367408"]),
//            'account' => '{"account_type":"Mobile", "account_id":"18611367408"}',
//            'customer_update' => json_encode(["contact_address"=>['address' => '北京']])
        ];

        $response = $client->get($method, $apiVersion, $params);
        $result = $response['response'];
        var_dump($result);
    }

    public function testEnsureCustomerExisted()
    {
        YouZanService::ensureCustomerExisted('18611367408');
    }

    public function testYouZanGouCiData()
    {
        $idx = 0;
        VipShuaFen::where('gouci', '=', 999)->chunk(100, function ($a, $b) use(&$idx){
            var_dump($idx+=100);

            /** @var VipShuaFen $vip */
            foreach ($a as $vip){
                try{
                    $res = YouZanService::getCustomerGouCiByMobile($vip->mobile);
                    $vip->gouci = $res['trade_count'];
                    !empty($res['first_time']) && $vip->firstBuyTime = $res['first_time'];
                    !empty($res['last_trade_time']) && $vip->lastestBuyTime = $res['last_trade_time'];

                    $vip->save();
                }catch (\Exception $e){
                    \Log::info("ERRORYOUZAN" . $vip->mobile);
                }
            }
        });
    }

    public function testGetTradeListByYouZanAccountId()
    {
//        $accountId = '814122446';
        $accountId = '736491603';
        $res = YouZanService::getTradeListByYouZanAccountId($accountId);
    }

    public function testUmpPresentGive()
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.ump.present.give';
        $apiVersion = '3.0.0';

        $params = [
            'activity_id' => '325577',
            'buyer_id' => '820814522'
//            'account' => json_encode(["account_type"=>"Mobile", "account_id"=>"18611367408"]),
//            'account' => '{"account_type":"Mobile", "account_id":"18611367408"}',
//            'customer_update' => json_encode(["contact_address"=>['address' => '北京']])
        ];

        $response = $client->get($method, $apiVersion, $params);
        $result = $response['response'];
        var_dump($result);
    }

    public function testCouponTake(){


        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.ump.coupon.take'; //要调用的api名称
        $api_version = '3.0.0'; //要调用的api版本号

        $params = [
            'mobile' => '18500353096',
            'coupon_group_id' => '2507415',
        ];
        $response =  $client->post($method, $api_version, $params);
        $result = $response['response'];
        var_dump($result);

    }


    public  function  testsendLottery($mobile = '18612345678')
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

    //op-3Cw_oi1zJCeUFsjuvWdgmt8Uo

    public function testUserWeixinFollower(){


        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.users.weixin.follower.get'; //要调用的api名称
        $api_version = '3.0.0'; //要调用的api版本号

        $params = [
            'weixin_openid' => 'op-3Cw_oi1zJCeUFsjuvWdgmt8Uo',
//            'coupon_group_id' => '2507415',
        ];
        $response =  $client->post($method, $api_version, $params);
        $result = $response['response'];
        var_dump($result);

//        5519138128
    }
}
