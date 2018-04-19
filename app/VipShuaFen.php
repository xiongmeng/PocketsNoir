<?php

namespace App;

use App\Jobs\SingleRecalculateVip;
use App\Libiary\Utility\IsHelper;
use App\Services\YouZanService;
use Illuminate\Database\Eloquent\Model;

class VipShuaFen extends Model
{
    /**
     * 主键
     * @var string
     */
    protected $primaryKey = 'mobile';

    protected $table = 'vip_shuafen';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
//        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
//        'password', 'remember_token',
    ];

    public $incrementing = false;

}
