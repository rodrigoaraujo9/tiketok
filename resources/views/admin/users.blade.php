@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Users</h1>

    @if(session('message'))
        <div class="alert alert-info">{{ session('message') }}</div>
    @endif

    @if($users->isEmpty())
        <p>No users found.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <!-- Ações para bloquear, desbloquear ou excluir -->
                            <form action="{{ route('users.block', $user->user_id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm">Block</button>
                            </form>
                            <form action="{{ route('users.unblock', $user->user_id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Unblock</button>
                            </form>
                            <form action="{{ route('users.delete', $user->user_id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
