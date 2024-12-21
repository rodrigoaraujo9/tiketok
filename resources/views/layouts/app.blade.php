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
        <style>
            nav {
                display: flex;
                gap: 3rem;
                align-items: center;
                justify-content: center;
                margin-top: 1rem;
            }

            .dropdown {
                position: relative;
                display: inline-block;
            }

            .dropdown-menu {
                display: none;
                position: absolute;
                background-color: #f9f9f9;
                box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
                padding: 1rem;
                z-index: 1;
                border-radius: 0.5rem;
                width: 200px;
                margin-top: 0.5rem;
                transition: opacity 0.3s ease, visibility 0.3s ease;
                visibility: hidden;
                opacity: 0;
            }

            .dropdown.show .dropdown-menu {
                display: block;
                visibility: visible;
                opacity: 1;
            }

            .dropdown-menu a {
                display: block;
                text-decoration: none;
                color: #000;
                padding: 0.5rem 0;
            }

            .dropdown-menu a:hover {
                background-color: #f1f1f1;
            }

            .logout-button {
                border: none;
                background: none;
                color: #000;
                cursor: pointer;
                font-size: 1rem;
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <main>
            <header>
                <h1><a href="{{ url('/events') }}" style="text-decoration: none;">TIKETOK</a></h1>
                <nav>
                    <!-- Browse Events -->
                    <a href="{{ route('events.index') }}" style="text-decoration: none;">Browse Events</a>

                    <!-- About Us -->
                    <a href="{{ route('about') }}" style="text-decoration: none;">About Us</a>

                    <!-- Dashboard with Enhanced Usability -->
                    @if (Auth::check())
                        <div class="dropdown" id="dashboardDropdown">
                            <a href="#" style="text-decoration: none;">Dashboard</a>
                            <div class="dropdown-menu">
                                @if (!Auth::user()->isAdmin())
                                    <a href="{{ route('events.create') }}">Create Event</a>
                                    <a href="{{ route('events.manage') }}">Manage Events</a>
                                    <a href="{{ route('events.attending') }}">Attending</a>
                                    <a href="{{ route('events.invitations') }}">Invitations</a>
                                    <a href="{{ route('profile.show') }}">Profile</a>
                                @else
                                    <a href="{{ route('allReports') }}">Admin Reports</a>
                                    <a  href="{{ route('allUsers') }}">Manage Users</a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Authentication Links -->
                    @if (Auth::check())
                        <span style="color:#949494; font-size: 1.5rem; font-weight: light;">
                            Welcome, {{ Auth::user()->name }}
                        </span>
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

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const dropdown = document.getElementById('dashboardDropdown');
                let timer;

                dropdown.addEventListener('mouseover', () => {
                    clearTimeout(timer);
                    dropdown.classList.add('show');
                });

                dropdown.addEventListener('mouseleave', () => {
                    timer = setTimeout(() => {
                        dropdown.classList.remove('show');
                    }, 300); // Delay in milliseconds before hiding the menu
                });
            });
        </script>
    </body>
</html>
