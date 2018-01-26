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
}
