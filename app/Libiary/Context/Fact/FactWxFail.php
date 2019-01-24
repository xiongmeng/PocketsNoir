<?php
/**
 * Created by PhpStorm.
 * User: lingchao
 * Date: 2019/1/24
 * Time: 下午4:39
 */
namespace App\Libiary\Context\Fact;

use App\Jobs\RegenerateShouKuanQrcode;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;

class FactWxFail extends FactBase
{
    private static $self = null;

    /**
     * @return null|FactShouKuanMa
     */
    public static function instance()
    {
        if(is_null(self::$self)){
            self::$self = new self();
        }

        return self::$self;
    }

    /**
     * 获取名称
     * @return string
     */
    public function name()
    {
        return "wx_fail";
    }

    public function recordWxFail($all)
    {
        $data = ["wx_fail"=>json_encode($all)];
        $data['event'] = 'wxfail';
        $this->record($data);
    }
}