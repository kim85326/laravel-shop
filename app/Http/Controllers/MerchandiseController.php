<?php

namespace App\Http\Controllers;

use App\Merchandise;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Validator;
use Image;
use DB;

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

    public function merchandiseItemUpdateProcess($merchandise_id) {
        //撈取商品資料
        $merchandise = Merchandise::findOrFail($merchandise_id);

        //接受輸入資料
        $input = request()->all();

        //驗證規則
        $rules = [
            'status' => 'required|in:C,S',
            'name' => 'required|max:80',
            'name_en' => 'required|max:80',
            'introduction' => 'required|max:2000',
            'introduction_en' => 'required|max:2000',
            'photo' => 'file|image|max:10240',
            'price' => 'required|integer|min:0',
            'remain_count' => 'required|integer|min:0',
        ];

        //驗證資料
        $validation = Validator::make($input, $rules);

        if ($validation->fails()) {
            //資料驗證錯誤
            return redirect('/merchandise/' . $merchandise->id . '/edit')
                ->withErrors($validation)
                ->withInput();
        }

        if (isset($input['photo'])) {
            //有上傳圖片
            $photo = $input['photo'];
            //檔案副檔名
            $file_extension = $photo->getClientOriginalExtension();
            //產生自訂隨機檔案名稱
            $file_name = uniqid() . '.' . $file_extension;
            //檔案相對路徑
            $file_relative_path = 'images/merchandise/' . $file_name;
            //檔案存放目錄為對外公開 public 目錄下的相對路徑
            $file_path = public_path($file_relative_path);
            //裁切圖片
            $image = Image::make($photo)->fit(450, 300)->save($file_path);
            //設定圖片檔案相對路徑
            $input['photo'] = $file_relative_path;
        }

        //更新商品資訊
        try {
            $merchandise->update($input);
        } catch (\Exception $error) {
            //更新失敗回傳錯誤訊息
            $error_message = [
                'msg' => [
                    '更新失敗'
                ]
            ];

            return redirect('/merchandise/' . $merchandise->id . '/edit')
                ->withErrors($error_message)
                ->withInput();
        }

        //重新導向至商品編輯頁
        return redirect('/merchandise/' . $merchandise->id . '/edit');
    }

    public function merchandiseManageListPage() {
        //每頁資料量
        $row_per_page = 10;

        //撈取商品分頁資料
        $merchandisePaginate = Merchandise::OrderBy('created_at', 'desc')
            ->paginate($row_per_page);

        //設定商品圖片網址
        foreach ($merchandisePaginate as &$merchandise) {
            if ( ! is_null($merchandise->photo)) {
                $merchandise->photo = url($merchandise->photo);
            }
        }

        $binding = [
            'title' => '管理商品',
            'merchandisePaginate' => $merchandisePaginate
        ];

        return view('merchandise.manageMerchandise', $binding);
    }

    public function merchandiseListPage() {
        //每頁資料量
        $row_per_page = 10;

        //撈取商品分頁資料
        $merchandisePaginate = Merchandise::OrderBy('updated_at', 'desc')
            ->where('status', 'S') //可販售的
            ->paginate($row_per_page);

        //設定商品圖片網址
        foreach ($merchandisePaginate as &$merchandise) {
            if ( ! is_null($merchandise->photo)) {
                $merchandise->photo = url($merchandise->photo);
            }
        }

        $binding = [
            'title' => '商品列表',
            'merchandisePaginate' => $merchandisePaginate
        ];

        return view('merchandise.listMerchandise', $binding);
    }

    public function merchandiseItemPage($merchandise_id) {
        //撈取商品資料
        $merchandise = Merchandise::findOrFail($merchandise_id);

        //設定商品圖片網址
        if ( ! is_null($merchandise->photo)) {
            $merchandise->photo = url($merchandise->photo);
        }

        $binding = [
            'title' => '商品頁',
            'merchandise' => $merchandise
        ];

        return view('merchandise.showMerchandise', $binding);
    }

    public function merchandiseItemBuyProcess($merchandise_id) {
        //接受輸入資料
        $input = request()->all();

        //驗證規則
        $rules = [
            'buy_count' => 'required|integer|min:1',
        ];

        //驗證資料
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            //驗證資料錯誤
            return redirect('/merchandise/'.$merchandise_id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            //取得登入會員資料
            $user_id = session()->get('user_id');
            $user = User::findOrFail($user_id);

            //交易開始
            DB::beginTransaction();

            //取得商品資料
            $merchandise = Merchandise::findOrFail($merchandise_id);

            //購買數量
            $buy_count = $input['buy_count'];
            //購買後剩餘數量
            $remain_count_after_buy = $merchandise->remain_count - $buy_count;

            if ($remain_count_after_buy < 0) {
                //購買後剩餘數量小於 0，不足以賣給使用者
                throw new Exception('商品數量不足，無法購買');
            }

            //紀錄購買後剩餘數量
            $merchandise->remain_count = $remain_count_after_buy;
            $merchandise->save();

            //總金額 = 總購買數量 * 商品價格
            $total_price = $buy_count * $merchandise->price;

            $transaction_data = [
                'user_id' => $user->id,
                'merchandise_id' => $merchandise->id,
                'price' => $merchandise->price,
                'buy_count' => $buy_count,
                'total_price' => $total_price,
            ];

            //建立交易資料
            Transaction::create($transaction_data);

            //交易結束
            DB::commit();

            //回傳購物成功訊息
            $message = [
                'msg' => ['購買成功']
            ];

            return redirect()
                ->to('/merchandise/'.$merchandise_id)
                ->withErrors($message);

        } catch (Exception $exception) {
            //恢復原先交易狀態
            DB::rollBack();

            //回傳錯誤訊息
            $error_message = [
                'msg' => [$exception->getMessage()]
            ];

            return redirect()
                ->back('/merchandise/'.$merchandise_id)
                ->withErrors($error_message)
                ->withInput();
        }
    }
}
