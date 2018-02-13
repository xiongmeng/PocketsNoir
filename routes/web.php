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

Route::group(['middleware' => ['wechat.oauth:snsapi_userinfo']], function () {
    Route::get('/entry', function () {
        /** @var $user \Overtrue\Socialite\User */
        $user = session('wechat.oauth_user.default'); // 拿到授权用户资料
//        $cfg = EasyWeChat::officialAccount()->jssdk->buildConfig(array('chooseImage', 'previewImage', 'uploadImage'), true);
//        return response()->json($user->toArray());

        return view('2018chunjie.entry', ['user' => $user]);
    });

    Route::post('/shoukuanma', function(){
        /** @var $user \Overtrue\Socialite\User */
        $user = session('wechat.oauth_user.default');
        $serverId = request()->get('serverId');

        $file = "2018chunjie/shoukuanma/{$user->getId()}.jpeg";
        $ossDisk = \Storage::disk('oss_activity');
        $ossDisk->delete($file);

        dispatch(new \App\Jobs\RegenerateShouKuanQrcode($user->getId(), $serverId))->onConnection('database')->onQueue('h5');

        return response()->json($user->toArray());
    });

    Route::post('/generate', function(){
        /** @var $user \Overtrue\Socialite\User */
        $user = session('wechat.oauth_user.default');

//        $publicDisk = \Storage::disk('public');
//        $head = "{$user->getId()}.jpeg";
//        if(!$publicDisk->exists($head)){
//            $headContent = \App\Libiary\Utility\CurlWrapper::curlGet($user->getAvatar());
//            $publicDisk->put($head, $headContent);
//        }
//
//        $shoukumaQrCode = Storage::disk('oss_activity')->get("2018chunjie/shoukuanma/{$user->getId()}.jpeg");
//        $shoukuanma = "shoukuma/{$user->getId()}.jpeg";
//        $publicDisk->put($shoukuanma, $shoukumaQrCode);
//
//        $imagine = new \Imagine\Gd\Imagine();
//        $bgi = $imagine->open(__DIR__ . "/chunjie2018Bg/WechatIMG3.jpeg");
//
//        $headi = $imagine->open($publicDisk->path($head));
//        $bgi->paste($headi, new \Imagine\Image\Point(322, 704));
//
//        $palette = new Imagine\Image\Palette\RGB();
//        $font = new \Imagine\Gd\Font(__DIR__ . '/chunjie2018Bg/SY.ttf', '30', $palette->color('#fff'));
//        $bgi->draw()->text($user->getNickname(), $font, new \Imagine\Image\Point(500,1180), 0, 480);
//
//        $shoukumai = $imagine->open($publicDisk->path($shoukuanma));
//        $bgi->paste($shoukumai, new \Imagine\Image\Point(371, 1467));
//
//        $users = "users/{$user->getId()}.jpeg";
//        $publicDisk->put($users, '');
//        $bgi->save(Storage::disk('public')->path($users));
//
//        \Storage::disk('oss_activity')->put("2018chunjie/users/{$user->getId()}.jpeg", file_get_contents(Storage::disk('public')->path($users)));
//
//        return response()->json(['image' => \Storage::disk('oss_activity')->url("2018chunjie/users/{$user->getId()}.jpeg")]);
        return response()->json(['image' => \Storage::disk('oss_activity')->url("2018chunjie/users/{$user->getId()}.jpeg")]);
    });

    Route::get('/qrcode', function () {
        /** @var $user \Overtrue\Socialite\User */
        $user = session('wechat.oauth_user.default'); // 拿到授权用户资料

        $file = "2018chunjie/shoukuanma/{$user->getId()}.jpeg";
        $ossDisk = \Storage::disk('oss_activity');

        $image = $ossDisk->has($file) ? $ossDisk->url($file) : '';

        return response()->json(['image' => $image]);
    });
});