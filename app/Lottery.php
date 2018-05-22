<?php
/**
 * Created by PhpStorm.
 * User: fangyushuai
 * Date: 2018/5/21
 * Time: 下午1:41
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
class Lottery extends Model
{
    protected $connection = 'mysql';

    protected $table = 'lottery';

    public $timestamps = false;

    protected $fillable = [
        'shop','first_prize','second_prize','third_prize','forth_prize','second_prize_total','status',
    ];
}
