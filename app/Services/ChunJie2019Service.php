<?php
/**
 * Created by PhpStorm.
 * User: lingchao
 * Date: 2019/1/15
 * Time: 上午10:29
 */

namespace App\Services;
use Imagine\Gd\Font;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;
use App\JobBuffer;
use App\Libiary\Utility\CurlWrapper;
use App\Services\ChunJie2018H5Service;
use App\Services\YouZanService;
use EasyWeChat\Kernel\Http\StreamResponse;
use Endroid\QrCode\QrCode;
use Zxing\QrReader;


class ChunJie2019Service
{

    public function ceshi($openId, $serverId, $avatar, $nickname)
    {
        $this->openId = $openId;
        $this->serverId = $serverId;
        $this->avatar = $avatar;
        $this->nickname = $nickname;

        /** 识别二维码 */
        /** @var StreamResponse $res */

        $shoukuanma = $this->serverId;
        \Log::info("QrReaderReadBegin");
        $reader = new QrReader($shoukuanma);
        $cjt = $reader->text();
        \Log::info("QrReaderReadEnd");
//        var_dump($cjt);die;
        if($cjt === false){
            throw new \Exception("解析图片二维码失败!{$this->openId}|||{$this->serverId}");
        }

        $publicDisk = \Storage::disk('public');
        \Log::info("GetHeadImgUrlBegin");
        $headContent = CurlWrapper::curlGet($this->avatar);
        $file = "{$this->openId}.jpeg";
        $publicDisk->put($file, $headContent);
        \Log::info("GetHeadImgUrlEnd");

        $headPath = $publicDisk->path($file);

        \Log::info("QrCodeBegin");
        $writer = new QrCode($cjt);
        $writer->setSize(300);
        $writer->setWriterByName('png');
        $writer->setMargin(1);
        $writer->setLogoPath($headPath);
        $writer->setLogoWidth(45);
        $content = $writer->writeString();
        \Log::info("QrCodeEnd");

        \Log::info("OssPutBegin");
        /** 保存新二维码到阿里云 */
        \Storage::disk('oss_activity')->put("activity/chunjie2019/shoukuanma/{$this->openId}.jpeg", $content);

        \Log::info("OssPutEnd");

        \Log::info("LastImgeGenerateBegin");
        /** @var 获取头像并生成新二维码 $user */
//        $user = \EasyWeChat::officialAccount()->user->get($this->openId);
//        var_dump($cjt);die;
////        $url = ;
        ChunJie2019Service::generate($this->openId, $this->avatar, $this->nickname,"/ChunJie2019/bjImg/a.png",'a');
        ChunJie2019Service::generate($this->openId, $this->avatar, $this->nickname,"/ChunJie2019/bjImg/b.png",'b');
        ChunJie2019Service::generate($this->openId, $this->avatar, $this->nickname,"/ChunJie2019/bjImg/c.png",'c');
//        ChunJie2019Service::generate($this->openId, $this->avatar, $this->nickname,"/ChunJie2019/bjImg/a1.png",'a1');
//        ChunJie2019Service::generate($this->openId, $this->avatar, $this->nickname,"/ChunJie2019/bjImg/b1.png",'b1');
//        ChunJie2019Service::generate($this->openId, $this->avatar, $this->nickname,"/ChunJie2019/bjImg/c1.png",'c1');
        \Log::info("LastImgeGenerateEnd");

    }
    public static function generate($id, $avatar, $nickname,$url,$key)
    {
        $publicDisk = \Storage::disk('public');
        $head = "{$id}.jpeg";
        $avatar = str_replace("/132", "/0", $avatar);
        $headContent = \App\Libiary\Utility\CurlWrapper::curlGet($avatar);
        $publicDisk->put($head, $headContent);

        $ossDisk = \Storage::disk('oss_activity');
        $shoukumaQrCode = $ossDisk->get("activity/chunjie2019/shoukuanma/{$id}.jpeg");
        $shoukuanma = "activity/chunjie2019/shoukuma/{$id}.jpeg";
        $publicDisk->put($shoukuanma, $shoukumaQrCode);

        $imagine = new Imagine();
        $bgi = $imagine->open(__DIR__ . $url);


        $palette = new RGB();

        $shoukumai = $imagine->open($publicDisk->path($shoukuanma));
        $shoukumai->resize(new Box(200,200));
        $bgi->resize(new Box(1080,1920));
        $headi = $imagine->open($publicDisk->path($head));
        $headi->resize(new Box(170,170));
        $color = '#000';//fec71c
//        $headi->draw()->ellipse(new Point(75, 75), new Box(150, 150),);
        if ($key =='a'){
            $bgi->paste($shoukumai, new Point(441, 1437));//背景图
            $bgi->paste($headi, new Point(461,878)); //头像

            $font = new Font(__DIR__ . '/ChunJie2018H5/SY.ttf', '36', $palette->color($color)); //昵称信息
            $box = $font->box($nickname);
            $bgi->draw()->text($nickname, $font, new Point(($bgi->getSize()->getWidth() - $box->getWidth())/2,1064), 0, 2);
        }
        if ($key =='b'){
            $bgi->paste($shoukumai, new Point(442, 747));
            $bgi->paste($headi, new Point(463,445));

            $font = new Font(__DIR__ . '/ChunJie2018H5/SY.ttf', '36', $palette->color($color)); //昵称信息
            $box = $font->box($nickname);
            $bgi->draw()->text($nickname, $font, new Point(($bgi->getSize()->getWidth() - $box->getWidth())/2,638), 0, 2);
        }
        if ($key =='c'){
            $bgi->paste($shoukumai, new Point(452, 753));
            $bgi->paste($headi, new Point(465,443));

            $font = new Font(__DIR__ . '/ChunJie2018H5/SY.ttf', '36', $palette->color($color)); //昵称信息
            $box = $font->box($nickname);
            $bgi->draw()->text($nickname, $font, new Point(($bgi->getSize()->getWidth() - $box->getWidth())/2,634), 0, 2);
        }



        if ($key =='a1'){
            $bgi->paste($shoukumai, new Point(437, 1404));//背景图
            $bgi->paste($headi, new Point(464,927)); //头像

            $font = new Font(__DIR__ . '/ChunJie2018H5/SY.ttf', '36', $palette->color($color)); //昵称信息
            $box = $font->box($nickname);
            $bgi->draw()->text($nickname, $font, new Point(($bgi->getSize()->getWidth() - $box->getWidth())/2,1114), 0, 2);
        }
        if ($key =='b1'){
            $bgi->paste($shoukumai, new Point(442, 808));
            $bgi->paste($headi, new Point(463,518));

            $font = new Font(__DIR__ . '/ChunJie2018H5/SY.ttf', '36', $palette->color($color)); //昵称信息
            $box = $font->box($nickname);
            $bgi->draw()->text($nickname, $font, new Point(($bgi->getSize()->getWidth() - $box->getWidth())/2,701), 0, 2);
        }
            if ($key =='c1'){
            $bgi->paste($shoukumai, new Point(452, 823));
            $bgi->paste($headi, new Point(465,517));

            $font = new Font(__DIR__ . '/ChunJie2018H5/SY.ttf', '36', $palette->color($color)); //昵称信息
            $box = $font->box($nickname);
            $bgi->draw()->text($nickname, $font, new Point(($bgi->getSize()->getWidth() - $box->getWidth())/2,707), 0, 2);
        }

//        var_dump($headi);exit();

//$bgi->paste();
        $users = "users/{$id}{$key}.jpeg";
        $publicDisk->put($users, '');
        $bgi->save(\Storage::disk('public')->path($users));

        \Storage::disk('oss_activity')->put("activity/chunjie2019/users/{$id}{$key}.jpeg", file_get_contents(\Storage::disk('public')->path($users)));

    }
}
