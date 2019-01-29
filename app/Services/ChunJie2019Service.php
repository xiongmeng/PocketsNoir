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
use OSS\OssClient;
use Config;

class ChunJie2019Service
{
    public static $bucket = 'public-document';


    public  static function OssClient(){
        return new OssClient( env('OSS_ACCESS_ID'),  env('OSS_ACCESS_KEY'),  'http://oss-cn-shenzhen.aliyuncs.com');
    }
    public static function is_exist_oss($yourObjectName) {
        try{

            $ossClient = self::OssClient();
            $exist = $ossClient->doesObjectExist(self::$bucket, $yourObjectName);
//            return true;
//            var_dump($ossClient);die;
            if($exist) {
                return true;
            } else {
                return false;
            }
        } catch(OssException $e) {
            return false;
        }

    }
    public static function delete_oss($yourObjectName) {
        $has_oss = self::is_exist_oss($yourObjectName);
        if($has_oss) {
            try{
                $ossClient = self::OssClient();
                $result = $ossClient->deleteObject(self::$bucket, $yourObjectName);
                return $result;
            } catch(OssException $e) {
                return false;
            }
        }
    }

    public function aliyun_delete(Request $request)
    {
        $filename = $request->input('filename');
        if (!empty($filename)) {
            $aliyunoss = new Aliyunoss();
            $aliyunoss->delete_oss($filename);
            return json_encode(['msg' => 'success']);
        } else {
            return json_encode(['msg' => 'fail']);
        }
    }
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
//////        $url = ;
//        ChunJie2019Service::generate($this->openId, $this->avatar, $this->nickname,"/ChunJie2019/bjImg/a.png",'a');
//        ChunJie2019Service::generate($this->openId, $this->avatar, $this->nickname,"/ChunJie2019/bjImg/b.png",'b');
        ChunJie2019Service::generate($this->openId, $this->avatar, $this->nickname,"/ChunJie2019/bjImg/c.png",'c');
//        ChunJie2019Service::generate($this->openId, $this->avatar, $this->nickname,"/ChunJie2019/bjImg/a1.png",'a1');
//        ChunJie2019Service::generate($this->openId, $this->avatar, $this->nickname,"/ChunJie2019/bjImg/b1.png",'b1');
        ChunJie2019Service::generate($this->openId, $this->avatar, $this->nickname,"/ChunJie2019/bjImg/c1.png",'c1');
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

//        Imagine\Image\Box
        $size  = new Box(1080,1920);



        $palette = new RGB();
//        new CMYK();
        $bgi = $imagine->create($size,$palette->color('d02f2d',100));
//        $bgi->usePalette( new CMYK());
        $shoukumai = $imagine->open($publicDisk->path($shoukuanma));
        $shoukumai->resize(new Box(200,200));

        $headi = $imagine->open($publicDisk->path($head));
        $headi->resize(new Box(270,270));
        $color = '#000';//fec71c
//        $image = $imagine->create(new Box(240, 240), $palette->color('#000'));


//        $headi->draw()->ellipse(new Point(85, 85), new Box(190, 190),$palette->color('fff'));
//        $headi->draw()->pieSlice(new Point(85, 85), new Box(180, 180),$palette->color('fff'));
        if ($key =='a'){
            $noBgi = $imagine->open(__DIR__ . $url);
            $noBgi->resize(new Box(1080,1920));

            $bgi->paste($headi, new Point(418,793)); //头像
            $bgi->paste($noBgi, new Point(0,0));



            $bgi->paste($shoukumai, new Point(441, 1444));//背景图

            $font = new Font(__DIR__ . '/ChunJie2018H5/SY.ttf', '36', $palette->color($color)); //昵称信息
            $box = $font->box($nickname);
            $bgi->draw()->text($nickname, $font, new Point(($bgi->getSize()->getWidth() - $box->getWidth())/2,1062), 0, 2);
        }
        if ($key =='b'){

            $noBgi = $imagine->open(__DIR__ . $url);
            $noBgi->resize(new Box(1080,1920));

            $bgi->paste($headi, new Point(417,359));
            $bgi->paste($noBgi, new Point(0,0));
            $bgi->paste($shoukumai, new Point(442, 746));

            $font = new Font(__DIR__ . '/ChunJie2018H5/SY.ttf', '36', $palette->color($color)); //昵称信息
            $box = $font->box($nickname);
            $bgi->draw()->text($nickname, $font, new Point(($bgi->getSize()->getWidth() - $box->getWidth())/2,623), 0, 2);
        }
        if ($key =='c'){

            $noBgi = $imagine->open(__DIR__ . $url);
            $noBgi->resize(new Box(1080,1920));
            $bgi->paste($headi, new Point(422,354));

            $bgi->paste($noBgi, new Point(0,0));
            $bgi->paste($shoukumai, new Point(452, 742));


            $font = new Font(__DIR__ . '/ChunJie2018H5/SY.ttf', '36', $palette->color($color)); //昵称信息
            $box = $font->box($nickname);
            $bgi->draw()->text($nickname, $font, new Point(($bgi->getSize()->getWidth() - $box->getWidth())/2,598), 0, 2);
        }



        if ($key =='a1'){

            $noBgi = $imagine->open(__DIR__ . $url);
            $noBgi->resize(new Box(1080,1920));

            $bgi->paste($headi, new Point(413,860)); //头像
            $bgi->paste($noBgi, new Point(0,0));

            $bgi->paste($shoukumai, new Point(437, 1417));//背景图


            $font = new Font(__DIR__ . '/ChunJie2018H5/SY.ttf', '36', $palette->color($color)); //昵称信息
            $box = $font->box($nickname);
            $bgi->draw()->text($nickname, $font, new Point(($bgi->getSize()->getWidth() - $box->getWidth())/2,1114), 0, 2);
        }
        if ($key =='b1'){

            $noBgi = $imagine->open(__DIR__ . $url);
            $noBgi->resize(new Box(1080,1920));

            $bgi->paste($headi, new Point(408,437));
            $bgi->paste($noBgi, new Point(0,0));

            $bgi->paste($shoukumai, new Point(442, 799));


            $font = new Font(__DIR__ . '/ChunJie2018H5/SY.ttf', '36', $palette->color($color)); //昵称信息
            $box = $font->box($nickname);
            $bgi->draw()->text($nickname, $font, new Point(($bgi->getSize()->getWidth() - $box->getWidth())/2,693), 0, 2);
        }
        if ($key =='c1'){

            $noBgi = $imagine->open(__DIR__ . $url);
            $noBgi->resize(new Box(1080,1920));

            $bgi->paste($headi, new Point(420,435));
            $bgi->paste($noBgi, new Point(0,0));

            $bgi->paste($shoukumai, new Point(452, 814));

            $font = new Font(__DIR__ . '/ChunJie2018H5/SY.ttf', '36', $palette->color($color)); //昵称信息
            $box = $font->box($nickname);
            $bgi->draw()->text($nickname, $font, new Point(($bgi->getSize()->getWidth() - $box->getWidth())/2,690), 0, 2);
        }

        $bgi->resize(new Box(696,1159));
        $users = "users/{$id}{$key}.jpeg";
        $publicDisk->put($users, '');
        $bgi->save(\Storage::disk('public')->path($users));

        \Storage::disk('oss_activity')->put("activity/chunjie2019/users/{$id}{$key}.jpeg", file_get_contents(\Storage::disk('public')->path($users)));

    }
}
