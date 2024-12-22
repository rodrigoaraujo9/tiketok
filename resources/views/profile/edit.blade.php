@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Profile</h1>
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" class="form-control" value="{{ $user->username }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
        </div>
        <div class="form-group">
            <label for="profile_photo">Profile Photo</label>
            <input type="file" name="profile_photo" class="form-control">
        </div>
        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Leave blank to keep current password">
        </div>
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif
        <!-- Add a confirmation button -->
        <button type="submit" class="btn btn-success confirmation-button" data-confirm="Are you sure you want to update your profile?">Update Profile</button>
    </form>
</div>
@endsection
