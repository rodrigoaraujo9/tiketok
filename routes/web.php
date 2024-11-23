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

// API
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
Route::controller(EventController::class)->group(function () {
    // Dashboard
    Route::get('/dashboard', 'dashboard')->name('dashboard')->middleware('auth');

    // Event creation form (must be placed first)
    Route::get('/events/create', 'create')->middleware('auth')->name('events.create');

    // List all events
    Route::get('/events', 'index')->name('events.index');

    // Show event details
    Route::get('/events/{id}', 'show')->name('events.show');

    // Store a new event
    Route::post('/events', 'store')->middleware('auth')->name('events.store');

    // Edit event form
    Route::get('/events/{id}/edit', 'edit')->middleware('auth')->name('events.edit');

    // Update an event
    Route::put('/events/{id}', 'update')->middleware('auth')->name('events.update');

    // Delete an event
    Route::delete('/events/{id}', 'destroy')->middleware('auth')->name('events.destroy');

    Route::post('/events/{event_id}/join', [EventController::class, 'joinEvent'])->middleware('auth')->name('events.join');
});
