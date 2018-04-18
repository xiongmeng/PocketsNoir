<?php

namespace Tests\Unit;

use App\Jobs\DisposeChangesWithYZUid;
use App\Jobs\DisposeGuanJiaPoPush;
use App\Jobs\DisposeYouZanPush;
use App\Jobs\RecalculateVip;
use App\Jobs\SyncVip;
use App\Libiary\Sign\Md5Zulin;
use App\Libiary\Utility\CurlWrapper;
use App\Services\DingDingService;
use App\Services\ZuLinService;
use Tests\TestCase;

class DingDingTest extends TestCase
{
    public function testGetAccessToken()
    {
        $accessToken = DingDingService::accessToken();
    }

//    public function test
}
