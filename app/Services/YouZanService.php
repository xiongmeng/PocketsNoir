<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Youzan\Open\Client;

class YouZanService
{

    public static function accessToken()
    {
        $key = 'YOUZAN_SELF_ACCESS_TOKEN';
        if(\Cache::has($key)){
            return \Cache::get($key);
        }else{
            $clientId = env('YOUZAN_CLIENT_ID');
            $clientSecret = env('YOUZAN_CLIENT_SECRET');

            $type = 'self';
            $keys['kdt_id'] = env('YOUZAN_KDT_ID');

            $accessToken = (new \Youzan\Open\Token($clientId, $clientSecret))->getToken($type, $keys);

            \Cache::add($key, $accessToken['access_token'], $accessToken['expires_in'] / 60);

            return $accessToken['access_token'];
        }
    }

    public static function getCardList()
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.scrm.card.list';
        $apiVersion = '3.0.0';

        $params = [
//            'alias' => 'fa8989ad342k',
        ];

        $response = $client->get($method, $apiVersion, $params);
        return $response['response'];
    }

    public static function tradeGet($tid)
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.trade.get';
        $apiVersion = '3.0.0';

        $params = [
            'tid' => $tid,
        ];

        $response = $client->get($method, $apiVersion, $params);
        return $response['response'];
    }

    public static function getCustomerByYouZanAccount($accountId)
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.scrm.customer.get';
        $apiVersion = '3.1.0';

        $params = [
            'account' => json_encode(['account_type' => 'YouZanAccount', 'account_id' => $accountId])
        ];

        $response = $client->get($method, $apiVersion, $params);
        return $response['response'];
    }

    public static function getUserCardListByMobile($mobile)
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.scrm.customer.card.list';
        $apiVersion = '3.0.0';

        $params = [
            'page' => 10,
            'mobile' => $mobile,
        ];

        $response = $client->post($method, $apiVersion, $params);
        $result = $response['response'];
        return $result;
    }

    /**
     * 获取有赞AccountId
     */
    public static function getTradeListByYouZanAccountId($accountId)
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.trades.sold.get';
        $apiVersion = '3.0.0';

        $params = [
            'buyer_id' => $accountId,
            'start_created' => '2018-01-28',
            'end_created' => date("Y-m-d 23:59:59")
        ];

        $response = $client->get($method, $apiVersion, $params);

        return $response['result']['trades'];
    }

    public static function userCardGrant($mobile, $cardAlias)
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.scrm.customer.card.grant';
        $apiVersion = '3.0.0';

        $params = [
            'mobile' => $mobile, //15911094367
            'card_alias' => $cardAlias,
            'fans_type' => 1,
            'fans_id' => 0
        ];

        $response = $client->post($method, $apiVersion, $params);
        $result = $response['response'];
        var_dump($result);
    }

    public static function userCardDelete($mobile, $cardAlias)
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.scrm.customer.card.delete';
        $apiVersion = '3.0.0';

        $params = [
            'mobile' => $mobile,
            'card_alias' => $cardAlias,
            'card_no' => '',
            'fans_type' => 1,
            'fans_id' => 0
        ];

        $response = $client->post($method, $apiVersion, $params);
        $result = $response['response'];
        var_dump($result);
    }

    public static function getCustomerInfoByCardNo($cardNo)
    {
        $accessToken = YouZanService::accessToken();
        $client = new Client($accessToken);

        $method = 'youzan.scrm.customer.info.get';
        $apiVersion = '3.0.0';

        $params = [
            'card_no' => $cardNo,
        ];

        $response = $client->post($method, $apiVersion, $params);
        return $response['response'];
    }
}
