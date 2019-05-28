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

// 使用者
Route::group(['prefix' => 'user'], function () {
    // 使用者驗證
    Route::group(['prefix' => 'auth'], function () {
        Route::get('/sign-up', 'UserAuthController@signUpPage');
        Route::post('/sign-up', 'UserAuthController@signUpProcess');
    });
});


Route::get('/', function () {
    return view('welcome');
});
