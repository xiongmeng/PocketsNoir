<?php

namespace Tests\Unit;

use App\Jobs\DisposeChangesWithYZUid;
use App\Jobs\DisposeGuanJiaPoPush;
use App\Jobs\DisposeYouZanPush;
use App\Jobs\RecalculateVip;
use App\Jobs\SyncVip;
use App\Libiary\Sign\Md5Zulin;
use App\Libiary\Utility\CurlWrapper;
use App\Services\ZuLinService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Tests\TestCase;

class BoYuanTest extends TestCase
{
    public function testPersonInfo()
    {
//        $res = CurlWrapper::post([
//            'name' => '熊猛', 'idCard' => '420621198409017713'
//            ],
////            'https://dev-debug.365dayservice.com:8044/credit-api/v1/personalInfo/idCardUsernameCheck', 30 ,
//            'https://api.365dayservice.com:8044/credit-api/v1/personalInfo/idCardUsernameCheck',
//            ['xa-key' => 'uO4UQxwXA5r5iSxBdzyJxLx6JTKmFgTUaVn5rFJleu0=']);

        $client = new Client();
        $options = [
            'json' => [
                'name' => '熊猛',
                'idCard' => '420621199009017713'
//                'idCard' => '420621198409017713'
            ],
            'headers' => [
//                'xa-key' => 'uO4UQxwXA5r5iSxBdzyJxLx6JTKmFgTUaVn5rFJleu0='
                'xa-key' => 'yxZpS9Qvh0FXFDBKcBS5jL0ilOJYhUB2d2hWcs+HPn8='
            ]
        ];

        try{

            $response = $client->request('POST',
//            '',
//                'https://dev-debug.365dayservice.com:8044/credit-api/v1/personalInfo/idCardUsernameCheck',
            'https://api.365dayservice.com:8044/credit-api/v1/personalInfo/idCardUsernameCheck',
                $options);

            $resJson = $response->getBody()->getContents();
            $res = json_decode($resJson, true);

            if(!empty($res['uuid'])){
                return $res['uuid'];
            }else{
                throw new \Exception('博源科技实名认证返回正常但未找见uuid');
            }
        }catch (ClientException $e){
            $resJson = $e->getResponse()->getBody()->getContents();
            $res = json_decode($resJson, true);
            if(!empty($res) && !empty($res['code']) && !empty($res['message'])){
                throw new \Exception($res['message'], $res['code']);
            }else{
                throw new \Exception('博源科技返回位置错误', '999');
            }
        }
    }
}
