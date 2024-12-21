@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center my-4">Manage Your Events</h1>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">← Back to Dashboard</a>

    @if ($events->isEmpty())
        <div class="alert alert-info text-center">
            <p>You have not created any events yet. <a href="{{ route('events.create') }}" class="btn btn-link">Create an Event</a></p>
        </div>
    @else
        @include('partials.manage_table', ['events' => $events])
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Handle pagination click
        document.addEventListener('click', function (e) {
            const target = e.target;

            // Check if the clicked element is a pagination link
            if (target.tagName === 'A' && target.closest('.pagination')) {
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
                    // Replace the events table and pagination
                    document.getElementById('events-content').innerHTML = data.html;
                })
                .catch(error => {
                    console.error('Error fetching paginated data:', error);
                });
            }
        });
    });
</script>
@endsection
