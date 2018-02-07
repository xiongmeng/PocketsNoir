<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vip extends Model
{
    const CARD_1 = 1;
    const CARD_2 = 2;
    const CARD_3 = 3;
    const CARD_4 = 4;
    const CARD_5 = 5;

    public static $youZanCardMaps = [
        self::CARD_1 => '367ty5es53jmeD', //https://www.youzan.com/scrm/card#edit/367ty5es53jmeD
        self::CARD_2 => '1yf3r9zfic6ueC', //https://www.youzan.com/scrm/card#edit/1yf3r9zfic6ueC
        self::CARD_3 => '3nrozaybu7k3qA', //https://www.youzan.com/scrm/card#edit/3nrozaybu7k3qA
        self::CARD_4 => '3nffmhz2rmfmuC', //https://www.youzan.com/scrm/card#edit/3nffmhz2rmfmuC
        self::CARD_5 => '26vdnev7mbzo6b', //https://www.youzan.com/scrm/card#edit/26vdnev7mbzo6b
    ];

    public static $GuanJiaPoCardMaps = [
        self::CARD_1 => '青口袋',
        self::CARD_2 => '蓝口袋',
        self::CARD_3 => '银口袋',
        self::CARD_4 => '金口袋',
        self::CARD_5 => '黑口袋',
    ];

    const MANUAL_MARK_YOUZAN = 0;
    const MANUAL_MARK_MANUAL = 1;
    const MANUAL_MARK_GUANJIAPO = 2;

    public static $ChannelCardMaps = [
        '普客' => self::CARD_1,
        '机场员工' => self::CARD_2,
//        '多彩筹1份' => self::CARD_2,
        '特殊渠道' => '特殊渠道'
    ];

    public static $channelMaps = [
        '普客' => 0,
        '多彩筹' => 1,
        '机场员工' => 3,
        '特殊渠道' => 99
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

    public static function isYouZanCardOver($cardAlias, $targetCard)
    {
        $alias = array_flip(self::$youZanCardMaps);
        return empty($alias[$cardAlias]) || $alias[$cardAlias] > $targetCard;
    }
}
