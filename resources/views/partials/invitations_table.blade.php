<div id="invitations-content">
    @if ($invitations->isEmpty())
        <p>You have no invitations.</p>
    @else
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Organizer</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invitations as $invite)
                    <tr>
                        <td>{{ $invite->event->name }}</td>
                        <td>{{ $invite->event->date }}</td>
                        <td>{{ $invite->event->organizer->name }}</td>
                        <td class="text-center">
                            <!-- Accept Invitation -->
                            <form action="{{ route('events.accept', $invite->event_id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="btn btn-success btn-sm">Accept</button>
                            </form>

                            <!-- Decline Invitation with Confirmation -->
                            <form action="{{ route('events.reject', $invite->event_id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm confirmation-button delete" data-confirm="Are you sure you want to decline this invitation?">
                                    Decline
                                </button>
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
