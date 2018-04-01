<?php

namespace Tests\Unit;

use App\Jobs\DisposeChangesWithYZUid;
use App\Jobs\DisposeGuanJiaPoPush;
use App\Jobs\DisposeYouZanPush;
use App\Jobs\RecalculateVip;
use App\Jobs\SyncVip;
use App\Libiary\Sign\Md5Zulin;
use App\Libiary\Utility\CurlWrapper;
use App\Libiary\Utility\IsHelper;
use App\Services\ZuLinService;
use App\Vip;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class IsHelperTest extends TestCase
{
    public function testIsMobile()
    {
        $this->assertEquals(true, IsHelper::isMobile('13611367408'));
        $this->assertEquals(true, IsHelper::isMobile('14611367408'));
        $this->assertEquals(true, IsHelper::isMobile('15611367408'));
        $this->assertEquals(true, IsHelper::isMobile('16611367408'));
        $this->assertEquals(true, IsHelper::isMobile('17611367408'));
        $this->assertEquals(true, IsHelper::isMobile('18611367408'));
        $this->assertEquals(true, IsHelper::isMobile('19611367408'));

        $this->assertEquals(false, IsHelper::isMobile('28611367408'));
        $this->assertEquals(false, IsHelper::isMobile('38611367408'));
        $this->assertEquals(false, IsHelper::isMobile('48611367408'));
        $this->assertEquals(false, IsHelper::isMobile('58611367408'));
        $this->assertEquals(false, IsHelper::isMobile('68611367408'));
        $this->assertEquals(false, IsHelper::isMobile('78611367408'));
        $this->assertEquals(false, IsHelper::isMobile('88611367408'));
        $this->assertEquals(false, IsHelper::isMobile('98611367408'));

        $this->assertEquals(false, IsHelper::isMobile('2861136740'));
        $this->assertEquals(false, IsHelper::isMobile('28611367'));
        $this->assertEquals(false, IsHelper::isMobile('2861136'));
        $this->assertEquals(false, IsHelper::isMobile('286113'));
        $this->assertEquals(false, IsHelper::isMobile('28611'));
        $this->assertEquals(false, IsHelper::isMobile('2861'));
        $this->assertEquals(false, IsHelper::isMobile('286'));
        $this->assertEquals(false, IsHelper::isMobile('28'));
        $this->assertEquals(false, IsHelper::isMobile('2'));

        $this->assertEquals(false, IsHelper::isMobile(' a12343434'));
        $this->assertEquals(false, IsHelper::isMobile('123adadad'));
        $this->assertEquals(false, IsHelper::isMobile('^asasqw'));

    }
}
