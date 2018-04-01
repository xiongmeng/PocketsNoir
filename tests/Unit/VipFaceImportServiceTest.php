<?php

namespace Tests\Unit;

use App\Jobs\DisposeChangesWithYZUid;
use App\Jobs\DisposeGuanJiaPoPush;
use App\Jobs\DisposeYouZanPush;
use App\Jobs\RecalculateVip;
use App\Jobs\SyncVip;
use App\Libiary\Sign\Md5Zulin;
use App\Libiary\Utility\CurlWrapper;
use App\Services\KoaLaService;
use App\Services\VipFaceImportService;
use App\Services\ZuLinService;
use Tests\TestCase;

class VipFaceImportServiceTest extends TestCase
{
    public function testDetectFormFile()
    {
        $temp = tmpfile();
        fwrite($temp ,file_get_contents(__DIR__ . '/face_shebao.jpg'));

        $res = VipFaceImportService::detectFormFile($temp);
//        print_r($res);

        is_resource($temp) && fclose($temp);
    }

    public function testBindFace()
    {
        $mobile = '18611367408';
        $subject = KoaLaService::subjectPhoto(__DIR__ . '/face_shebao.jpg');
        VipFaceImportService::bindVipFace($subject['id'], $mobile);
    }
}
