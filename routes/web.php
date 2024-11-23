<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CardController;
use App\Http\Controllers\ItemController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\EventController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home
Route::redirect('/', '/login');

// Cards
Route::controller(CardController::class)->group(function () {
    Route::get('/cards', 'list')->name('cards');
    Route::get('/cards/{id}', 'show');
});


// API (Cards & Items)
Route::controller(CardController::class)->group(function () {
    Route::put('/api/cards', 'create');
    Route::delete('/api/cards/{card_id}', 'delete');
});

Route::controller(ItemController::class)->group(function () {
    Route::put('/api/cards/{card_id}', 'create');
    Route::post('/api/item/{id}', 'update');
    Route::delete('/api/item/{id}', 'delete');
});

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

// Events
Route::controller(EventController::class)->middleware('auth')->group(function () {
    // Event creation form
    Route::get('/events/create', 'create')->name('events.create');

    // List all events
    Route::get('/events', 'index')->name('events.index');

    // Show event details
    Route::get('/events/{event_id}', 'show')
        ->where('event_id', '[0-9]+') // Only allow numeric event_id
        ->name('events.show');

    // Store a new event
    Route::post('/events', 'store')->name('events.store');

    // Edit event form
    Route::get('/events/{event_id}/edit', 'edit')
        ->where('event_id', '[0-9]+') // Only allow numeric event_id
        ->name('events.edit');

    // Update an event
    Route::put('/events/{event_id}', 'update')
        ->where('event_id', '[0-9]+') // Only allow numeric event_id
        ->name('events.update');

    // Delete an event
    Route::delete('/events/{event_id}', 'destroy')
        ->where('event_id', '[0-9]+') // Only allow numeric event_id
        ->name('events.destroy');

    // Manage user's events
    Route::get('/events/manage', 'manage')->name('events.manage');

    // Invitations
    Route::post('/events/{event_id}/invite', 'invite')
        ->where('event_id', '[0-9]+') // Only allow numeric event_id
        ->name('events.invite');

    Route::post('/events/{event_id}/accept', 'acceptInvitation')
        ->where('event_id', '[0-9]+') // Only allow numeric event_id
        ->name('events.accept');

    Route::post('/events/{event_id}/reject', 'rejectInvitation')
        ->where('event_id', '[0-9]+') // Only allow numeric event_id
        ->name('events.reject');

    // View invitations
    Route::get('/invitations', 'listInvitations')->name('events.invitations');

    Route::get('/events/attending', [EventController::class, 'attending'])
    ->middleware('auth')
    ->name('events.attending');

});

