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
        <a href="{{ route('events.edit', $report->event_id) }}" class="btn btn-warning">Edit Event</a>
        
        <a href="{{ route('updateReportForm', $report->report_id) }}" class="btn btn-warning">Edit Report</a>

        <form action="{{ route('deleteReport', $report->report_id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete Report</button>
        </form>
        @if (Auth::user()->isAdmin())
            <a href="{{ route('allReports') }}" class="btn btn-secondary">Back to Reports</a>
        @endif
        @if (!Auth::user()->isAdmin())    
            <a href="{{ route('userReports') }}" class="btn btn-secondary">Back to Reports</a>
        @endif
    </div>
@endsection


