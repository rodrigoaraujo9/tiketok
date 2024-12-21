<nav class="navbar navbar-expand-lg navbar-light bg-light shadow mb-4">
    <div class="container">
        <!-- Navbar Brand -->

        <!-- Toggle Button for Mobile -->

        <!-- Collapsible Content -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Events Section -->
                <li class="nav-item dropdown">
                    <a>
                        Events
                    </a>
                    <li><a class="dropdown-item" href="{{ route('events.index') }}">Browse Events</a></li>
                    @if (Auth::check() && !Auth::user()->isAdmin())
                        <li><a class="dropdown-item" href="{{ route('events.create') }}">Create Event</a></li>
                        <li><a class="dropdown-item" href="{{ route('events.manage') }}">Manage Events</a></li>
                        <li><a class="dropdown-item" href="{{ route('events.attending') }}">Attending</a></li>
                        <li><a class="dropdown-item" href="{{ route('events.invitations') }}">Invitations</a></li>
                    @endif
                </li>

                <!-- User Section -->
                @if (Auth::check() && !Auth::user()->isAdmin())
                <li class="nav-item dropdown">
                    <a>
                        User
                    </a>
                    <li><a class="dropdown-item {{ request()->routeIs('profile.show') ? 'active' : '' }}" href="{{ route('profile.show') }}">
                        Profile
                    </a></li>
                    <li><a class="dropdown-item {{ request()->routeIs('userReports') ? 'active' : '' }}" href="{{ route('userReports') }}">
                        My Reports
                    </a></li>
                </li>
                @endif

                <!-- Admin Tools -->
                @if (Auth::check() && Auth::user()->isAdmin())
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is('admin*') ? 'active' : '' }}" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Admin Tools
                    </a>
 
                        <li><a  href="{{ route('allUsers') }}">Manage Users</a></li>
                        <li><a href="{{ route('allReports') }}">Admin Reports</a></li>

                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
