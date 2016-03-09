<!DOCTYPE html>
<html lang="en">
<head>
    @section('head')
        @include('layouts.header')
    @show
</head>
<body>
<div class="container">
    @yield('content')
</div>

@section('footer')
    @include('layouts.footer')
@show
</body>
</html>