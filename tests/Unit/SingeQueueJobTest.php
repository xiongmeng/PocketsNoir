<?php

namespace Tests\Unit;

use App\Jobs\DemoSingleQueueJob;
use Tests\TestCase;

class SingeQueueJobTest extends TestCase
{
    public function testDispatch()
    {
        dispatch(new DemoSingleQueueJob(1));
    }
}
