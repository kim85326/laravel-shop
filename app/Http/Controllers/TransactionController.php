<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function transactionListPage() {
        $user_id = session()->get('user_id');

        //每頁資料量
        $row_per_page = 10;

        //撈取交易分頁資料
        $transactionPaginate = Transaction::where('user_id', $user_id)
            ->orderBy('created_at')
            ->with('Merchandise')
            ->paginate($row_per_page);

        //設定商品圖片網址
        foreach ($transactionPaginate as $transaction) {
            if ( ! is_null($transaction->merchandise->photo)) {
                $transaction->merchandise->photo = url($transaction->merchandise->photo);
            }
        }

        $binding = [
            'title' => '交易紀錄',
            'transactionPaginate' => $transactionPaginate,
        ];

        return view('transaction.listUserTransaction', $binding);
    }
}
