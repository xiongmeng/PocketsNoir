<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

class KoaLaService
{
    public static function loginCookie()
    {
        $jar = \Cache::get('face_cookie');
        if(empty($jar)){
            $jar = new CookieJar();
            $data = ['username' => 'xiongmeng@pocketnoir.com', 'password' => 'xiongmeng'];

            self::request('POST','/auth/login',['form_params' => $data, 'cookies' => $jar]);

            \Cache::put('face_cookie', $jar, 10);
        }

        return $jar;
    }

    public static function upload($path, $files, $data)
    {
        $multipart = [];

        foreach ($files as $filename => $filePath){
            $multipart[] = [
                'name' => $filename,
                'contents' => fopen($filePath, 'r')
            ];
        }

        foreach ($data as $key => $value){
            $multipart[] = [
                'name' => $key,
                'contents' => $value
            ];
        };

        return self::request('POST', $path, ['multipart' => $multipart]);
    }

    public static function post($path , $data)
    {
        return self::request('POST', $path, ['form_params' => $data]);
    }

    public static function get($path, $data)
    {
        return self::request('GET', $path, ['query' => $data]);
    }

    public static function request($method, $path, $options)
    {
        $baseUri = "https://v2.koalacam.net";
        $client = new Client(['base_uri' => $baseUri]);

        empty($options['cookies']) && ($options['cookies'] = self::loginCookie());

        empty($options['headers']) && $options['headers'] = [];
        empty($options['headers']['User-Agent']) && $options['headers']['User-Agent'] = 'Koala Admin';

        $response = $client->request($method, $path, $options);

        $resJson = $response->getBody()->getContents();
        $res = json_decode($resJson, true);
        if(empty($res)){
            throw new \Exception("Koala返回结果为非Json格式");
        }

        if($res['code'] <> 0){
            throw new \Exception($res['desc']?:'Koala结果返回未知异常', $res['code']);
        }

        return $res;
    }
}

