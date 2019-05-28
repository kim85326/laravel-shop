@extends('layout.master')

@section('title', $title)

@section('content')
    <div class="container">
        <h1>{{ $title }}</h1>

        {{--錯誤訊息--}}
        @include('components.validationErrorMessage')

        <form action="/user/auth/sign-in" method="post">
            {!! csrf_field() !!}

            <label>
                Email：
                <input type="text" name="email" value="{{old('email')}}">
            </label>

            <label>
                密碼：
                <input type="password" name="password" value="{{old('password')}}">
            </label>

            <button type="submit">登入</button>
        </form>
    </div>
@endsection
