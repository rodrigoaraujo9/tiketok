<div id="users-content">
    @if($users->isEmpty())
        <p>No users found.</p>
    @else
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td class="text-center">
                            <!-- Block or Unblock User -->
                            @if(!$user->is_blocked)
                                <form action="{{ route('users.block', $user->user_id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm confirmation-button" data-confirm="Are you sure you want to block this user?">
                                        Block
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('users.unblock', $user->user_id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm confirmation-button" data-confirm="Are you sure you want to unblock this user?">
                                        Unblock
                                    </button>
                                </form>
                            @endif
                            <!-- Delete User -->
                            <form action="{{ route('users.delete', $user->user_id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm confirmation-button delete" data-confirm="Are you sure you want to delete this user?">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination-container d-flex justify-content-center mt-4">
            {{ $users->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>
