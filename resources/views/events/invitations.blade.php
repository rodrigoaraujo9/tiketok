@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Your Invitations</h1>

    @include('partials.invitations_table', ['invitations' => $invitations])
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Handle pagination clicks
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
                        // Update the invitations table and pagination
                        document.getElementById('invitations-content').innerHTML = data.html;
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
