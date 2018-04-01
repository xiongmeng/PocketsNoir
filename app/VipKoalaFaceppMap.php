<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VipKoalaFaceppMap extends Model
{
    /**
     * 主键
     * @var string
     */
    protected $primaryKey = 'mobile';

    protected $table = 'vip_koala_facepp_map';
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
}
