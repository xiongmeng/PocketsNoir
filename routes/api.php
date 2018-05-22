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

Route::any('lottery/chooseShop', "lotteryController@chooseShop");

Route::any('lottery/lotteryDraw/{id}', "lotteryController@lotteryDraw");

Route::post('lottery/addShopRule', "lotteryController@addShopRule");
