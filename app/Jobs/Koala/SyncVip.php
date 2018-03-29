<?php

namespace App\Jobs\Koala;

use App\Jobs\Job;
use App\Services\KoaLaService;
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
        $mobile = $vip->mobile;

        $subject = KoaLaService::subjectGetByName($this->mobile);

        if(empty($subject)){
                KoaLaService::subjectPost(['subject_type' => 0, 'name' => $mobile]);
        }
    }
}
