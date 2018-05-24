<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class DataFormatMiddleware
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

        if(!$response instanceof Response){
            throw new \Exception("response must be instace of Response");
        }

        if($response->headers->get('content-type', '') == 'application/json'){
            $status = 0;
            $msg = '';

//            根据异常设置相应的error和msg
            $originalData = null;
            if($response->exception && $response->exception instanceof \Exception){
                $this->customException($response->exception, $status, $msg, $originalData);
            }else{
                $originalData = $response->getContent();
            }
            $originalData = json_decode($originalData, true);

//        重新设置content
            $response->setContent(json_encode(['status' => $status, 'msg' => $msg, 'data' => $originalData]));
        }

        return $response;
    }

    /**
     * 自定义异常
     */
    private function customException(\Exception $e, &$status, &$msg, &$data)
    {
        $status = $e->getCode();
        $status == 0 && $status = 1000;

        $msg = $e->getMessage();

        if ($e instanceof HttpResponseException) {
            $data = $e->getResponse()->getContent();
        }elseif ($e instanceof ValidationException && $e->getResponse()) {
            $data = $e->getResponse()->getContent();
            $msg = '存在不合法的或未填写的参数';
        }elseif($e instanceof MethodNotAllowedHttpException){
            $msg = '接口不支持此方法';
        }
    }
}
