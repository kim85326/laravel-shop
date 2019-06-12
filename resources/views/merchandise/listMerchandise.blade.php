@extends('layout.master')

@section('title', $title)

@section('content')
    <div class="container">
        <h1>{{ $title }}</h1>

        {{--錯誤訊息--}}
        @include('components.validationErrorMessage')

        <table>
            <tr>
                <th>名稱</th>
                <th>照片</th>
                <th>價格</th>
                <th>剩餘數量</th>
            </tr>
            @foreach($merchandisePaginate as $merchandise)
                <tr>
                    <td>
                        <a href="/merchandise/{{ $merchandise->id }}">
                            {{ $merchandise->name }}
                        </a>
                    </td>
                    <td>
                        <a href="/merchandise/{{ $merchandise->id }}">
                            @if(is_null($merchandise->photo))
                                <img src="{{ url('/assert/images/default-merchandise.jpeg') }}">
                            @else
                                <img src="{{ $merchandise->photo }}">
                            @endif
                        </a>
                    </td>
                    <td>{{ $merchandise->price }}</td>
                    <td>{{ $merchandise->remain_count }}</td>
                </tr>
            @endforeach
        </table>

        {{-- 分頁頁數按鈕 --}}
        {{ $merchandisePaginate->links() }}
    </div>
@endsection
