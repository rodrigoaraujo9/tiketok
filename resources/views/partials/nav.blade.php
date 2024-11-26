<nav class="navbar navbar-expand-lg navbar-light bg-light shadow mb-4">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand fw-bold text-primary" href="{{ route('dashboard') }}">EventApp</a>


        <!-- Collapsible Content -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Core Navigation -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('events.index') ? 'active' : '' }}" href="{{ route('events.index') }}">
                        Browse Events
                    </a>
                </li>
                @if (Auth::check() && !Auth::user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('events.create') ? 'active' : '' }}" href="{{ route('events.create') }}">
                        Create Event
                    </a>
                </li>
                @endif
                @if (Auth::check() && !Auth::user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('events.manage') ? 'active' : '' }}" href="{{ route('events.manage') }}">
                        Manage My Events
                    </a>
                </li>
                @endif
                @if (Auth::check() && !Auth::user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('events.attending') ? 'active' : '' }}" href="{{ route('events.attending') }}">
                        My Events
                    </a>
                </li>
                @endif
                @if (Auth::check() && !Auth::user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('events.invitations') ? 'active' : '' }}" href="{{ route('events.invitations') }}">
                        Invitations
                    </a>
                </li>
                @endif
                @if (Auth::check() && Auth::user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('allReports') ? 'active' : '' }}" href="{{ route('allReports') }}">
                            Admin Reports
                        </a>
                    </li>
                @endif
                @if (Auth::check() && !Auth::user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('userReports') ? 'active' : '' }}" href="{{ route('userReports') }}">
                            My Reports
                        </a>
                    </li>
                @endif
            </ul>
            <!-- User Options -->
            <div class="nav-item">
                    <form action="{{ route('logout') }}" method="GET" class="d-inline">
                        @csrf
                        <button class="btn btn-link nav-link text-danger p-0 fw-bold" type="submit">Logout</button>
                    </form>
</div>
        </div>
    </div>
</nav>
