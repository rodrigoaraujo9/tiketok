@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Report: {{ $report->report_id }}</h1>

    <form action="{{ route('createReport') }}" method="POST">
        @csrf
        <input type="hidden" name="event_id" value="{{ $event->event_id }}">
        <input type="hidden" name="user_id" value="{{ Auth::id() }}">

        <div class="form-group">
            <label for="reason">Reason</label>
            <input type="text" id="reason" name="reason" value="{{ old('reason', $report->reason) }}" required>
        </div>

        @if (Auth::user()->isAdmin())
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="pending" {{ $report->r_status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="investigating" {{ $report->r_status == 'investigating' ? 'selected' : '' }}>Investigating</option>
                    <option value="resolved" {{ $report->r_status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="dismissed" {{ $report->r_status == 'dismissed' ? 'selected' : '' }}>Dismissed</option>
                </select>
            </div>
        @endif

        <button type="submit" class="btn btn-danger">Update Report</button>
    </form>

    <a href="{{ route('events.show', $event->event_id) }}" class="btn btn-secondary">Back to Report</a>
</div>
@endsection