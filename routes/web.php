<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Define all routes for public and authenticated pages, ensuring proper 
| redirection and authentication middleware where necessary.
|
*/

// Public Routes
Route::get('/', function () {
    return redirect()->route('events.index'); // Default route redirects to events list
});

Route::controller(EventController::class)->group(function () {
    // Publicly accessible event pages
    Route::get('/events', 'index')->name('events.index'); // List all events
    Route::get('/events/{event_id}', 'show')
        ->where('event_id', '[0-9]+') // Only allow numeric event_id
        ->name('events.show'); // Show event details
});

// Authentication
Route::middleware(['guest'])->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'showLoginForm')->name('login'); // Login form
        Route::post('/login', 'authenticate'); // Handle login
    });

    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'showRegistrationForm')->name('register'); // Registration form
        Route::post('/register', 'register'); // Handle registration
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout'); // Logout

    // Authenticated Routes for Events
    Route::controller(EventController::class)->group(function () {
        Route::get('/events/create', 'create')->name('events.create'); // Create event form
        Route::post('/events', 'store')->name('events.store'); // Store new event
        Route::get('/events/{event_id}/edit', 'edit')
            ->where('event_id', '[0-9]+') // Only allow numeric event_id
            ->name('events.edit'); // Edit event form
        Route::put('/events/{event_id}', 'update')
            ->where('event_id', '[0-9]+') // Only allow numeric event_id
            ->name('events.update'); // Update event
        Route::delete('/events/{event_id}', 'destroy')
            ->where('event_id', '[0-9]+') // Only allow numeric event_id
            ->name('events.destroy'); // Delete event

        // Manage Events
        Route::get('/events/manage', 'manage')->name('events.manage');

        // Invitations
        Route::post('/events/{event_id}/invite', 'invite')
            ->where('event_id', '[0-9]+') // Only allow numeric event_id
            ->name('events.invite'); // Invite to event
        Route::get('/invitations', 'listInvitations')->name('events.invitations');
        Route::post('/events/{event_id}/accept', 'acceptInvitation')
            ->where('event_id', '[0-9]+') // Only allow numeric event_id
            ->name('events.accept');
        Route::post('/events/{event_id}/reject', 'rejectInvitation')
            ->where('event_id', '[0-9]+') // Only allow numeric event_id
            ->name('events.reject');

        // Join and Leave Events
        Route::post('/events/{event_id}/join', 'joinEvent')
            ->where('event_id', '[0-9]+') // Only allow numeric event_id
            ->name('events.join');
        Route::delete('/events/{event_id}/leave', 'leaveEvent')
            ->where('event_id', '[0-9]+') // Only allow numeric event_id
            ->name('events.leave');

        // Attending and Dashboard
        Route::get('/events/attending', 'attending')->name('events.attending');
        Route::get('/dashboard', 'dashboard')->name('dashboard');
    });

    // Reports
    Route::controller(ReportController::class)->group(function () {
        Route::get('user/userReports/{user_id?}', 'userReports')->name('userReports');
        Route::get('user/newReport/{event_id}', 'createReportForm')->name('createReportForm');
        Route::post('user/newReport', 'createReport')->name('createReport');
        Route::get('user/editReport/{report_id}', 'updateReportForm')->name('updateReportForm');
        Route::put('user/editReport/{report_id}', 'updateReport')->name('updateReport');
    });

    // Profile
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

// Admin Routes
Route::middleware(['auth', 'can:admin-access'])->group(function () {
    Route::controller(ReportController::class)->group(function () {
        Route::get('admin/reports', 'allReports')->name('allReports');
        Route::delete('admin/report/{id}', 'deleteReport')->name('deleteReport');
    });

    Route::post('adminDestroy', [EventController::class, 'adminDestroy'])->name('destroyAdmin');
});

// Polls
Route::middleware(['auth'])->prefix('events/{event_id}/polls')->group(function () {
    Route::get('/', [PollController::class, 'index'])->name('polls.index');
    Route::get('/create', [PollController::class, 'create'])->name('polls.create');
    Route::post('/', [PollController::class, 'store'])->name('polls.store');
    Route::post('/{poll_id}/vote', [PollController::class, 'vote'])->name('polls.vote');
    Route::delete('/{poll_id}/vote', [PollController::class, 'deleteVote'])->name('polls.deleteVote');
    Route::delete('/{poll_id}', [PollController::class, 'destroy'])->name('polls.destroy');
});

// Comments
Route::middleware(['auth'])->group(function () {
    Route::post('/events/{event_id}/comments', [EventController::class, 'addComment'])->name('comments.add');
    Route::put('/comments/{comment_id}', [EventController::class, 'editComment'])->name('comments.edit');
    Route::delete('/comments/{comment_id}', [EventController::class, 'deleteComment'])->name('comments.delete');
});

// About Us
Route::get('/about', function () {
    return view('about_us');
})->name('about');

// Search Events
Route::get('/events/search', [EventController::class, 'search'])->name('events.search');

// Manage Attendees
Route::middleware(['auth'])->group(function () {
    Route::get('/events/{event}/attendees', [EventController::class, 'attendees'])->name('events.attendees');
    Route::post('/events/{event}/attendees/remove', [EventController::class, 'removeAttendee'])->name('events.attendees.remove');
});

// Attendees List
Route::get('/events/{event_id}/attendees/list', [EventController::class, 'viewAttendeesList'])
    ->where('event_id', '[0-9]+')
    ->name('events.attendees.list');
