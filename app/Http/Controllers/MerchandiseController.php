<?php

namespace App\Http\Controllers;

use App\Merchandise;
use Illuminate\Http\Request;

class MerchandiseController extends Controller
{
    //新增商品
    public function merchandiseCreateProcess() {
        //建立商品基本資訊
        $merchandise_data = [
            'status' => 'C',    //建立中
            'name' => '',   //商品名稱
            'name_en' => '',    //商品英文名稱
            'introduction' => '',   //商品介紹
            'introduction_en' => '',    //商品英文介紹
            'photo' => null,    //商品照片
            'price' => 0,   //價格
            'remain_count' => 0,    //商品剩餘數量
        ];

        try {
            $merchandise = Merchandise::create($merchandise_data);
        } catch (\Exception $error) {
            //新增失敗回傳錯誤訊息
            $error_message = [
                'msg' => [
                    '建立商品失敗'
                ]
            ];

            return redirect('/merchandise/')
                ->withErrors($error_message)
                ->withInput();
        }

        //重新導向至商品編輯頁
        return redirect('/merchandise/' . $merchandise->id . '/edit');
    }

    public function merchandiseItemEditPage($merchandise_id) {
        //撈取商品資料
        $merchandise = Merchandise::findOrFail($merchandise_id);

        if ( ! is_null($merchandise->photo)) {
            //設定商品照片網址
            $merchandise->photo = url($merchandise->photo);
        }

        $binding = [
            'title' => '編輯商品',
            'merchandise' => $merchandise
        ];
        return view('merchandise.editMerchandise', $binding);
    }
}
