@extends('layout.master')

@section('title', $title)

@section('content')
    <div class="container">
        <h1>{{ $title }}</h1>

        {{--錯誤訊息--}}
        @include('components.validationErrorMessage')

        <table>
            <tr>
                <th>商品名稱</th>
                <th>照片</th>
                <th>單價</th>
                <th>數量</th>
                <th>總金額</th>
                <th>購買時間</th>
            </tr>
            @foreach($transactionPaginate as $transaction)
                <tr>
                    <td>
                        <a href="/merchandise/{{ $transaction->merchandise->id }}">
                            {{ $transaction->merchandise->name }}
                        </a>
                    </td>
                    <td>
                        <a href="/merchandise/{{ $transaction->merchandise->id }}">
                            @if(is_null($transaction->merchandise->photo))
                                <img src="{{ url('/assert/images/default-merchandise.jpeg') }}">
                            @else
                                <img src="{{ $transaction->merchandise->photo }}">
                            @endif
                        </a>
                    </td>
                    <td>{{ $transaction->merchandise->price }}</td>
                    <td>{{ $transaction->buy_count }}</td>
                    <td>{{ $transaction->total_price }}</td>
                    <td>{{ $transaction->created_at }}</td>
                </tr>
            @endforeach
        </table>

        {{-- 分頁頁數按鈕 --}}
        {{ $transactionPaginate->links() }}
    </div>
@endsection
