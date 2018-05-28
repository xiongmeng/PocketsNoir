<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::any('/dispatchCardForJiChang', function () {
    $method = strtoupper(request()->method());
    if ($method == 'POST') {
        $mobile = request()->post('mobile');
        $vip = \App\Vip::find($mobile);
        if(!empty($vip)){
            $vip->card = \App\Vip::CARD_4;
            $vip->manual_marked = \App\Vip::MANUAL_MARK_JICHANGYG;
            $vip->save();
        }
        $vip = \App\Vip::createForJiChangYG(request()->post('mobile'));
        return response()->json($vip->toArray());
    }else{
        return view('dispatchCardForJiChang');
    }
});

Route::any('/createCard', function () {
    $method = strtoupper(request()->method());
    if ($method == 'POST') {
        $vip = \App\Vip::createFromAdmin(request()->post('mobile'));
        return response()->json($vip->toArray());
    } else {
        return view('refreshCard');
    }
});

Route::any('/refreshCard', function () {
    $method = strtoupper(request()->method());
    if ($method == 'POST') {
        $mobile = request()->post('mobile');
        dispatch(new \App\Jobs\SingleRecalculateVip($mobile));
        $vip = \App\Vip::find($mobile);
        return response()->json($vip->toArray());
    } else {
        return view('refreshCard');
    }
});

Route::post('/youzan/push', function () {
    try {
        $rawPostData = file_get_contents("php://input");
        dispatch(new \App\Jobs\DisposeYouZanPush($rawPostData))->onConnection('sync');
    } catch (Exception $e) {
        Log::info($e);
    }

    return response('{"code":0,"msg":"success"}', 200, ['content_type' => 'text/plain']);
});

Route::post('/guanjiapo/push', function () {
    try {
        dispatch(new \App\Jobs\DisposeGuanJiaPoPush(request()->post()))->onConnection('sync');
    } catch (Exception $e) {
        \App\Libiary\Context\Fact\FactException::instance()->recordException($e);
    }
    return response('{"code":0,"msg":"success"}', 200, ['content_type' => 'text/plain']);
});

Route::post('/zulin/push', function () {
    try {
        dispatch(new \App\Jobs\DisposeZuLinPush(request()->post()))->onConnection('sync');
    } catch (Exception $e) {
        \App\Libiary\Context\Fact\FactException::instance()->recordException($e);
    }
    return response('{"code":0,"msg":"success"}', 200, ['content_type' => 'text/plain']);
});

Route::post('/vip/face/importBase64', function () {
//    header("Access-Control-Allow-Origin: *");

    $base64_image_content = $_POST['imgBase64'];
//匹配出图片的格式
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
        $content = base64_decode(str_replace($result[1], '', $base64_image_content));

        $temp = tmpfile();
        fwrite($temp, $content);
        $res = \App\Services\VipFaceImportService::detectFormFile($temp);
        is_resource($temp) && fclose($temp);

        return $res;
    } else {
        throw new Exception("base64格式不正确！");
    }
});

Route::post('/vip/face/import', function () {
//    header("Access-Control-Allow-Origin: *");
    if (empty($_FILES['file'])) {
        throw new Exception("未发现文件内容！");
    }
    $res = \App\Services\VipFaceImportService::detectFormFile($_FILES['file']['tmp_name']);
    return $res;
});

Route::post('/vip/mobile/code', function () {
    $mobile = request()->post('mobile');

    if (empty($mobile)) {
        throw new Exception("必须传入手机号!");
    }

    $cacheKey = "vip_mobile_code_$mobile";
    $cacheExpired = "vip_mobile_expired_$mobile";
    if (Cache::has($cacheExpired)) {
        throw new Exception("一分钟内不能重复发送验证码！");
    }

    $code = rand(100000, 999999);
    Cache::put($cacheExpired, '', 1);
    Cache::put($cacheKey, $code, 5);

    $aliSms = new \Mrgoon\AliSms\AliSms();
    $response = $aliSms->sendSms($mobile, 'SMS_111890588', ['code' => $code]);
    return $response;
});

Route::post('/vip/checkin', function () {
//    header("Access-Control-Allow-Origin: *");

    $code = request()->post('code');
    $mobile = request()->post('mobile');
    $faceId = request()->post('face_id');

    if (empty($code)) {
        throw new Exception("短信验证码不能为空!");
    }
    if (empty($mobile)) {
        throw new Exception("必须传入手机号!");
    }
    if (empty($faceId)) {
        throw new Exception("必须传入人脸Id!");
    }

    $cacheKey = "vip_mobile_code_$mobile";
    $codeExpected = Cache::get($cacheKey);
    if (empty($codeExpected)) {
        throw new Exception("短信验证码不存在或已过期，请重新获取！");
    }
    if ($codeExpected <> $code) {
        throw new Exception("验证码输入错误！");
    }

    \App\Vip::createFromJiChang($mobile);
    $res = \App\Services\VipFaceImportService::bindVipFace($faceId, $mobile);

    return $res;
});



Route::get('/jsCfg', function (){
    $refer = URL::previous();
    $json = EasyWeChat::officialAccount()->jssdk
        ->setUrl($refer)
        ->buildConfig(array("onMenuShareTimeline","onMenuShareAppMessage","onMenuShareQQ","onMenuShareWeibo","onMenuShareQZone","startRecord","stopRecord","onVoiceRecordEnd","playVoice","pauseVoice","stopVoice","onVoicePlayEnd","uploadVoice","downloadVoice","chooseImage","previewImage","uploadImage","downloadImage","translateVoice","getNetworkType","openLocation","getLocation","hideOptionMenu","showOptionMenu","hideMenuItems","showMenuItems","hideAllNonBaseMenuItem","showAllNonBaseMenuItem","closeWindow","scanQRCode","chooseWXPay","openProductSpecificView","addCard","chooseCard","openCard"), false);
    return response()->json(json_decode($json, true));
});

Route::group(['middleware' => ['wechat.oauth:snsapi_userinfo']], function () {
    Route::get('/entry', function () {
        /** @var $user \Overtrue\Socialite\User */
//    $user = session('wechat.oauth_user.default'); // 拿到授权用户资料

        return view('2018chunjie.entry', []);
    });

    Route::get('/auth', function () {
        /** @var $user \Overtrue\Socialite\User */
        $user = session('wechat.oauth_user.default'); // 拿到授权用户资料

        return response()->json($user);
    });
});