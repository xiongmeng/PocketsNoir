<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class YzUidMobileMap extends Model
{
    /**
     * 主键
     * @var string
     */
    protected $primaryKey = 'yz_uid';

    protected $table = 'yz_uid_mobile_map';
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
