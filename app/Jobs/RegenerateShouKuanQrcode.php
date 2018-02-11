<?php

namespace App\Jobs;

use App\JobBuffer;
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
        /** @var StreamResponse $res */
        $res = \EasyWeChat::officialAccount()->media->get($this->serverId);
//        $res->save(__DIR__);

        $user = \EasyWeChat::officialAccount()->user->get($this->openId);
//        $reader = new \QrReader($res->getBodyContents(), \QrReader::SOURCE_TYPE_BLOB);
//        $cjt = $reader->text();

        $cjt='aaaa';

        $writer = new QrCode($cjt);
        $writer->setSize(300);
        $writer->setWriterByName('png');
        $writer->setMargin(1);
        $writer->setLogoPath();
        $writer->setLogoWidth(10);

        $writer->writeFile(__DIR__ . '/cj3.jpg');
    }
}
