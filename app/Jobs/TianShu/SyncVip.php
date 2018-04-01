<?php

namespace App\Jobs\TianShu;

use App\Jobs\Job;
use App\Services\KoaLaService;
use App\Services\TianShuService;
use App\Vip;

class SyncVip extends Job
{
    private $mobile = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mobile)
    {
        $this->mobile = $mobile;
    }

    public function handle()
    {
        /** @var Vip $vip */
        $vip = Vip::find($this->mobile);

        TianShuService::syncVip($vip);
    }
}
