<?php

namespace Tests\Unit;

use App\Jobs\RegenerateShouKuanQrcode;
use App\Libiary\Utility\CurlWrapper;
use App\Services\ChunJie2018H5Service;
use EasyWeChat\Kernel\Http\StreamResponse;
use Tests\TestCase;

class ChunJie2018Test extends TestCase
{
    public function testGenerateShouKuanMa()
    {
        $mediaId = 'hk5Z2g7Ij435ynQ9z1aHXSzOZzb6LNVwqHuA_moycnG9f_wuKTt9ZrcwONKJeJso';
//        $mediaId = '8tQvYzIQHoTa1k1MzRp_UcFn_Sh1h8kO42qqPm3Haj3G-y3TLfGBcuBqqBu_vMCy';
        $openId = 'op-3Cww_mqGm2Caj6ZeprJrZ1h8Y';
//        /** @var StreamResponse $res */
//        $res = \EasyWeChat::officialAccount()->media->get($mediaId);
////        $res->save(__DIR__);
//
        $user = \EasyWeChat::officialAccount()->user->get($openId);
//        $header = storage_path($user['openid'] . ".jpeg");
//        file_put_contents($header, file_get_contents($user['headimgurl']));
//        $reader = new \QrReader($res->getBodyContents(), \QrReader::SOURCE_TYPE_BLOB);
//        $cjt = $reader->text();

        dispatch(new RegenerateShouKuanQrcode($openId, $mediaId, $user['headimgurl'], $user['nickname']))->onConnection('sync');
    }

    public function testGenerate()
    {
        $openId = 'op-3Cww_mqGm2Caj6ZeprJrZ1h8Y';
//        $openId = 'op-3Cw9E62qcLfVuQ91e3AOJOvvc';
//        $openId = 'op-3Cw7_hlx51fbt1D2afTCejuzY';

//        $openId = 'o_iNMxJfk7LmFakFxY6ArwQScSok';
        $user = \EasyWeChat::officialAccount()->user->get($openId);

        ChunJie2018H5Service::generate($openId, $user['headimgurl'], '熊猛');
    }

    /**
     * 压测
     */
    public function testYace()
    {
        $data1 = <<<D1
{"openId":"opcNZ5Jic0zfcamTsZhkyy9uuvRI","avatarUrl":"https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83epOiajS4qA7kbdceoEEcjI1jfZM4jGRy1G9icHlMWSibUaCMPurW2qxjmLWScxaVtuCibK9meCWz3FNFQ/0","nickName":"熊猛","img":"wxfile://tmp_b3ec7ea1c2864ac7329673dcbdd1079f0e3ee56aae063c3e.jpg"}
D1;
        $data2 = <<<D1
{"openId":"opcNZ5Jic0zfcamTsZhkyy9uuvRI","avatarUrl":"https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83epOiajS4qA7kbdceoEEcjI1jfZM4jGRy1G9icHlMWSibUaCMPurW2qxjmLWScxaVtuCibK9meCWz3FNFQ/0","nickName":"熊猛","img":"wxfile://tmp_b3ec7ea1c2864ac7329673dcbdd1079f0e3ee56aae063c3e.jpg"}
D1;
        $data3 = <<<D1
{"openId":"opcNZ5Jic0zfcamTsZhkyy9uuvRI","avatarUrl":"https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83epOiajS4qA7kbdceoEEcjI1jfZM4jGRy1G9icHlMWSibUaCMPurW2qxjmLWScxaVtuCibK9meCWz3FNFQ/0","nickName":"熊猛","img":"wxfile://tmp_b3ec7ea1c2864ac7329673dcbdd1079f0e3ee56aae063c3e.jpg"}
D1;
        $data4 = <<<D1
{"openId":"opcNZ5Jic0zfcamTsZhkyy9uuvRI","avatarUrl":"https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83epOiajS4qA7kbdceoEEcjI1jfZM4jGRy1G9icHlMWSibUaCMPurW2qxjmLWScxaVtuCibK9meCWz3FNFQ/0","nickName":"熊猛","img":"wxfile://tmp_b3ec7ea1c2864ac7329673dcbdd1079f0e3ee56aae063c3e.jpg"}
D1;
        $data5 = <<<D1
{"openId":"opcNZ5Jic0zfcamTsZhkyy9uuvRI","avatarUrl":"https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83epOiajS4qA7kbdceoEEcjI1jfZM4jGRy1G9icHlMWSibUaCMPurW2qxjmLWScxaVtuCibK9meCWz3FNFQ/0","nickName":"熊猛","img":"wxfile://tmp_b3ec7ea1c2864ac7329673dcbdd1079f0e3ee56aae063c3e.jpg"}
D1;

        $datas = [$data1];

        $len = 1000;
        for ($i = 0; $i<$len; $i++){
            foreach ($datas as $data){
                $res = CurlWrapper::post(
                    $data, 'https://activity.sylicod.com/2019chunjieshoukuanma', 30, ['Content-type:application/json']
                );
            }
        }

        print_r($res);
    }
}
