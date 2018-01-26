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
    return response('{"code":0,"msg":"success"}', 200, ['content_type' => 'text/plain']);
});

Route::get('/ab', function (){
    return response('{"code":0,"msg":"success"}', 200, ['content_type' => 'text/plain']);
});