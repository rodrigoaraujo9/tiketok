<nav class="navbar navbar-expand-lg navbar-light bg-light shadow mb-4" style="padding-bottom: 4rem;">
    <div class="container">


        <!-- Collapsible Content -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Core Navigation -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('events.index') ? 'active' : '' }}" href="{{ route('events.index') }}">
                        Browse Events
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('events.create') ? 'active' : '' }}" href="{{ route('events.create') }}">
                        Create Event
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('events.manage') ? 'active' : '' }}" href="{{ route('events.manage') }}">
                        Manage My Events
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('events.attending') ? 'active' : '' }}" href="{{ route('events.attending') }}">
                        My Events
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('events.invitations') ? 'active' : '' }}" href="{{ route('events.invitations') }}">
                        Invitations
                    </a>
                </li>
            </ul>
            <!-- User Options -->
            <div class="nav-item">

</div>
        </div>
    </div>
</nav>

