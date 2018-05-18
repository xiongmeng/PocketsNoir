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

class MobileTest extends TestCase
{
    public function testPersonInfo()
    {
//        $res = CurlWrapper::post([
//            'name' => '熊猛', 'idCard' => '420621198409017713'
//            ],
////            'https://dev-debug.365dayservice.com:8044/credit-api/v1/personalInfo/idCardUsernameCheck', 30 ,
//            'https://api.365dayservice.com:8044/credit-api/v1/personalInfo/idCardUsernameCheck',
//            ['xa-key' => 'uO4UQxwXA5r5iSxBdzyJxLx6JTKmFgTUaVn5rFJleu0=']);

//        $client = new Client();
//        $options = [
//            'json' => [
//                'name' => '熊猛',
//                'idCard' => '420621199009017713'
//            ],
//            'headers' => [
//                'xa-key' => 'uO4UQxwXA5r5iSxBdzyJxLx6JTKmFgTUaVn5rFJleu0='
//            ]
//        ];
//
//        try{
//
//            $response = $client->request('GET',
////            '',
//                'https://ali-mobile.showapi.com/6-1',
////            'https://api.365dayservice.com:8044/credit-api/v1/personalInfo/idCardUsernameCheck',
//                $options);
//
//            $resJson = $response->getBody()->getContents();
//            $res = json_decode($resJson, true);
//            print_r($resJson);
//        }catch (ClientException $e){
//            $resJson = $e->getResponse()->getBody()->getContents();
//        }

        $host = "https://ali-mobile.showapi.com";
        $path = "/6-1";
        $method = "GET";
        $appcode = "cf62d9ad41674e9daaa615bbedbf5f85";
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "num=18611367408";
        $bodys = "";
        $url = $host . $path . "?" . $querys;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);


        if (1 == strpos("$" . $host, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        $data = curl_exec($curl);
        var_dump(curl_error($curl));
        var_dump(curl_errno($curl));

        $headers = ["Authorization:APPCODE " . $appcode];
        $res = CurlWrapper::get(
            [
                'num' => '18611367408'],
            'https://ali-mobile.showapi.com/6-1',
            30,
            $headers
            );
    }


}

//    {"showapi_res_error":"","showapi_res_code":0,"showapi_res_body":{"name":"北京联通GSM卡","postCode":"100000","provCode":"110000","prov":"北京","num":1861136,"cityCode":"110000","type":3,"areaCode":"010","city":"北京","ret_code":0}}

