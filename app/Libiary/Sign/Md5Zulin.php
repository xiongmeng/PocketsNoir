<?php

namespace App\Libiary\Sign;

class Md5Zulin
{
    #算签名
    public function verify($params, $appsecret)
    {
        $sign = $params['sign'];
        unset($params['sign']);
        if ($sign !== $this->sign($params, $appsecret)) {
            throw new \Exception("sign verify error!");
        }
    }

    public function sign($params, $appsecret)
    {
        unset($params['sign']);
        $stringPrepare = $this->getSignContent($params);
        $stringToBeSigned = "{$stringPrepare}&key={$appsecret}";
        return strtoupper(md5($stringToBeSigned));
    }

    #排序参数
    public function getSignContent($params)
    {
        ksort ( $params );
        $stringToBeSigned = "";
        $i = 0;
        foreach ( $params as $k => $v ) {
            if (is_scalar($v) && false === $this->checkEmpty ( $v ) && "@" != substr ( $v, 0, 1 )) {
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i ++;
            }
        }
        unset ( $k, $v );
        return $stringToBeSigned;
    }
    /**
     * 校验$value是否非空
     * if not set ,return true;
     * if is null , return true;
     */
    public function checkEmpty($value) {
        if (! isset ( $value ))
            return true;
        if ($value === null)
            return true;
        if (trim ( $value ) === "")
            return true;
        return false;
    }
}
