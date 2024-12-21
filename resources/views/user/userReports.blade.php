@extends('layouts.app')

@section('content')
<div class="container">
    <h1>My Reports</h1>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Back to Dashboard</a>

    @include('partials.reports_table', ['reports' => $reports])
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Handle pagination click events
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
                    if (data.html) {
                        // Update the reports table and pagination
                        document.getElementById('reports-content').innerHTML = data.html;
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
