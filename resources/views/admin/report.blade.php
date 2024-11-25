@extends('layouts.app')
            
@section('content')
    <div class="container">
        <h1>Report {{ $report->report_id }}</h1>

        <p><strong>Event ID:</strong> {{ $report->event_id }}</p>
        <p><strong>Event Name:</strong> {{ $report->event->name }}</p>
        <p><strong>User ID:</strong> {{ $report->description }}</p>
        <p><strong>User Name:</strong> {{ $report->user->name }}</p>
        <p><strong>Reason:</strong> {{ $report->reason }}</p>
        <p><strong>Status:</strong> {{ $report->r_status}}</p>
        <p><strong>Created at:</strong> {{ $report->created_at }}</p>
        <p><strong>Updated at:</strong> {{ $report->updated_at }}</p>
        <a href="{{ route('updateReport', $report->report_id) }}" class="btn btn-warning">Edit Report</a>

        <a href="{{ route('allReports') }}" class="btn btn-secondary">Back to Reports</a>
    </div>
@endsection


