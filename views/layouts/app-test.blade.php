<html xmlns="http://www.w3.org/1999/xhtml" lang="{{ app()->getLocale() }}">
<head>
<title>Activity Module - @yield('title')</title>
</head>
<body>
      @if (Auth::check())
            <p class="User"> Chào <b>{{ Auth::user()->getAuthIdentifier() }}</b>, 
            <a href="{{route('logout')}}"><b>Thoát</b></a></p>
      @else
      		<a href="{{route('login')}}"><b>Đăng Nhập</b></a>
      @endif
      @yield('content')
</body>
</html>