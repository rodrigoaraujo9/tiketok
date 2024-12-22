<div id="events-content">
    <table class="table table-striped table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Date</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($events as $event)
                <tr>
                    <td>{{ $event->name }}</td>
                    <td>{{ $event->date }}</td>
                    <td class="text-center">
                        <form action="{{ route('events.invite', $event->event_id) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="email" name="email" class="form-control" placeholder="Invite user by email" required>
                            <button class="btn btn-primary btn-sm mt-2">Send Invite</button>
                        </form>
                        <a href="{{ route('events.attendees', $event->event_id) }}" class="btn btn-outline-info btn-sm mt-2">
                            Manage Attendees
                        </a>
                        <a href="{{ route('events.edit', $event->event_id) }}" class="btn btn-outline-success btn-sm mt-2">
                            Edit Event
                        </a>
                        <!-- Delete Event Button -->
                        <form action="{{ route('events.destroy', $event->event_id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm mt-2 confirmation-button delete" data-confirm="Are you sure you want to delete this event?">
                                Delete Event
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination-container d-flex justify-content-center mt-4">
        {{ $events->links('pagination::bootstrap-4') }}
    </div>
</div>
