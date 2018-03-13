<?php

namespace Tests\Unit;

use App\Jobs\DisposeChangesWithYZUid;
use App\Jobs\DisposeGuanJiaPoPush;
use App\Jobs\DisposeYouZanPush;
use App\Jobs\RecalculateVip;
use App\Jobs\SyncVip;
use App\Libiary\Sign\Md5Zulin;
use App\Libiary\Utility\CurlWrapper;
use App\Services\ZuLinService;
use Tests\TestCase;

class ZulinTest extends TestCase
{
    public function testCardGrant()
    {
        ZuLinService::grantVip('18611367408', '蓝口袋');
    }

    public function testPointSync()
    {
        ZuLinService::syncPoints('18611367408', 6);
    }
}
