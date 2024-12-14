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
</div>
@endsection