<?php

namespace Tests\Unit;

use App\Libiary\Utility\CurlWrapper;
use EasyWeChat\Kernel\Http\StreamResponse;
use Tests\TestCase;

class WechatTest extends TestCase
{
    public function testMediaId()
    {
//        $mediaId = 'C5kojyxCzsYrfrvQH9m-Z8HKvjwfF6N-9CQ98EziY09KPDuPlvQLPbh3lP_VNZhu';
        $mediaId = '8tQvYzIQHoTa1k1MzRp_UcFn_Sh1h8kO42qqPm3Haj3G-y3TLfGBcuBqqBu_vMCy';
        /** @var StreamResponse $res */
        $res = \EasyWeChat::officialAccount()->media->get($mediaId);
        $res->save(__DIR__);

//        $user = \EasyWeChat::officialAccount()->user->get('op-3Cww_mqGm2Caj6ZeprJrZ1h8Y');
//
//        $content = CurlWrapper::curlGet($user['headimgurl']);
//        $header = storage_path($user['openid'] . ".jpeg");
//        file_put_contents($header, file_get_contents($user['headimgurl']));


//        $reader = new \QrReader($res->getBodyContents(), \QrReader::SOURCE_TYPE_BLOB);
//        $cjt = $reader->text();
    }
}
