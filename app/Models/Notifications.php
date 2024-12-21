<?php
use App\Models\Notification;

Route::get('/notifications', function () {
    $userId = auth()->id();
    $notifications = Notification::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
    return view('notifications.index', compact('notifications'));
})->middleware('auth')->name('notifications.index');
