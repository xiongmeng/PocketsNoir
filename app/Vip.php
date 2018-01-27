<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vip extends Model
{
    const CARD_1 = 1; //普卡
    const CARD_2 = 2; //银卡
    const CARD_3 = 3; //金卡
    const CARD_4 = 4; //砖石卡
    const CARD_5 = 5; //黑卡

    public static $youZanCardMaps = [
        self::CARD_1 => '',
        self::CARD_1 => '',
        self::CARD_1 => '',
        self::CARD_1 => '',
        self::CARD_1 => '',
    ];

    public static $GuanJiaPoCardMaps = [
        self::CARD_1 => '',
        self::CARD_1 => '',
        self::CARD_1 => '',
        self::CARD_1 => '',
        self::CARD_1 => '',
    ];

    /**
     * 主键
     * @var string
     */
    protected $primaryKey = 'mobile';

    protected $table = 'vip';
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
