<?php

namespace App\Jobs;

class DemoSingleQueueJob extends SingleQueueJob
{
    public function __construct($identity)
    {
        $this->identity = $identity;
    }

    function business()
    {
        print_r($this->identity);
    }
}
