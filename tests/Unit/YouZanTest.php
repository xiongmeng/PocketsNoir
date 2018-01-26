<?php

namespace Tests\Unit;

use App\Services\YouZanService;
use Tests\TestCase;
use Youzan\Open\Client;

class ExampleTest extends TestCase
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
            'buyer_id' => '719428369',
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
        foreach ($cardList['items'] as $card) {
            $params = [
                'page' => 50,
                'card_alias' => $card['card_alias'],
            ];

            $files = [];
            $response = $client->post($method, $apiVersion, $params, $files);
            $result = $response['response'];
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

    public function testUserCardList()
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.scrm.customer.card.list';
        $apiVersion = '3.0.0';

        $params = [
            'page' => 10,
            'mobile' => '18611367408',
        ];

        $response = $client->post($method, $apiVersion, $params);
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
            'card_no' => '231382285097959355',
        ];

        $response = $client->post($method, $apiVersion, $params);
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
            'account' => '{"account_type":"YouZanAccount", "account_id":"719428369"}'
//            'account' => '{"account_type":"FansID", "account_id":"2022742744"}'
        ];
//        fans_id => 2022742744
//        buyer_id => 304316094

        $response = $client->get($method, $apiVersion, $params);
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
            'tid' => 'E20180125095444070200006',
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
            'mobile' => '13709413994',
        ];

        $response = $client->get($method, $apiVersion, $params);
        $result = $response['response'];
        var_dump($result);
    }

    public function testCustomerCreate()
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.scrm.customer.create';
        $apiVersion = '3.0.0';

        $params = [
            'mobile' => '15210264742',
            'customer_create' => json_encode(['name' => '熊猛旧手机']),
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
            'account' => '{"account_type":"Mobile", "account_id":"18611367408"}',
        ];

        $response = $client->get($method, $apiVersion, $params);
        $result = $response['response'];
        var_dump($result);
    }

    public function testCustomerUpdate()
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.scrm.customer.update';
        $apiVersion = '3.0.0';

        $params = [
//            'account' => json_encode(["account_type"=>"Mobile", "account_id"=>"18611367408"]),
            'account' => '{"account_type":"Mobile", "account_id":"18611367408"}',
            'customer_update' => json_encode(["contact_address"=>['address' => '北京']])
        ];

        $response = $client->get($method, $apiVersion, $params);
        $result = $response['response'];
        var_dump($result);
    }

}
