@extends('layout.master')

@section('title', $title)

@section('content')
    <div class="container">
        <h1>{{ $title }}</h1>

        {{--錯誤訊息--}}
        @include('components.validationErrorMessage')

        <form action="/user/auth/sign-up" method="post">
            {!! csrf_field() !!}

            <label>
                暱稱：
                <input type="text" name="nickname" value="{{old('nickname')}}">
            </label>

            <label>
                Email：
                <input type="text" name="email" value="{{old('email')}}">
            </label>

            <label>
                密碼：
                <input type="password" name="password" value="{{old('password')}}">
            </label>

            <label>
                確認密碼：
                <input type="password" name="password_confirmation" value="{{old('password_confirmation')}}">
            </label>

            <label>
                帳號型態：
                <select name="type">
                    <option value="G" {{ (old("type") == "G" ? "selected" : "") }}>一般會員</option>
                    <option value="A" {{ (old("type") == "A" ? "selected" : "") }}>管理員</option>
                </select>
            </label>

            <button type="submit">註冊</button>
        </form>
    </div>
@endsection