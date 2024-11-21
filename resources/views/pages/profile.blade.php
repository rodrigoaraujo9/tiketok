@extends('layouts.app')
@section('content')
<div class="page-container">
    <h1 class="page-title">Profile</h1>
    <div class="profile-content">
        <div class="profile-section">
            <h2>Personal Information</h2>
            <div class="profile-info">
                <div class="info-item">
                    <label>Name:</label>
                    <span>{{ Auth::user()->name }}</span>
                </div>
                <div class="info-item">
                    <label>Email:</label>
                    <span>{{ Auth::user()->email }}</span>
                </div>
            </div>
        </div>
        
        <div class="profile-section">
            <h2>My Tickets</h2>
            <div class="tickets-list">
                <!-- Add ticket items here -->
            </div>
        </div>
    </div>
</div>
@endsection 