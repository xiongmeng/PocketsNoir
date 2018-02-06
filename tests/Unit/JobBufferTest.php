<?php

namespace Tests\Unit;

use App\JobBuffer;
use App\Jobs\DisposeChangesWithYZUid;
use App\Jobs\DisposeGuanJiaPoPush;
use App\Jobs\RecalculateVip;
use App\Jobs\SyncVip;
use App\Vip;
use function foo\func;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class JobBufferTest extends TestCase
{
    public function testYZTradeEvent()
    {
        JobBuffer::whereStatus(JobBuffer::STATUS_IDLE)
            ->groupBy(['job_name', 'job_id'])->select([
                'job_name',
                'job_id',
                \DB::raw('GROUP_CONCAT(id) as ids, min(created_at) as minCreated')
            ])->orderBy('minCreated', 'asc')->chunk(10, function (Collection $list){
                foreach ($list as $item){
                    $name = $item['job_name'];
                    $id = $item['job_id'];
                    $rows = JobBuffer::whereJobName($name)->whereJobId($id)->whereStatus(JobBuffer::STATUS_IDLE)
                        ->whereIn('id', explode(',', $item['ids']))->update(['status' => JobBuffer::STATUS_DISPATCHING]);
                    if($rows > 0){
                        switch ($name){
                            case DisposeChangesWithYZUid::class:
                                dispatch(new DisposeChangesWithYZUid($id))->onConnection('sync');
                                break;
                            case RecalculateVip::class:
                                dispatch(new RecalculateVip($id))->onConnection('sync');
                                break;
                            default:
                                break;
                        }
                    }
                    $rows = JobBuffer::whereJobName($name)->whereJobId($id)->whereStatus(JobBuffer::STATUS_DISPATCHING)
                        ->whereIn('id', explode(',', $item['ids']))->update(['status' => JobBuffer::STATUS_DISPATCHED]);
                }

                $s = 'hello';
                echo $s;
        });
    }
}
