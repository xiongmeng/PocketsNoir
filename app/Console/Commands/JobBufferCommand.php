<?php 
namespace App\Console\Commands;

use App\JobBuffer;
use App\Jobs\DisposeChangesWithYZUid;
use App\Jobs\RecalculateVip;
use App\Jobs\YouZanCardActivatedQuery;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class JobBufferCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'job:buffer {step : The step to dispose}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'dispatch jobs from database buffer';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $step = $this->argument('step');

        $identity = self::class . ": ";
        \Log::info($identity . 'start');

        JobBuffer::whereStatus(JobBuffer::STATUS_IDLE)
            ->groupBy(['job_name', 'job_id'])->select([
                'job_name',
                'job_id',
                \DB::raw('GROUP_CONCAT(id) as ids, min(created_at) as minCreated')
            ])->orderBy('minCreated', 'asc')->chunk($step, function (Collection $list)use($identity){
                \Log::info($identity . "got items count : " . $list->count());

                foreach ($list as $item){
                    \Log::info($identity . "dispose item : " . json_encode($item->toArray()));

                    $name = $item['job_name'];
                    $id = $item['job_id'];

//                    修改状态为分派中
                    $rows = JobBuffer::whereJobName($name)->whereJobId($id)->whereStatus(JobBuffer::STATUS_IDLE)
                        ->whereIn('id', explode(',', $item['ids']))->update(['status' => JobBuffer::STATUS_DISPATCHING]);

                    \Log::info($identity . "update dispatching items' count : " . $rows);
                    if($rows > 0){
                        switch ($name){
                            case DisposeChangesWithYZUid::class:
                                dispatch(new DisposeChangesWithYZUid($id))->onConnection('sync');
                                break;
                            case RecalculateVip::class:
                                dispatch(new RecalculateVip($id))->onConnection('sync');
                                break;
                            case YouZanCardActivatedQuery::class:
                                dispatch(new YouZanCardActivatedQuery($id))->onConnection('sync');
                                break;
                            default:
                                break;
                        }
                    }

//                    修改状态为已分派
                    $rows = JobBuffer::whereJobName($name)->whereJobId($id)->whereStatus(JobBuffer::STATUS_DISPATCHING)
                        ->whereIn('id', explode(',', $item['ids']))->update(['status' => JobBuffer::STATUS_DISPATCHED]);

                    \Log::info($identity . "update dispatched items' count : " . $rows);
                }
            });

        \Log::info($identity . ' end');
    }
}
