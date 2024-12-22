<div id="attending-content">
    @if ($events->isEmpty())
        <p>You are not attending any events.</p>
    @else
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Venue</th>
                    <th>Organizer</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($events as $event)
                    <tr>
                        <td>
                            <a href="{{ route('events.show', $event->event_id) }}">{{ $event->name }}</a>
                        </td>
                        <td>{{ $event->date }}</td>
                        <td>{{ $event->venue->name }}</td>
                        <td>{{ $event->organizer->name }}</td>
                        <td class="text-center" style="vertical-align: middle;">
                            <!-- Leave Button with Confirmation -->
                            <form action="{{ route('events.leave', $event->event_id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm confirmation-button delete" data-confirm="Are you sure you want to leave this event?">
                                    Leave
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
    @endif
</div>
