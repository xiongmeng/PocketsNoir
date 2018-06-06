<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('lottery/chooseShop', "LotteryController@chooseShop");

Route::get('lottery/lotteryDraw', "LotteryController@lotteryDraw");

Route::post('lottery/addShopRule', "LotteryController@addShopRule");

Route::post('lottery/lotterySave', "LotteryController@LotterySave");

Route::post('lottery/presentTest', "LotteryController@presentTest");

Route::post('lottery/getMobileCode', "LotteryController@getMobileCode");

Route::post('lottery/importFaceBase64', "LotteryController@importFaceBase64");
