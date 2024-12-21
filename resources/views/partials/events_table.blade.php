<div id="events-content">
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Date</th>
                <th>Venue</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
                <tr>
                    <td>{{ $event->name }}</td>
                    <td>{{ $event->description }}</td>  
                    <td>{{ $event->date }}</td>
                    <td>{{ $event->venue->name }}</td>
                    <td>
                        <a href="{{ route('events.show', $event->event_id) }}" class="btn btn-primary">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination-container d-flex justify-content-center mt-4">
        {{ $events->links('pagination::bootstrap-4') }}
    </div>
</div>
