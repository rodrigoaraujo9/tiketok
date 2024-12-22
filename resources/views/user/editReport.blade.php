@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Report: {{ $report->report_id }}</h1>

    <form action="{{ route('updateReport', $report->report_id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="event_id" value="{{ $event->event_id }}">
        
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

        <!-- Add confirmation-button class and data-confirm message -->
        <button type="submit" class="btn btn-danger confirmation-button" data-confirm="Are you sure you want to update this report?">
            Update Report
        </button>
    </form>

    <a href="{{ route('showReport', $report->report_id) }}" class="btn btn-secondary">Back to Report</a>
</div>
@endsection
