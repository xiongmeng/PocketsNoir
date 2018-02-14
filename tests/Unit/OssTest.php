<?php

namespace Tests\Unit;

use EasyWeChat\Kernel\Http\StreamResponse;
use Tests\TestCase;

class OssTest extends TestCase
{
    public function testUpload()
    {
        $user = \EasyWeChat::officialAccount()->user->get('op-3Cww_mqGm2Caj6ZeprJrZ1h8Y');

//        $headContent = file_get_contents($user['headimgurl']);
        $file = "{$user['openid']}.jpeg";
//        \Storage::disk('public')->put($file, $headContent);

        $headContent = \Storage::disk('public')->get($file);
        \Storage::disk('oss_activity')->put("2018chunjie/users/{$user['openid']}.jpeg", $headContent);
    }

    public function testDelete()
    {
        $id = "op-3Cww_mqGm2Caj6ZeprJrZ1h8Y";
        $file = "2018chunjie/users/{$id}.jpeg";
        $ossDisk = \Storage::disk('oss_activity');
        $res = $ossDisk->delete($file);
    }
}
