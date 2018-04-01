<?php

namespace App\Libiary\Utility;

class IsHelper
{
    public static function isMobile($mobile)
    {
        if (!is_numeric($mobile)) {
            return false;
        }
//        $regular = <<<REGULAR
//#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,1,3,6,7,8]{1}\d{8}$|^18[\d]{9}$#
//REGULAR;

        $regular = <<<REGULAR
        /^1[3|4|5|6|7|8|9]\d{9}$/
REGULAR;

        return preg_match($regular, $mobile);
    }
}
