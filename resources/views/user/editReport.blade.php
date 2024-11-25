<form action="{{ route('updateReport', $reports->report_id) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label for="reason">Reason</label>
        <input type="text" id="reason" name="reason" value="{{ old('reason', $reports->reason) }}" required>
    </div>
</form>