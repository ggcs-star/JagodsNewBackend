<!DOCTYPE html>
<html>
@include('frontend.partials._head')

<body  @stack('body-data')>
    <audio id="myAudio1">
        <source src="{{asset('beep.mp3')}}" type="audio/mpeg">
    </audio>
    
    <div id="main-wrapper">
        @include('frontend.partials._nav')

        @yield('main-content')
        @includeUnless(request()->is(['login', 'register']), 'frontend.partials._footer')

    </div>
    @include('frontend.partials._scripts')

</body>

</html>
