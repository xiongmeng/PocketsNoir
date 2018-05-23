<?php
/**
 * Created by PhpStorm.
 * User: fangyushuai
 * Date: 2018/5/23
 * Time: 下午4:02
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
class LotteryCoupon extends Model
{
    protected $connection = 'mysql';

    protected $table = 'lottery_coupon';

    public $timestamps = false;

    protected $fillable = [
      'coupon_id','coupon_name','status'
    ];
}