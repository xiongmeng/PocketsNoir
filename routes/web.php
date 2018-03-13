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

        dispatch(new \App\Jobs\SingleRecalculateVip($mobile));

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
        dispatch(new \App\Jobs\SingleRecalculateVip($mobile));
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

Route::post('/zulin/push', function(){
    try{
        dispatch(new \App\Jobs\DisposeZuLinPush(request()->post()))->onConnection('sync');
    }catch (Exception $e){
        \App\Libiary\Context\Fact\FactException::instance()->recordException($e);
    }
    return response('{"code":0,"msg":"success"}', 200, ['content_type' => 'text/plain']);
});
