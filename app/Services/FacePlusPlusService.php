<?php

namespace App\Services;

use App\Libiary\Utility\CurlWrapper;
use function EasyWeChat\Kernel\Support\current_url;

class FacePlusPlusService
{
    public static function detect($imageUrl)
    {
        $res = self::query('detect', ['image_url' => $imageUrl]);

        return $res;
    }

    public static function facesetCreate()
    {
        $res = self::query('faceset/create', [
            'display_name' => '集合1',
            'outer_id' => 1
        ]);

        return $res;
    }

    public static function facesetAddface($outerId, $faceTokens)
    {
        $res = self::query('faceset/addface',[
            'outer_id' => $outerId,
            'face_tokens' => $faceTokens
        ]);

        return $res;
    }

    public static function facesetRemoveface($outerId, $faceTokens)
    {
        $res = self::query('faceset/removeface', [
            'outer_id' => $outerId,
            'face_tokens' => $faceTokens
        ]);

        return $res;
    }

    public static function facesetGetDetail($outerId)
    {
        $res = self::query('faceset/getdetail', [
            'outer_id' => $outerId,
        ]);

        return $res;
    }

    public static function facesetSearch($faceToken, $outerId)
    {
        $res = self::query('search', [
            'face_token' => $faceToken,
            'outer_id' => $outerId,
        ]);

        return $res;
    }

    /**
     * 检测是否只有一张人脸
     * @param $imageUrl
     * @return mixed
     * @throws \Exception
     */
    public static function DetectOnlyOneFace($imageUrl)
    {
        $res = self::detect($imageUrl);

        $count = count($res['faces']);
        if($count < 1){
            throw new \Exception("ImageHasNoFace");
        }
        if($count > 1){
            throw new \Exception("ImageHasMoreFaceWith({$count})");
        }

        return current($res['faces']);
    }

    private static function query($path, $data)
    {
        $data = array_merge([
            'api_key' => env('FACEPLUSPLUS_KEY'),
            'api_secret' => env('FACEPLUSPLUS_SECRET'),
        ], $data);

        $resJson = CurlWrapper::post($data, 'https://api-cn.faceplusplus.com/facepp/v3/' . $path);

        $res = json_decode($resJson, true);
        if(empty($res)){
            throw new \Exception("ResponseNotJsonFormatFace++");
        }

        if(!empty($res['error_message'])){
            throw new \Exception("Face++Exception:{$res['error_message']}");
        }

        return $res;
    }

}