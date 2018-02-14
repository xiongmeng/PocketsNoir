<?php

namespace App\Services;

use Imagine\Gd\Font;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;

class ChunJie2018H5Service
{
    public static function generate($id, $avatar, $nickname)
    {
        $publicDisk = \Storage::disk('public');
        $head = "{$id}.jpeg";
        $avatar = str_replace("/132", "/0", $avatar);
        $headContent = \App\Libiary\Utility\CurlWrapper::curlGet($avatar);
        $publicDisk->put($head, $headContent);

        $ossDisk = \Storage::disk('oss_activity');
        $shoukumaQrCode = $ossDisk->get("2018chunjie/shoukuanma/{$id}.jpeg");
        $shoukuanma = "shoukuma/{$id}.jpeg";
        $publicDisk->put($shoukuanma, $shoukumaQrCode);

        $imagine = new Imagine();
        $bgi = $imagine->open(__DIR__ . "/ChunJie2018H5/bg/all.jpg");

        $headi = $imagine->open($publicDisk->path($head));
        $headi->resize(new Box(375,375));
        $bgi->paste($headi, new Point(350,625));

        $noBgi = $imagine->open(__DIR__ . "/ChunJie2018H5/all.png");
        $bgi->paste($noBgi, new Point(0,0));

        $palette = new RGB();
        $font = new Font(__DIR__ . '/ChunJie2018H5/SY.ttf', '40', $palette->color('#000'));
//        $nickname = '哈哈哈哈哈哈哈哈';
        $box = $font->box($nickname);
        $bgi->draw()->text($nickname, $font, new Point(($bgi->getSize()->getWidth() - $box->getWidth())/2,1175), 0, 2);

        $shoukumai = $imagine->open($publicDisk->path($shoukuanma));
        $shoukumai->resize(new Box(280,280));
        $bgi->paste($shoukumai, new Point(402, 1446));

        $users = "users/{$id}.jpeg";
        $publicDisk->put($users, '');
        $bgi->save(\Storage::disk('public')->path($users));

        \Storage::disk('oss_activity')->put("2018chunjie/users/{$id}.jpeg", file_get_contents(\Storage::disk('public')->path($users)));

    }
}
