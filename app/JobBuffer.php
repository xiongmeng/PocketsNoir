<?php

namespace App;

use App\Jobs\DisposeChangesWithYZUid;
use App\Jobs\RecalculateVip;
use App\Jobs\SingleRecalculateVip;
use App\Jobs\YouZanCardActivatedQuery;
use Illuminate\Database\Eloquent\Model;

class JobBuffer extends Model
{
    const STATUS_IDLE = 'idle';
    const STATUS_DISPATCHING = 'dispatching';
    const STATUS_DISPATCHED = 'dispatched';

    /**
     * 主键
     * @var string
     */
    protected $primaryKey = 'id';

    protected $table = 'job_buffer';
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

    public static function addYouZanCardActivatedQuery($cardNo)
    {
        self::add(YouZanCardActivatedQuery::class, $cardNo);
    }

    public static function addYouZanParseUid($yzUid)
    {
        self::add(DisposeChangesWithYZUid::class, $yzUid);
    }

    public static function add($jobName, $jobId)
    {
        $model = new JobBuffer();
        $model->job_name = $jobName;
        $model->job_id = $jobId;
        $model->status = self::STATUS_IDLE;
        $model->save();
    }

    public static function dispatch($jobName, $jobId)
    {
        switch ($jobName){
            case DisposeChangesWithYZUid::class:
                dispatch(new DisposeChangesWithYZUid($jobId))->onConnection('sync');
                break;
            case YouZanCardActivatedQuery::class:
                dispatch(new YouZanCardActivatedQuery($jobId))->onConnection('sync');
                break;
            default:
                break;
        }
    }
}
