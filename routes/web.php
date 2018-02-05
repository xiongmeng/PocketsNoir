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

Route::post('/youzan/push', function () {
    $rawPostData = file_get_contents("php://input");
    dispatch(new \App\Jobs\DisposeYouZanPush($rawPostData))->onConnection('sync');
    return response('{"code":0,"msg":"success"}', 200, ['content_type' => 'text/plain']);
});

Route::post('/guanjiapo/push', function(){
    $postRawData = [
        'id' => 'xx',
        'status' => 'a',
        'type' => '' //零售单回调
    ];

    return response('{"code":0,"msg":"success"}', 200, ['content_type' => 'text/plain']);
});

Route::get('/ab', function (){
    return response('{"code":0,"msg":"success"}', 200, ['content_type' => 'text/plain']);
});

Route::group(['middleware' => ['wechat.oauth:snsapi_userinfo']], function () {
    Route::get('/user', function () {
        /** @var $user \Overtrue\Socialite\User */
        $user = session('wechat.oauth_user.default'); // 拿到授权用户资料

//        dd($user);
        return response()->json($user->toArray());
    });
});