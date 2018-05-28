<?php
/**
 * Created by PhpStorm.
 * User: fangyushuai
 * Date: 2018/5/22
 * Time: 下午4:12
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class LotteryMember extends Model
{
    protected $connection = 'mysql';

    protected $table = 'lottery_member';

    public $timestamps = true;

    protected $fillable = [

        'shop_id','present_id','imageID','phone','member_name','status'
    ];
}