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
        ChunJie2019Service::generate($this->openId, $this->avatar, $this->nickname,"/ChunJie2019/bjImg/1.jpg",'a');
        ChunJie2019Service::generate($this->openId, $this->avatar, $this->nickname,"/ChunJie2019/bjImg/2.jpg",'b');
        ChunJie2019Service::generate($this->openId, $this->avatar, $this->nickname,"/ChunJie2019/bjImg/3.jpg",'c');
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
//
//        $headi = $imagine->open($publicDisk->path($head));
//        $headi->resize(new Box(375,375));
//        var_dump($headi);exit();
//        $bgi->paste($headi, new Point(350,625));


//        $noBgi = $imagine->open(__DIR__ ."/ChunJie2019/bjImg/1.jpg");
//        $bgi->paste($noBgi, new Point(0,0));
        $palette = new RGB();
        $font = new Font(__DIR__ . '/ChunJie2018H5/SY.ttf', '40', $palette->color('#000'));
//        $nickname = '哈哈哈哈哈哈哈哈';
        $box = $font->box($nickname);
        $bgi->draw()->text($nickname, $font, new Point(($bgi->getSize()->getWidth() - $box->getWidth())/2,1175), 0, 2);

        $shoukumai = $imagine->open($publicDisk->path($shoukuanma));
        $shoukumai->resize(new Box(200,200));
        $bgi->resize(new Box(1080,1920));
        if ($key =='a'){
            $bgi->paste($shoukumai, new Point(441, 1481));
        }
        if ($key =='b'){
            $bgi->paste($shoukumai, new Point(442, 831));
        }
        if ($key =='c'){
            $bgi->paste($shoukumai, new Point(452, 846));
        }
//$bgi->paste();
        $users = "users/{$id}{$key}.jpeg";
        $publicDisk->put($users, '');
        $bgi->save(\Storage::disk('public')->path($users));

        \Storage::disk('oss_activity')->put("activity/chunjie2019/users/{$id}{$key}.jpeg", file_get_contents(\Storage::disk('public')->path($users)));

    }
}
