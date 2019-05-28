<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - Shop </title>
</head>
<body>
    <ul class="nav">
        @if(session()->has('user_id'))
            <li><a href="/user/auth/sign-out">登出</a></li>
        @else
            <li><a href="/user/auth/sign-in">登入</a></li>
            <li><a href="/user/auth/sign-up">註冊</a></li>
        @endif
    </ul>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>