<?php

namespace Tests\Unit;

use App\Services\YouZanService;
use Endroid\QrCode\QrCode;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Tests\TestCase;
use Youzan\Open\Client;

class ImagineTest extends TestCase
{
    public function testImage()
    {
        $cj = __DIR__ . '/mmexport1517400671995.jpg';
        $xm = __DIR__ . '/mm_facetoface_collect_qrcode_1517401455304.png';


        $cji = new \Imagine\Gd\Imagine();
        $image = $cji->open($cj);
        $image->crop(new Point(275, 200), new Box(699, 1000));
        $image->save(__DIR__ . '/cj2.jpg');

        $xmi = new \Imagine\Gd\Imagine();
        $image = $xmi->open($xm);
        $image->crop(new Point(275, 200), new Box(699, 1000));
        $image->save(__DIR__ . '/xm2.jpg');

    }

    public function testQrcodeRead()
    {
        $cj = __DIR__ . '/cj2.jpg';
        $xm = __DIR__ . '/xm2.jpg';

        $reader = new \QrReader($cj);
        $cjt = $reader->text();

        $writer = new QrCode($cjt);
        $writer->setSize(300);
        $writer->setWriterByName('png');
        $writer->setMargin(1);
        $writer->writeFile(__DIR__ . '/cj3.jpg');

        $reader = new \QrReader($xm);
        $xmt = $reader->text();
    }

    public function testQrcodeWrite()
    {
        $cj = __DIR__ . '/cj2.jpg';
        $xm = __DIR__ . '/xm2.jpg';

//        $reader = new \QrReader($cj);
        $cjt = 'wxp://f2f0BtI4uGxWMwpTXxiV39hKd6_gXYrX97K0';

        $writer = new QrCode($cjt);
        $writer->setSize(300);
        $writer->setWriterByName('png');
        $writer->setMargin(1);
        $writer->writeFile(__DIR__ . '/cj3.jpg');

//        $reader = new \QrReader($xm);
//        $xmt = $reader->text();
    }

    public function testPaste()
    {
        $bg = __DIR__ . '/WechatIMG3.jpeg';
        $cj = __DIR__ . '/cj3.jpg';
        $xm = __DIR__ . '/xm3.jpg';

        $imagine = new Imagine();
        $bgi = $imagine->open($bg);

        $cji = $imagine->open($cj);
        $bgi->paste($cji, new Point(371, 1467));
        $bgi->save(__DIR__ . '/cj4.jpg');

//        $xmi = new \Imagine\Gd\Imagine();
//        $image = $xmi->open($xm);
//        $image->crop(new Point(275, 200), new Box(699, 1000));
//        $image->save(__DIR__ . '/xm2.jpg');
    }
}
