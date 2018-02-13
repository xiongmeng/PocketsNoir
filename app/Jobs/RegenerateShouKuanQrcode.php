<?php

namespace App\Jobs;

use App\JobBuffer;
use App\Libiary\Utility\CurlWrapper;
use App\Services\ChunJie2018H5Service;
use App\Services\YouZanService;
use EasyWeChat\Kernel\Http\StreamResponse;
use Endroid\QrCode\QrCode;

class RegenerateShouKuanQrcode extends Job
{
    private $openId = null;
    private $serverId = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($openId, $serverId)
    {
        $this->openId = $openId;
        $this->serverId = $serverId;
    }

    public function handle()
    {
        /** 识别二维码 */
        /** @var StreamResponse $res */
        $shoukuanma = \EasyWeChat::officialAccount()->media->get($this->serverId);
        \Log::info("QrReaderReadBegin");
        $reader = new \QrReader($shoukuanma->getBodyContents(), \QrReader::SOURCE_TYPE_BLOB);
        $cjt = $reader->text();
        \Log::info("QrReaderReadEnd");

        /** @var 获取头像并生成新二维码 $user */
        $user = \EasyWeChat::officialAccount()->user->get($this->openId);
        $publicDisk = \Storage::disk('public');
        \Log::info("GetHeadImgUrlBegin");
        $headContent = CurlWrapper::curlGet($user['headimgurl']);
        $file = "{$user['openid']}.jpeg";
        $publicDisk->put($file, $headContent);
        \Log::info("GetHeadImgUrlEnd");

        $headPath = $publicDisk->path($file);

        \Log::info("QrCodeBegin");
        $writer = new QrCode($cjt);
        $writer->setSize(237);
        $writer->setWriterByName('png');
        $writer->setMargin(1);
        $writer->setLogoPath($headPath);
        $writer->setLogoWidth(40);
        $content = $writer->writeString();
        \Log::info("QrCodeEnd");

        \Log::info("OssPutBegin");
        /** 保存新二维码到阿里云 */
        \Storage::disk('oss_activity')->put("2018chunjie/shoukuanma/{$this->openId}.jpeg", $content);

        \Log::info("OssPutEnd");

        \Log::info("LastImgeGenerateBegin");
        ChunJie2018H5Service::generate($this->openId, $user['headimgurl'], $user['nickname']);
        \Log::info("LastImgeGenerateEnd");
    }
}
