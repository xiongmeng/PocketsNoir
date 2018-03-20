<?php

namespace Tests\Unit;

use App\Services\KoaLaService;
use Tests\TestCase;

class KoalaTest extends TestCase
{
    public function testAuth()
    {
        $res = KoaLaService::loginCookie();
        print_r($res);
    }

    public function testMobileAdminSubjects()
    {
        $res = KoaLaService::get('/mobile-admin/subjects', []);
        print_r($res);
    }

    public function testSubjectAvatar()
    {
        $res = KoaLaService::upload('/subject/avatar',
            ['avatar' => __DIR__ . '/unface.jpg'], []);
        print_r($res);
    }

    public function testSubjectPhoto()
    {
        $res = KoaLaService::upload(
            '/subject/photo',
//            ['photo' => __DIR__ . '/face_shebao.jpg'],
            ['photo' => __DIR__ . '/unface.jpg'],
            []
        );

        print_r($res);
    }

    public function testSubject()
    {
        $res = KoaLaService::post('/subject', ['subject_type' => 0, 'name' => '18611367408']);
        print_r($res);
    }

    public function testMobileAdminSubjectsList()
    {
        $res = KoaLaService::get('/mobile-admin/subjects/list', ['category' => 'employee', 'name' => '18611367408']);
        print_r($res);
    }
}
