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
                <td>{{ $merchandise->name }}</td>
            </tr>
            <tr>
                <th>照片</th>
                <td>
                    @if(is_null($merchandise->photo))
                        <img src="{{ url('/assert/images/default-merchandise.jpeg') }}">
                    @else
                        <img src="{{ $merchandise->photo }}">
                    @endif
                </td>
            </tr>
            <tr>
                <th>價格</th>
                <td>{{ $merchandise->price }}</td>
            </tr>
            <tr>
                <th>剩餘數量</th>
                <td>{{ $merchandise->remain_count }}</td>
            </tr>
            <tr>
                <th>介紹</th>
                <td>{{ $merchandise->introduction }}</td>
            </tr>
            <tr>
                <td colspan="2">
                    <form action="/merchandise/{{ $merchandise->id }}/buy" method="post">
                        {{ csrf_field() }}
                        購買數量
                        <select name="buy_count">
                            @for($count = 1; $count <= $merchandise->remain_count; $count++)
                                <option value="{{ $count }}">{{ $count }}</option>
                            @endfor
                        </select>
                        <button type="submit">
                            購買
                        </button>
                    </form>
                </td>
            </tr>
        </table>
    </div>
@endsection
