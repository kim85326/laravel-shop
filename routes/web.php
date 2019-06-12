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
        //註冊
        Route::get('/sign-up', 'UserAuthController@signUpPage');
        Route::post('/sign-up', 'UserAuthController@signUpProcess');
        //登入
        Route::get('/sign-in', 'UserAuthController@signInPage');
        Route::post('/sign-in', 'UserAuthController@signInProcess');
        //登出
        Route::get('/sign-out', 'UserAuthController@signOut');
    });
});

Route::get('/', function () {
    return view('home');
});

//商品
Route::group(['prefix' => 'merchandise'], function () {
    //商品清單檢視
    Route::get('/', 'MerchandiseController@merchandiseListPage');
    //商品資料新增
    Route::get('/create', 'MerchandiseController@merchandiseCreateProcess')->middleware(['user.auth.admin']);
    //商品管理清單檢視
    Route::get('/manage', 'MerchandiseController@merchandiseManageListPage')->middleware(['user.auth.admin']);

    //指定商品
    Route::group(['prefix' => '{merchandise_id}'], function () {
        //商品單品檢視
        Route::get('/', 'MerchandiseController@merchandiseItemPage');
        //商品單品編輯頁面檢視
        Route::get('/edit', 'MerchandiseController@merchandiseItemEditPage')->middleware(['user.auth.admin']);
        //商品單品資料修改
        Route::put('/', 'MerchandiseController@merchandiseItemUpdateProcess')->middleware(['user.auth.admin']);
        //購買商品
        Route::post('/buy', 'MerchandiseController@merchandiseItemBuyProcess')->middleware(['user.auth']);
    });
});