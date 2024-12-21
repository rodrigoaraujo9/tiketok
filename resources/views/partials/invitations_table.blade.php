<div id="invitations-content">
    @if ($invitations->isEmpty())
        <p>You have no invitations.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Organizer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invitations as $invite)
                    <tr>
                        <td>{{ $invite->event->name }}</td>
                        <td>{{ $invite->event->date }}</td>
                        <td>{{ $invite->event->organizer->name }}</td>
                        <td>
                            <!-- Accept Invitation -->
                            <form action="{{ route('events.accept', $invite->event_id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="btn btn-success">Accept</button>
                            </form>

                            <!-- Reject Invitation -->
                            <form action="{{ route('events.reject', $invite->event_id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="delete btn btn-danger">Decline</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination-container d-flex justify-content-center mt-4">
            {{ $invitations->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>
