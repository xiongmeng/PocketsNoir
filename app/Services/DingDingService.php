<?php

namespace App\Services;

use App\Libiary\Utility\CurlWrapper;

class DingDingService
{
    public static function accessToken()
    {
        $key = 'DINGDING_SELF_ACCESS_TOKEN';
        if (\Cache::has($key)) {
            return \Cache::get($key);
        } else {
            $corpId = env('DINGDING_CORPID');
            $corpSercet = env('DINGDING_CORPSECRET');

            $accessToken = self::request('gettoken', [
                'corpid' => $corpId,
                'corpsecret' => $corpSercet
            ]);

            \Cache::add($key, $accessToken['access_token'], $accessToken['expires_in'] / 60);

            return $accessToken['access_token'];
        }
    }

    public static function departmentListIds()
    {
//        self::request()
    }


    private static function getWithAccessToken($path, $data)
    {
        $accessToken = self::accessToken();
        $data['access_token'] = $accessToken;

        $res = self::request($path, $data, 'GET');

        return $res;
    }

    /**
     * 请求
     * @param $path
     * @param $data
     * @param string $method
     * @return mixed
     * @throws \Exception
     */
    private static function request($path, $data, $method='GET')
    {
        $resRaw = CurlWrapper::get($data, env('DINGDING_HOST') . $path);

        $res = json_decode($resRaw, true);
        if(empty($res) || !empty($res['errcode'])){
            throw new \Exception("钉钉接口请求异常: {$res['errmsg']}", $res['errcode']);
        }

        return $res;
    }

}
