<?php
/**
 * Created by PhpStorm.
 * User: fangyushuai
 * Date: 2018/5/23
 * Time: 上午11:28
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class LotteryPresent extends Model
{
    protected $connection = 'mysql';

    protected $table = 'lottery_present';

    public $timestamps = false;

    protected $fillable = [

        'activity_id','present_name','status',
    ];
}