<?php 
namespace App\Console\Commands;

use App\JobBuffer;
use App\Jobs\DisposeChangesWithYZUid;
use App\Jobs\RecalculateVip;
use App\Jobs\YouZanCardActivatedQuery;
use App\Libiary\Context\Dimension\DimExecution;
use App\Libiary\Context\Fact\FactException;
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

                    try{
                        $rows = self::dispatchingItem($name, $id, $item['ids']);
                        if($rows <= 0){
                            throw new \Exception("dispatching items' count less than 0！");
                        }

                        JobBuffer::dispatch($name, $id);

                        $rows = self::dispatchedItem($name, $id, $item['ids']);

                        if($rows <= 0){
                            throw new \Exception("dispatched items' count less than 0！");
                        }
                    }catch(\Exception $e){
                        $rows = self::dispatchingItemFailed($name, $id, $item['ids']);

                        FactException::instance()->recordException($e, "JobBufferDispatchFailed-RevertRows{$rows}");
                    }
                }
            });
    }

    private static function dispatchingItem($jobName, $jobId, $ids)
    {
        return JobBuffer::whereJobName($jobName)->whereJobId($jobId)->whereStatus(JobBuffer::STATUS_IDLE)
            ->whereIn('id', explode(',', $ids))->update(['status' => JobBuffer::STATUS_DISPATCHING]);
    }

    private static function dispatchedItem($jobName, $jobId, $ids)
    {
        return JobBuffer::whereJobName($jobName)->whereJobId($jobId)->whereStatus(JobBuffer::STATUS_DISPATCHING)
            ->whereIn('id', explode(',', $ids))->update(['status' => JobBuffer::STATUS_DISPATCHED]);
    }

    private static function dispatchingItemFailed($jobName, $jobId, $ids)
    {
        return JobBuffer::whereJobName($jobName)->whereJobId($jobId)->whereStatus(JobBuffer::STATUS_DISPATCHING)
            ->whereIn('id', explode(',', $ids))->update(['status' => JobBuffer::STATUS_IDLE]);
    }
}
