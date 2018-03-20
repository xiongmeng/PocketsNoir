<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'youzan/push',
        'guanjiapo/push',
        'zulin/push',
        'shoukuanma',
        'generate',
        'refreshCard',
        'vip/face/import',
        'vip/mobile/code'
    ];
}
