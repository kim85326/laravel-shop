@extends('layout.master')

@section('title', $title)

@section('content')
    <div class="container">
        <h1>{{ $title }}</h1>

        {{--錯誤訊息--}}
        @include('components.validationErrorMessage')

        <table>
            <tr>
                <th>編號</th>
                <th>名稱</th>
                <th>圖片</th>
                <th>狀態</th>
                <th>價格</th>
                <th>剩餘數量</th>
                <th>編輯</th>
            </tr>
            @foreach($merchandisePaginate as $merchandise)
                <tr>
                    <td>{{ $merchandise->id }}</td>
                    <td>{{ $merchandise->name }}</td>
                    <td>
                        @if(is_null($merchandise->photo))
                            <img src="{{ url('/assert/images/default-merchandise.jpeg') }}">
                        @else
                            <img src="{{ $merchandise->photo }}">
                        @endif
                    </td>
                    <td>
                        @if($merchandise->status == 'C')
                            建立中
                        @else
                            可販售
                        @endif
                    </td>
                    <td>{{ $merchandise->price }}</td>
                    <td>{{ $merchandise->remain_count }}</td>
                    <td>
                        <a href="/merchandise/{{ $merchandise->id }}/edit">
                            編輯
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>

        {{-- 分頁頁數按鈕 --}}
        {{ $merchandisePaginate->links() }}
    </div>
@endsection
