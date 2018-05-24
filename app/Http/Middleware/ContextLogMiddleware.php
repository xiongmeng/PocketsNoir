<?php

namespace App\Http\Middleware;

use App\Libiary\Context\Dimension\DimExecution;
use Closure;
use Symfony\Component\HttpFoundation\Response;

class ContextLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        if($response instanceof Response){
            $contentType = $response->headers->get('content-type');

            $content = in_array($contentType, ['application/xml', 'application/json', 'text/plain']) ? $response->getContent() : 'ignore_because_content_type';

            DimExecution::instance()->recordResponse(
                $content, $contentType, $response->getStatusCode()
            );
        }

        return $response;
    }
}
