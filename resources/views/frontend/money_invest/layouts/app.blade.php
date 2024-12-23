<!DOCTYPE html>
<html lang="pt-BR">

@include('frontend::include.__head')

<body>
<x:notify-messages/>
@include('frontend::include.__header')

@yield('content')
@include('frontend::include.__footer')
@include('frontend::cookie.gdpr_cookie')

@include('frontend::include.__script')


</body>
</html>

