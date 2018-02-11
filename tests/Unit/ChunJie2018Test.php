<?php

namespace Tests\Unit;

use App\Jobs\RegenerateShouKuanQrcode;
use EasyWeChat\Kernel\Http\StreamResponse;
use Tests\TestCase;

class ChunJie2018Test extends TestCase
{
    public function testGenerateShouKuanMa()
    {
        $mediaId = 'ZQSq9OeRhge6xdCu-NnrjS97pBbJbBQf3QEZJNoT3RY51fW2Zj0rxg7IbPYMWbgp';
        $openId = 'op-3Cww_mqGm2Caj6ZeprJrZ1h8Y';
//        /** @var StreamResponse $res */
//        $res = \EasyWeChat::officialAccount()->media->get($mediaId);
////        $res->save(__DIR__);
//
//        $user = \EasyWeChat::officialAccount()->user->get('op-3Cww_mqGm2Caj6ZeprJrZ1h8Y');
//        $header = storage_path($user['openid'] . ".jpeg");
//        file_put_contents($header, file_get_contents($user['headimgurl']));
//        $reader = new \QrReader($res->getBodyContents(), \QrReader::SOURCE_TYPE_BLOB);
//        $cjt = $reader->text();

        dispatch(new RegenerateShouKuanQrcode($openId, $mediaId))->onConnection('sync');
    }
}
