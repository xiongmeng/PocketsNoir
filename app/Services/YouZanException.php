<?php

namespace App\Services;

class YouZanException extends \Exception
{
    public function __construct(array $errorResponse)
    {
        parent::__construct($errorResponse['msg'], $errorResponse['code'], null);
    }
}
