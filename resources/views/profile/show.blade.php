@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Profile</h1>

    @if($user->profile_photo && Storage::disk('public')->exists($user->profile_photo))
        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo" width="150">
    @else
        <p>No profile photo available.</p>
    @endif

    <p><strong>Name:</strong> {{ $user->name }}</p>
    <p><strong>Username:</strong> {{ $user->username }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Phone:</strong> {{ $user->phone }}</p>
    <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
</div>
@endsection