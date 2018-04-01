<?php

namespace App;

use App\Jobs\SingleRecalculateVip;
use App\Libiary\Utility\IsHelper;
use App\Services\YouZanService;
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
    const MANUAL_MARK_DUOCHATOU = 1;
    const MANUAL_MARK_GUANJIAPO = 2;
    const MANUAL_MARK_JICHANGYG = 3;
    const MANUAL_MARK_ADMIN=99;
    const MANUAL_MARK_ZULIN=98;
    const MANUAL_MARK_JICHANG=97;

    public static $ChannelCardMaps = [
        '普客' => self::CARD_1,
        '机场员工' => self::CARD_4,
//        '多彩筹1份' => self::CARD_2,
        '特殊渠道' => '特殊渠道'
    ];

    public static $jiChangChannelCardMaps = [
        '机场员工' => self::CARD_4,
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

    public $incrementing = false;

    public static function isYouZanCardOver($cardAlias, $targetCard)
    {
        $alias = array_flip(self::$youZanCardMaps);
        return empty($alias[$cardAlias]) || $alias[$cardAlias] > $targetCard;
    }

    /**
     * 给机场员工发卡-金卡
     * @param $mobile
     */
    public static function createForJiChangYG($mobile)
    {
        YouZanService::ensureCustomerExisted($mobile);
        return self::insertOrUpdate($mobile, self::CARD_4, self::MANUAL_MARK_JICHANGYG, false);
    }

    /**
     * 给多彩投用户发卡-金卡or银卡
     * @param $mobile
     * @param $card
     */
    public static function createForDuoChaiTou($mobile, $card)
    {
        if(in_array($card, [self::CARD_4, self::CARD_3])){
            $card = self::CARD_1;
        }
        YouZanService::ensureCustomerExisted($mobile);
        return self::insertOrUpdate($mobile, $card, self::MANUAL_MARK_DUOCHATOU, false);
    }

    public static function createFromYouZan($mobile, $recalculate = true)
    {
        return self::insertOrUpdate($mobile, self::CARD_1, self::MANUAL_MARK_YOUZAN, $recalculate);
    }

    public static function createFromZuLin($mobile, $recalculate = true)
    {
        YouZanService::ensureCustomerExisted($mobile);
        return self::insertOrUpdate($mobile, self::CARD_1, self::MANUAL_MARK_ZULIN, false);
    }

    public static function createFromJiChang($mobile, $recalculate = true)
    {
        YouZanService::ensureCustomerExisted($mobile);
        return self::insertOrUpdate($mobile, self::CARD_1, self::MANUAL_MARK_JICHANG, false);
    }

    /**
     * 创建普通卡
     * @param $mobile
     */
    public static function createFromAdmin($mobile, $recalculate = true)
    {
        YouZanService::ensureCustomerExisted($mobile);
        return self::insertOrUpdate($mobile, self::CARD_1, self::MANUAL_MARK_ADMIN, false);
    }

    /**
     * 新增一个会员，如果成功则可以设置是否同步到其他业务系统
     * @param $mobile
     * @param $card
     * @param $manualMarked
     * @param bool $recalculate
     * @return Vip|\Illuminate\Database\Eloquent\Collection|Model|null|static|static[]
     * @throws \Exception
     */
    private static function insertOrUpdate($mobile, $card, $manualMarked, $recalculate = true)
    {
        if(!IsHelper::isMobile($mobile)){
            throw new \Exception("请输入合法的手机号{$mobile}");
        }

        $vip = Vip::find($mobile);
        if(empty($vip)){
            $vip = new Vip();
            $vip->mobile = $mobile;
        }
        $vip->card = $card;
        $vip->manual_marked = $manualMarked;

        $vip->save();

        $recalculate && dispatch(new SingleRecalculateVip($mobile));

        return $vip;
    }

}
