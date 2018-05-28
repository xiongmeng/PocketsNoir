<?php 
namespace App\Console\Commands;

use App\JobBuffer;
use App\Jobs\DisposeChangesWithYZUid;
use App\Jobs\RecalculateVip;
use App\Jobs\YouZanCardActivatedQuery;
use App\Libiary\Context\Dimension\DimExecution;
use App\Libiary\Context\Fact\FactException;
use App\LotteryMember;
use App\Services\LotteryService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class DuiJiangQuery extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'duijiang {step : The step to dispose}';

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

        LotteryMember::where('status' , '=', 1)->where('created_at', '>', date('Y-m-d H:i:s', time()-3600))
            ->orderBy('created_at', 'desc')->chunk($step, function (Collection $list){
            foreach ($list as $item){
              LotteryService::sendLotteryByMobile($item['mobile']);
            }
        });
    }
}
