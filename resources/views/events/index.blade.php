@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Events</h1>
    
    <!-- Search Bar -->
    <form action="{{ route('events.index') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input 
                type="text" 
                name="search" 
                class="form-control" 
                placeholder="Search events by name" 
                value="{{ request('search') }}"
            >
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <!-- Tag Filter -->
    <div class="mb-3">
        <h5>Filter by Tag:</h5>
        <div>
            @foreach($tags as $tagItem)
                <a 
                    href="{{ route('events.index', ['tag' => $tagItem->name, 'search' => request('search')]) }}" 
                    class="badge {{ request('tag') === $tagItem->name ? 'bg-primary' : 'bg-secondary' }} text-decoration-none p-2 w-100 text-center mb-2"
                >
                    {{ $tagItem->name }}
                </a>
            @endforeach
        </div>
    </div>

    <br>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Back to Dashboard</a>

    @if($events->isEmpty())
        <p>No events found.</p>
    @else
        @include('partials.events_table', ['events' => $events])
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Handle pagination and filter clicks
        document.addEventListener('click', function (e) {
            const target = e.target;

            // Check if the clicked element is a pagination link or filter link
            if (target.tagName === 'A' && (target.closest('.pagination') || target.closest('.badge'))) {
                e.preventDefault();
                const url = target.getAttribute('href');

                // Fetch the new data via AJAX
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.html) {
                        // Update the events table and pagination
                        document.getElementById('events-content').innerHTML = data.html;
                    } else {
                        console.error('No HTML content found in the response:', data);
                    }
                })
                .catch(error => {
                    console.error('Error fetching paginated data:', error);
                });
            }
        });
    });
</script>
@endsection
