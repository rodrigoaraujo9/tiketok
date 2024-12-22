@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Profile Card -->
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h1 class="mb-4 text-primary">Profile</h1>

                    <!-- Profile Photo -->
                    @if($user->profile_photo && Storage::disk('public')->exists($user->profile_photo))
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                             alt="Profile Photo" 
                             class="profile-photo rounded-circle mb-4">
                    @else
                        <p>No profile photo available.</p>
                    @endif

                    <!-- Profile Information -->
                    <p><strong><i class="fas fa-user"></i> Name:</strong> {{ $user->name }}</p>
                    <p><strong><i class="fas fa-at"></i> Username:</strong> {{ $user->username }}</p>
                    <p><strong><i class="fas fa-envelope"></i> Email:</strong> {{ $user->email }}</p>
                    <p><strong><i class="fas fa-phone"></i> Phone:</strong> {{ $user->phone }}</p>
                    
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary mt-3">Edit Profile</a>

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="alert alert-danger mt-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="alert alert-success mt-4">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
