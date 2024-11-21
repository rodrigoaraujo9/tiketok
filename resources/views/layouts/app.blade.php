<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <!-- Add Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        
        <script type="text/javascript" src="{{ url('js/app.js') }}" defer></script>
    </head>
    <body>
        <main>
            <nav class="top-nav">
                <div class="nav-left">
                    @if (!Request::is('login'))
                    <button class="menu-button">
                        <i class="fas fa-bars"></i>
                        MENU
                    </button>
                    @endif
                </div>
                
                <div class="nav-center">
                    <h1 class="logo"><a href="{{ url('/cards') }}">TIKETOK</a></h1>
                </div>
                
                <div class="nav-right">
                    @if (!Request::is('login'))
                    <button class="search-button">
                        <i class="fas fa-search"></i>
                    </button>
                    @endif
                    @if (Auth::check())
                        <div class="user-controls">
                            <a class="logout-button" href="{{ url('/logout') }}">
                                <i class="fas fa-sign-out-alt"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </nav>

            <section id="content">
                @yield('content')
            </section>

            @if (!Request::is('login'))
            <nav class="bottom-nav">
                <a href="{{ url('/') }}" class="bottom-nav-item {{ Request::is('/') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>HOME</span>
                </a>
                <a href="{{ url('/about') }}" class="bottom-nav-item {{ Request::is('about') ? 'active' : '' }}">
                    <i class="fas fa-info-circle"></i>
                    <span>ABOUT US</span>
                </a>
                <a href="{{ url('/events') }}" class="bottom-nav-item {{ Request::is('events') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>EVENTS</span>
                </a>
                <a href="{{ url('/faq') }}" class="bottom-nav-item {{ Request::is('faq') ? 'active' : '' }}">
                    <i class="fas fa-question-circle"></i>
                    <span>FAQ</span>
                </a>
                <a href="{{ url('/profile') }}" class="bottom-nav-item {{ Request::is('profile') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span>PROFILE</span>
                </a>
            </nav>
            @endif
        </main>
    </body>
</html>