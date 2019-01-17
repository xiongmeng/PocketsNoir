<?php

namespace App\Jobs;

use App\JobBuffer;
use App\Libiary\Utility\CurlWrapper;
use App\Services\ChunJie2018H5Service;
use App\Services\ChunJie2019Service;
use App\Services\YouZanService;
use EasyWeChat\Kernel\Http\StreamResponse;
use Endroid\QrCode\QrCode;
use Zxing\QrReader;

class RegenerateShouKuanQrcode extends Job
{
    private $openId = null;
    private $serverId = null;
    private $avatar = null;
    private $nickname = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($openId, $serverId, $avatar, $nickname)
    {
        $this->openId = $openId;
        $this->serverId = $serverId;
        $this->avatar = $avatar;
        $this->nickname = $nickname;
    }

    public function handle()
    {
        /** 识别二维码 */
        /** @var StreamResponse $res */


        /** 识别二维码 */
        /** @var StreamResponse $res */
        \Log::info("openId={$this->openId}");
        $shoukuanma = $this->serverId;
        \Log::info("QrReaderReadBegin");
        $reader = new \QrReader($shoukuanma);
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
        ChunJie2019Service::generate($this->openId, $this->avatar, $this->nickname,"/ChunJie2019/bjImg/a1.png",'a1');
        ChunJie2019Service::generate($this->openId, $this->avatar, $this->nickname,"/ChunJie2019/bjImg/b1.png",'b1');
        ChunJie2019Service::generate($this->openId, $this->avatar, $this->nickname,"/ChunJie2019/bjImg/c1.png",'c1');
        \Log::info("LastImgeGenerateEnd");

    }

    /*
     *     $shoukuanma = \EasyWeChat::officialAccount()->media->get($this->serverId);
        \Log::info("QrReaderReadBegin");
        $reader = new \QrReader($shoukuanma->getBodyContents(), \QrReader::SOURCE_TYPE_BLOB);
        $cjt = $reader->text();
        \Log::info("QrReaderReadEnd");
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

\Storage::disk('oss_activity')->put("2018chunjie/shoukuanma/{$this->openId}.jpeg", $content);

        \Log::info("OssPutEnd");

        \Log::info("LastImgeGenerateBegin");
        $user = \EasyWeChat::officialAccount()->user->get($this->openId);
        ChunJie2018H5Service::generate($this->openId, $this->avatar, isset($user['nickname']) ? $user['nickname'] : $this->nickname);
        \Log::info("LastImgeGenerateEnd");
     *
     * */
}
