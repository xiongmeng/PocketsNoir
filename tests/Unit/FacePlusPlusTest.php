<?php

namespace Tests\Unit;

use App\Services\FacePlusPlusService;
use App\Services\KoaLaService;
use App\Services\TianShuService;
use App\Vip;
use Softonic\GraphQL\ClientBuilder;
use Tests\TestCase;

class FacePlusPlusTest extends TestCase
{
    public function testDetect()
    {
        $res = FacePlusPlusService::detect('https://pn-activity.oss-cn-shenzhen.aliyuncs.com/vip/face/tmp/1521778248.jpeg');
        print_r($res);
    }

    public function testDetectNoFace()
    {
        $res = FacePlusPlusService::detect('https://pn-activity.oss-cn-shenzhen.aliyuncs.com/vip/face/tmp/a38d73081611920081ec63303ec9b3e320180329110231.jpeg');
        print_r($res);
    }

    public function testDetectMultiFace()
    {
        $res = FacePlusPlusService::detect('https://pn-activity.oss-cn-shenzhen.aliyuncs.com/vip/face/tmp/1b475d3e1ba81e9cbf4692c0d01bd86120180328143242.jpeg');
        print_r($res);
    }

    public function testFacesetCreate()
    {
        $res = FacePlusPlusService::facesetCreate();
        print_r($res);
    }

    public function testSearch()
    {
        $faces = [
//            'https://pn-activity.oss-cn-shenzhen.aliyuncs.com/vip/face/tmp/1521783315.jpeg',
//            'https://koala-online.oss-cn-beijing.aliyuncs.com/static/upload/photo/2018-03-29/v2_dab30fade89ab623f3357a1a46b97b95f05d4e41.jpg',
//            'https://koala-online.oss-cn-beijing.aliyuncs.com/static/upload/photo/2018-03-28/v2_332e769c74a1da5169eff12a2148a5e321b753c7.jpg',
//            'https://koala-online.oss-cn-beijing.aliyuncs.com/static/upload/photo/2018-03-26/v2_63220715f83c4f2f79a87e475be6ffbe695de7e8.jpg'
        ];

        foreach ($faces as $face){
            $faceDe = FacePlusPlusService::detectOnlyOneFace($face);

            FacePlusPlusService::facesetAddface(1, $faceDe['face_token']);
        }

//        $searchFace = 'https://pn-activity.oss-cn-shenzhen.aliyuncs.com/vip/face/tmp/a36ed17a0ca6bb481ebb75484bd121db20180328141212.jpeg';
        $searchFace = 'https://koala-online.oss-cn-beijing.aliyuncs.com/static/upload/photo/2018-03-29/v2_dab30fade89ab623f3357a1a46b97b95f05d4e41.jpg';
        $faceDe = FacePlusPlusService::detectOnlyOneFace($searchFace);

        $res = FacePlusPlusService::facesetSearchByToken($faceDe['face_token'], 1);
    }

    public function testFacesetGetDetail()
    {
        $res = FacePlusPlusService::facesetGetDetail(1);
        foreach ($res['face_tokens'] as $face_token){
            $res = FacePlusPlusService::facesetRemoveface($face_token);
        }
    }

//29b1db47eb42a6252227e674a2fb2edb
}
