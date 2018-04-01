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
use App\Vip;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ModelTest extends TestCase
{
    public function testAddOrModify()
    {
        DB::beginTransaction();
        $vip = new Vip();
        $vip->mobile='18611367408';
        $vip->card=Vip::CARD_1;
        $vip->save();

        $a = $vip->mobile;
        \DB::commit();
    }

    public function testValidateMobile()
    {
        $matches = [];
//        $tel = '18611367408';
//        $n = preg_match_all("/^1[3|4|5|7|8][0-9]\d{4,8}$/", $tel, $matches);

        $tel = 'a 132345323';
        $n = preg_match_all("/^1[3|4|5|7|8][0-9]\d{4,8}$/", $tel, $matches);
    }


    function is_mobile($mobile) {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,1,3,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
    }
}
