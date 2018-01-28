<?php

namespace App\Services;

use App\Services\YouZanClient as Client;

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
        $response = (new Client(YouZanService::accessToken()))->get(
            'youzan.scrm.card.list', '3.0.0', []);
        return $response['response'];
    }

    /**
     * 获取单个交易
     * @param $tid
     * @return mixed
     */
    public static function tradeGet($tid)
    {
        $response = (new Client(YouZanService::accessToken()))
            ->get('youzan.trade.get', '3.0.0', ['tid' => $tid]);
        return $response['trade'];
    }

    public static function getCustomerByYouZanAccount($accountId)
    {
        $response = (new Client(YouZanService::accessToken()))->get(
            'youzan.scrm.customer.get', '3.1.0', [
            'account' => json_encode(['account_type' => 'YouZanAccount', 'account_id' => $accountId])
        ]);
        return $response;
    }

    public static function getUserCardListByMobile($mobile)
    {
        $response = (new Client(YouZanService::accessToken()))
            ->post('youzan.scrm.customer.card.list', '3.0.0', [
            'page' => 1,
            'mobile' => $mobile,
        ]);
        return !empty($response['items']) ? $response['items'] : [];
    }

    /**
     * 获取有赞AccountId
     */
    public static function getTradeListByYouZanAccountId($accountId)
    {
        $response = (new Client(YouZanService::accessToken()))->get(
            'youzan.trades.sold.get', '3.0.0', [
                'buyer_id' => $accountId,
                'start_created' => '2018-01-26',
                'end_created' => date("Y-m-d 23:59:59"),
                'status' => 'TRADE_BUYER_SIGNED'
            ]);

        return $response['trades'];
    }

    public static function userCardGrant($mobile, $cardAlias)
    {
        $response = (new Client(YouZanService::accessToken()))->post(
            'youzan.scrm.customer.card.grant', '3.0.0', [
            'mobile' => $mobile, //15911094367
            'card_alias' => $cardAlias,
            'fans_type' => 1,
            'fans_id' => 0
        ]);
        return $response;
    }

    public static function userCardDelete($mobile, $cardAlias)
    {
        $response = (new Client(YouZanService::accessToken()))->post(
            'youzan.scrm.customer.card.delete', '3.0.0', [
            'mobile' => $mobile,
            'card_alias' => $cardAlias,
            'card_no' => '',
            'fans_type' => 1,
            'fans_id' => 0
        ]);
        return $response;
    }

    public static function getCustomerInfoByCardNo($cardNo)
    {
        $response = (new Client(YouZanService::accessToken()))->post(
            'youzan.scrm.customer.info.get', '3.0.0', ['card_no' => $cardNo]);
        return $response;
    }
}
