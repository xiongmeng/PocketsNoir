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
//    return 'welcome';
});

Route::any('/dispatchCard', function (){
    $method = strtoupper(request()->method());
    if($method == 'POST'){
        $posts = request()->post();
        $channel = $posts['channel'];
        if(empty($channel)){
            throw new Exception('渠道不能为空！');
        }
        $mobile = $posts['mobile'];
        if(empty($mobile)){
            throw new Exception('手机号不能为空！');
        }

        $card = $posts['card'];
        if(!empty($card)){
            if($channel <> '特殊渠道'){
                throw new Exception('必须选特殊渠道才能自定义选卡！');
            }
        }else{
            if(empty(\App\Vip::$ChannelCardMaps[$channel])){
                throw new Exception('对应的渠道不存在相对应的卡！');
            }
            $card = \App\Vip::$ChannelCardMaps[$channel];
        }

        if(!isset(\App\Vip::$channelMaps[$channel])){
            throw new Exception("对应的渠道{$channel}不存在渠道码值(参考channelMaps)");
        }

        $vip = \App\Vip::find($mobile);
        if(empty($vip)){
            $vip = new \App\Vip();
            $vip->mobile = $mobile;
            $vip->card = $card;
            $vip->manual_marked = \App\Vip::$channelMaps[$channel];
            $vip->save();
        }else{
            Log::info("AdjustCardLevelAsCardExisted", ['before' => $vip->toArray(),
                'now' => ['card'=>$card, 'manual_marked' => \App\Vip::$channelMaps[$channel]]]);
            $vip->card = $card;
            $vip->manual_marked = \App\Vip::$channelMaps[$channel];
//            throw new Exception("会员卡已经存在！" . json_encode($vip->toArray()));
            $vip->save();
        }

        dispatch(new \App\Jobs\RecalculateVip($mobile))->onConnection('sync');

        $vip = \App\Vip::find($mobile);
        return response()->json($vip->toArray());
    }else{
        return view('dispatchCard');
    }
});

Route::get('/dispatchCardFotJiChang', function (){
    return view('dispatchCardForJiChang');
});

Route::any('/refreshCard', function (){
    $method = strtoupper(request()->method());
    if($method == 'POST'){
        $mobile = request()->post('mobile');
        dispatch(new \App\Jobs\RecalculateVip($mobile))->onConnection('sync');
        $vip = \App\Vip::find($mobile);
        return response()->json($vip->toArray());
    }else{
        return view('refreshCard');
    }
});

Route::post('/youzan/push', function () {
    try{
        $rawPostData = file_get_contents("php://input");
        dispatch(new \App\Jobs\DisposeYouZanPush($rawPostData))->onConnection('sync');
    }catch (Exception $e){
        Log::info($e);
    }

    return response('{"code":0,"msg":"success"}', 200, ['content_type' => 'text/plain']);
});

Route::post('/guanjiapo/push', function(){
    try{
        dispatch(new \App\Jobs\DisposeGuanJiaPoPush(request()->post()))->onConnection('sync');
    }catch (Exception $e){
        \App\Libiary\Context\Fact\FactException::instance()->recordException($e);
    }
    return response('{"code":0,"msg":"success"}', 200, ['content_type' => 'text/plain']);
});

Route::get('/ab', function (){
    return response('{"code":0,"msg":"success"}', 200, ['content_type' => 'text/plain']);
});
Route::post('/code', function () {
    $code = request()->post('code');
    if (!isset($code)) return response('{"code":1000,"msg":"code不能为空！"}', 200, ['content_type' => 'text/plain']);

    $data = [
        "appid"=>env("WECHAT_MINI_PROGRAM_APPID"),
        "secret"=>env("WECHAT_MINI_PROGRAM_SECRET"),
        "js_code"=>$code,
        "grant_type"=>"authorization_code"
        ];
    $url = http_build_query($data);
    $user = \App\Libiary\Utility\CurlWrapper::get($data,"https://api.weixin.qq.com/sns/jscode2session?{$url}");
    return  response($user);
});
Route::post('/2019chunjieshoukuanma', function () {
    $openId = request()->post('openId');
    $img = request()->post('img');
    $avatarUrl = request()->post('avatarUrl');
    $nickName = request()->post('nickName');
//    var_dump($openId);die;
    if (!isset($openId)) return response('{"code":1000,"msg":{'.$openId.'}}', 200, ['content_type' => 'text/plain']);
    if (!isset($img)) return response('{"code":1000,"msg":"img不能为空！"}', 200, ['content_type' => 'text/plain']);
    if (!isset($avatarUrl)) return response('{"code":1000,"msg":"avatarUrl不能为空！"}', 200, ['content_type' => 'text/plain']);
    if (!isset($nickName)) return response('{"code":1000,"msg":"nickName不能为空！"}', 200, ['content_type' => 'text/plain']);
    $user=['openId'=>$openId];
    $img = str_replace('wxfile://','https://public-document.oss-cn-shenzhen.aliyuncs.com/activity/chunjie2019/shoukuanma/',$img);
    $nickName = str_replace(" ","",$nickName);
    Log::info("$openId,$img,$avatarUrl,$nickName");
    $arr = ['a','a1','b','b1','c','c1'];
    foreach ($arr as $item){
        $result = true;
        if ($item == 'a'){
            $result = \App\Services\ChunJie2019Service::delete_oss("activity/chunjie2019/users/{$openId}{$item}.jpeg");
            if ($result)  Log::info("delete={$openId}");
        }
        if ($result){

            \App\Services\ChunJie2019Service::delete_oss("activity/chunjie2019/users/{$openId}{$item}.jpeg");
        }
    }
    dispatch(new \App\Jobs\RegenerateShouKuanQrcode($openId, $img, $avatarUrl, $nickName))->onConnection('database')->onQueue('h5');
//    (new \App\Services\ChunJie2019Service())->ceshi($openId, $img, $avatarUrl, $nickName);
    return response($user);
});

Route::group(['middleware' => ['wechat.oauth:snsapi_userinfo']], function () {
    Route::get('/entry', function () {
        /** @var $user \Overtrue\Socialite\User */
        $user = session('wechat.oauth_user.default'); // 拿到授权用户资料

        $file = "2018chunjie/users/{$user->getId()}.jpeg";
        $ossDisk = \Storage::disk('oss_activity');
        if(0 && $ossDisk->exists($file)){
            return view('2018chunjie.entry', ['user' => $user]);
        }else{
            return view('2018chunjie.codeimg', ['user' => $user]);
        }
    });

    Route::get('/codeimg', function () {
        /** @var $user \Overtrue\Socialite\User */
        $user = session('wechat.oauth_user.default'); // 拿到授权用户资料
        return view('2018chunjie.codeimg', ['user' => $user]);
    });

    Route::get('/upload', function () {
        /** @var $user \Overtrue\Socialite\User */
        $user = session('wechat.oauth_user.default'); // 拿到授权用户资料
        return view('2018chunjie.upload', ['user' => $user]);
    });

    Route::get('/share', function () {
        /** @var $user \Overtrue\Socialite\User */
        $user = session('wechat.oauth_user.default'); // 拿到授权用户资料

        $file = "2018chunjie/users/{$user->getId()}.jpeg";
        $ossDisk = \Storage::disk('oss_activity');
        return view('2018chunjie.share', ['user' => $user, 'image' => $ossDisk->exists($file) ? $ossDisk->url($file) : '']);
    });

    Route::post('/shoukuanma', function(){
        /** @var $user \Overtrue\Socialite\User */
        $user = session('wechat.oauth_user.default');
        $serverId = request()->get('serverId');

        $file = "2018chunjie/users/{$user->getId()}.jpeg";
        $ossDisk = \Storage::disk('oss_activity');
        $ossDisk->delete($file);

        dispatch(new \App\Jobs\RegenerateShouKuanQrcode($user->getId(), $serverId, $user->getAvatar(), $user->getNickname()))->onConnection('database')->onQueue('h5');

        return response()->json($user->toArray());
    });

    Route::post('/generate', function(){
        /** @var $user \Overtrue\Socialite\User */
        $user = session('wechat.oauth_user.default');

        return response()->json(['image' => \Storage::disk('oss_activity')->url("2018chunjie/users/{$user->getId()}.jpeg")]);
    });

    Route::get('/qrcode', function () {
        /** @var $user \Overtrue\Socialite\User */
        $user = session('wechat.oauth_user.default'); // 拿到授权用户资料

//        $file = "2018chunjie/shoukuanma/{$user->getId()}.jpeg";
//        $ossDisk = \Storage::disk('oss_activity');
//
//        $image = $ossDisk->has($file) ? $ossDisk->url($file) : '';
//
//        return response()->json(['image' => $image]);
        $file = "2018chunjie/users/{$user->getId()}.jpeg";
        $ossDisk = \Storage::disk('oss_activity');
        return response()->json(['image' => $ossDisk->exists($file) ? $ossDisk->url($file) : '']);
    });
});