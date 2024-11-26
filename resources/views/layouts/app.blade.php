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
        <script type="text/javascript" src={{ url('js/app.js') }} defer>
        </script>
    </head>
    <body>
        <main>
            <header>
                <h1><a href="{{ url('/events') }}" style="text-decoration: none;">TIKETOK</a></h1>
                <nav style="display: flex; gap: 3rem; align-items: center; justify-content: center; margin-top: 1rem;">
                    <a href="{{ route('events.index') }}" style="text-decoration: none;">Browse Events</a>
                    @if (Auth::check() && !Auth::user()->isAdmin())
                    <a href="{{ route('events.create') }}" style="text-decoration: none;">Create Event</a>
                    <a href="{{ route('events.manage') }}" style="text-decoration: none;">Manage</a>
                    <a href="{{ route('events.attending') }}" style="text-decoration: none;">Attending</a>
                    <a href="{{ route('dashboard') }}" style="text-decoration: none;">Dashboard</a>
                    <a href="{{ route('events.invitations') }}" style="text-decoration: none;">Invitations</a>
                    @endif
                    @if (Auth::check() && Auth::user()->isAdmin())
                    <a href="{{ route('allReports') }}" style="text-decoration: none;">Admin Reports</a>
                    @endif
                    @if (Auth::check())
                    <span style="color:#949494;  font-size: 1.5rem;font-weight: light;">Welcome, {{ Auth::user()->name }}</span>
                        <form action="{{ route('logout') }}" method="GET" style="display: inline; margin:0;">
                            @csrf
                            <button class="logout-button">Logout</button>
                        </form>
                        
                    @else
                        <a href="{{ route('login') }}" style="text-decoration: none;">Login</a>
                        <a href="{{ route('register') }}" style="text-decoration: none;">Register</a>
                    @endif
                </nav>
            </header>
            <section id="content">
                @yield('content')
            </section>
        </main>
    </body>
</html>

