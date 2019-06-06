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
        try {
            $user = User::create($input);
        } catch (\Exception $error) {
            //新增失敗回傳錯誤訊息
            $error_message = [
                'msg' => [
                    '註冊失敗'
                ]
            ];

            return redirect('/user/auth/sign-up')
                ->withErrors($error_message)
                ->withInput();
        }

        //重新導向到登入頁
        return redirect('/user/auth/sign-in');
    }

    // 登入頁面
    public function signInPage() {
        $binding = [
            'title' => '登入'
        ];
        return view('auth.signIn', $binding);
    }

    public function signInProcess() {
        $input = request()->all();

        $rules = [
            'email' => 'required|max:150|email',
            'password' => 'required|min:6',
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return redirect('/user/auth/sign-in')
                ->withErrors($validator)
                ->withInput();
        }

        //查詢使用者
        $user = User::where('email', $input['email'])->first();

        if (is_null($user)) {
            //找不到該使用者
            $error_message = [
                'msg' => [
                    'Email 尚未註冊'
                ]
            ];

            return redirect('/user/auth/sign-in')
                ->withErrors($error_message)
                ->withInput();
        }

        //驗證密碼(第一個參數為明文)
        $is_password_correct = Hash::check($input['password'], $user->password);

        if (! $is_password_correct) {
            //密碼錯誤回傳錯誤訊息
            $error_message = [
                'msg' => [
                    '密碼錯誤'
                ]
            ];

            return redirect('/user/auth/sign-in')
                ->withErrors($error_message)
                ->withInput();
        }

        //session 記錄會員編號
        session()->put('user_id', $user->id);

        //重新導向到原先使用者造訪的頁面，沒有嘗試造訪頁則會重新導向回首頁
        return redirect()->intended('/');
    }

    public function signOut() {
        //清除 session
        session()->forget();

        return redirect('/');
    }
}
