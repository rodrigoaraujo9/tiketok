@extends('layouts.app')

@section('content')
<div class="container">
    <h1> My Reports</h1>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Back to Dashboard</a>

    @if ($reports->count() == 0)
        <p>You dont have any reports.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Updated</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)
                    <tr>
                        <td>{{ $report->event->name }}</td>
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
    @endif
</div>

@endsection