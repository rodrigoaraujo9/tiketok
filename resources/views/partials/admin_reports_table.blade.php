<div id="reports-content">
    @if($reports->isEmpty())
        <p>You don’t have any reports.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Event</th>
                    <th>User</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                    <tr>
                        <td>{{ $report->report_id }}</td>
                        <td>{{ $report->event_id }}</td>
                        <td>{{ $report->user_id }}</td>
                        <td>{{ $report->r_status }}</td>
                        <td>{{ $report->created_at }}</td>
                        <td>{{ $report->updated_at }}</td>
                        <td>
                            <a href="{{ route('showReport', $report->report_id) }}" class="btn btn-primary">View</a>
                        </td>
                    </tr>    
                @endforeach
            </tbody>
        </table>
        <div class="pagination-container d-flex justify-content-center mt-4">
            {{ $reports->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>
