@extends('layout.master')

@section('title', $title)

@section('content')
    <div class="container">
        <h1>{{ $title }}</h1>

        {{--錯誤訊息--}}
        @include('components.validationErrorMessage')

        <form action="/merchandise/{{ $merchandise->id }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('PUT') }}

            <label>
                商品狀態：
                <select name="status" value="{{old('email')}}">
                    <option
                            value="C"
                            @if(old('status', $merchandise->status) == 'C')
                                selected
                            @endif
                    >
                        建立中
                    </option>
                    <option
                            value="S"
                            @if(old('status', $merchandise->status) == 'S')
                            selected
                            @endif
                    >
                        可販售
                    </option>
                </select>
            </label>

            <label>
                商品名稱：
                <input type="text" name="name" value="{{old('name', $merchandise->name)}}">
            </label>

            <label>
                商品英文名稱：
                <input type="text" name="name_en" value="{{old('name_en', $merchandise->name_en)}}">
            </label>

            <label>
                商品介紹：
                <input type="text" name="introduction" value="{{old('introduction', $merchandise->introduction)}}">
            </label>

            <label>
                商品英文介紹：
                <input type="text" name="introduction_en" value="{{old('introduction_en', $merchandise->introduction_en)}}">
            </label>

            <label>
                商品照片：
                <input type="file" name="photo">
                <img src="{{ $merchandise->photo or '/assert/images/default-merchandise.png' }}">
            </label>

            <label>
                商品價格：
                <input type="text" name="price" value="{{old('price', $merchandise->price)}}">
            </label>


            <label>
                商品剩餘數量：
                <input type="text" name="remain_count" value="{{old('remain_count', $merchandise->remain_count)}}">
            </label>

            <button type="submit">更新商品資訊</button>
        </form>
    </div>
@endsection
