<?php
namespace App\Libiary\Context\Fact;

use App\Jobs\RegenerateShouKuanQrcode;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;

class FactShouKuanMa extends FactBase
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
        return "fact_shou_kuan_ma";
    }

    public function recordBefore(RegenerateShouKuanQrcode $job)
    {
        $data = $job->recordStart();
        $data['event'] = 'before';
        $this->record($data);
    }

    /**
     * 记录处理完毕的job
     * @param JobProcessed $event
     */
    public function recordAfter(RegenerateShouKuanQrcode $job)
    {
        $data = $job->recordEnd();
        $data['event'] = 'after';
        $this->record($data);
    }
}