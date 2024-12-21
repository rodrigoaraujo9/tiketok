<div id="attending-content">
    @if ($events->isEmpty())
        <p>You are not attending any events.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Venue</th>
                    <th>Organizer</th>
                    <th>Actions</th>
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
                        <td style="vertical-align: middle;">
                            <form action="{{ route('events.leave', $event->event_id) }}" method="POST" style="display: inline-block; margin: 0; padding: 0;">
                                @csrf
                                @method('DELETE')
                                <button class="delete btn btn-danger btn-sm">Leave</button>
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
