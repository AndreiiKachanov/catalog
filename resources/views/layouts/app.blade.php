@php
    $pageWithVue = in_array(Route::currentRouteName(), ['home', 'any']);
    $notShowBreadcrumbs = in_array(Route::currentRouteName(), ['admin.home', 'login', 'password.request', 'password.reset', ]);
    //dump(Route::currentRouteName(), $pageWithVue);
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>
            {{ config('app.name', 'Laravel') }}
{{--            @yield('title')--}}
        </title>
    {{--    @yield('meta')--}}
    <!-- Styles -->
        <link href="{{ mix('css/app.css', (config('app.env') == 'local') ? 'build' : '' ) }}" rel="stylesheet">
        @yield('custom_css')
    </head>
    <body>
        <div id="app">
            <header
                @class(['hide-class' => $pageWithVue])
                class="header">
                <nav class="navbar navbar-expand-md navbar-dark">
                    <div class="container">
                        <a class="navbar-brand" href="{{ url('/') }}">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                        @auth
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                        @endauth

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <!-- Left Side Of Navbar -->
                            <ul class="navbar-nav mr-auto">

                            </ul>

                            <!-- Right Side Of Navbar -->
                            <ul class="navbar-nav ml-auto">
                                <!-- Authentication Links -->
{{--                                @guest--}}
{{--                                    <li><a class="nav-link" href="{{ route('login') }}">Login</a></li>--}}
{{--                                    <li><a class="nav-link" href="{{ route('register') }}">Register</a></li>--}}
{{--                                @else--}}
                                    @auth
                                        <li class="nav-item dropdown">
                                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                {{ Auth::user()->name }} <span class="caret"></span>
                                            </a>

                                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
    {{--                                            @can ('admin-panel')--}}
                                                    <a class="dropdown-item" href="{{ route('admin.home') }}">Admin</a>
    {{--                                            @endcan--}}
                                                {{--                                    <a class="dropdown-item" href="{{ route('cabinet.home') }}">Cabinet</a>--}}
                                                <a class="dropdown-item" href="{{ route('logout') }}"
                                                   onclick="event.preventDefault();
                                               document.getElementById('logout-form').submit();">
                                                    Logout
                                                </a>

                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                            </div>
                                        </li>
                                    @endauth
{{--                                @endguest--}}
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>
            <main
                @class(['app-content', 'py-3' => !$pageWithVue])

{{--                тоже самое, но в сокращенном виде--}}
{{--                 {!! !$pageWithVue ?: 'id="app-vue"' !!} --}}
                {!! $pageWithVue ? 'id="app-vue"' : '' !!}
            >
                    @if($pageWithVue)
{{--                        <app-component date="{{ date('d.m.Y') }}"></app-component>--}}
                        <app-component/>
                    @endif

                    @if(!$pageWithVue)
                        <div class="container">
                            @if(!$notShowBreadcrumbs)
                                @section('breadcrumbs', Breadcrumbs::render())
                                @yield('breadcrumbs')
                            @endif
{{--                            {{  dump($notShowBreadcrumbs) }}--}}
                            @include('layouts.partials.flash')
                            @yield('content')
                        </div>
                    @endif
            </main>
            <footer
                @class(['hide-class' => $pageWithVue])
            >
                <div class="container">
                    <div class="border-top pt-3">
                        <p style="text-align: center;">&copy; {{ date('Y') }} - {{ config('app.author') }}</p>
                    </div>
                </div>
            </footer>
        </div>
        <!-- Scripts -->
{{--        Для $pageWithVue подключаем app.js, для админки app_admin.js--}}
        <script src="{{ mix('js/' . ($pageWithVue ? 'app.js' : 'app_admin.js'), (config('app.env') == 'local') ? 'build' : '') }}"></script>

        @yield('scripts')

        @env(['production'])
            @if($pageWithVue)
                <script type="text/javascript" >
                    (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
                        m[i].l=1*new Date();
                        for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
                        k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
                    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

                    ym(92132246, "init", {
                        clickmap:true,
                        trackLinks:true,
                        accurateTrackBounce:true,
                        webvisor:true
                    });
                </script>
                <noscript><div><img src="https://mc.yandex.ru/watch/92132246" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
            @endif
        @endenv
    </body>
</html>
