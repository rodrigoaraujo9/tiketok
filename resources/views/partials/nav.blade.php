<nav class="navbar navbar-expand-lg navbar-light bg-light shadow mb-4">
    <div class="container">

        <!-- Collapsible Content -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Events Section -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is('events*') ? 'active' : '' }}" href="#" id="eventsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Events
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="eventsDropdown">
                        <li><a class="dropdown-item" href="{{ route('events.index') }}">Browse Events</a></li>
                        @if (Auth::check() && !Auth::user()->isAdmin())
                            <li><a class="dropdown-item" href="{{ route('events.create') }}">Create Event</a></li>
                            <li><a class="dropdown-item" href="{{ route('events.manage') }}">Manage Events</a></li>
                            <li><a class="dropdown-item" href="{{ route('events.attending') }}">Attending</a></li>
                            <li><a class="dropdown-item" href="{{ route('events.invitations') }}">Invitations</a></li>
                        @endif
                    </ul>
                </li>

                <!-- Profile -->
                @if (Auth::check() && !Auth::user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('profile.show') ? 'active' : '' }}" href="{{ route('profile.show') }}">
                        <i class="fas fa-user"></i> Profile
                    </a>
                </li>
                @endif

                <!-- Admin Tools -->
                @if (Auth::check() && Auth::user()->isAdmin())
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is('admin*') ? 'active' : '' }}" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Admin Tools
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                        <li><a class="dropdown-item" href="{{ route('allUsers') }}">Manage Users</a></li>
                        <li><a class="dropdown-item" href="{{ route('allReports') }}">Admin Reports</a></li>
                    </ul>
                </li>
                @endif

                <!-- Reports -->
                @if (Auth::check() && !Auth::user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('userReports') ? 'active' : '' }}" href="{{ route('userReports') }}">
                        <i class="fas fa-file-alt"></i> My Reports
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
