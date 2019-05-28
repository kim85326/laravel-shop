<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class UserAuthController extends Controller
{
    // 註冊頁面
    public function signUpPage() {
        $binding = [
            'title' => '註冊'
        ];
        return view('auth.signUp', $binding);
    }

    // 處理註冊資料
    public function signUpProcess() {
        // 接受輸入資料
        $input = request()->all();

        //驗證規則
        $rules = [
            'nickname' => 'required|max:50',
            'email' => 'required|max:150|email',
            'password' => 'required|min:6|same:password_confirmation',
            'password_confirmation' => 'required|min:6',
            'type' => 'required|in:G,A'
        ];

        //驗證資料
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            // 資料驗證錯誤
            return redirect('/user/auth/sign-up')
                ->withErrors($validator)
                ->withInput();
        }

        //密碼加密
        $input['password'] = Hash::make($input['password']);

        //新增會員
        $Users = User::create($input);

        //重新導向到登入頁
        return redirect('/user/auth/sign-in');
    }
}
