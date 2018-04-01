<?php

namespace App\Jobs\FacePlusPlus;

use App\Jobs\Job;
use App\Services\FacePlusPlusService;
use App\Services\KoaLaService;
use App\VipKoalaFaceppMap;

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
        /** @var VipKoalaFaceppMap $map */
        $map = VipKoalaFaceppMap::find($this->mobile);
        if(empty($map->koala_photo_id)){
            return;
        }
        $subject = KoaLaService::subjectGet($map->koala_id);
        if(empty($subject['photos'])){
            return;
        }

        $koalaPhoto=$subject['photos'][0];
        if($map->face_token){
            FacePlusPlusService::facesetRemoveface($map->face_token, $map->faceset_outer_id);
        }
        $faceDetect = FacePlusPlusService::detectOnlyOneFace($koalaPhoto['url']);
        $faceToken = $faceDetect['face_token'];
        $map->face_token = $faceToken;
        $res = FacePlusPlusService::facesetCreate($faceToken);
        $map->faceset_outer_id =$res['outer_id'];
        $map->save();

//        这个接口一用就报错
//        FacePlusPlusService::faceSetuserid($faceToken, $this->mobile);
    }
}
