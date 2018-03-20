<?php

namespace Tests\Unit;

use EasyWeChat\Kernel\Http\StreamResponse;
use Mrgoon\AliSms\AliSms;
use Tests\TestCase;

class AliYunSmsTest extends TestCase
{
    public function testQianMing()
    {
//        SMS_111890588

        $aliSms = new AliSms();
        $response = $aliSms->sendSms('18611367408', 'SMS_111890588', ['code'=> '888888']);
//dump($response);
    }
}
