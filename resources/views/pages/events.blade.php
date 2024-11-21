@extends('layouts.app')
@section('content')
<div class="page-container">
    <h1 class="page-title">Events</h1>
    <div class="events-filter">
        <button class="filter-button active">All</button>
        <button class="filter-button">Music</button>
        <button class="filter-button">Sports</button>
        <button class="filter-button">Theater</button>
    </div>
    <div class="events-grid">
        <div class="event-card">
            <div class="event-date">07/10/2024</div>
            <h2 class="event-title">Evento 1</h2>
            <div class="event-image"></div>
            <div class="event-details">
                <span class="event-location"><i class="fas fa-map-marker-alt"></i> Location</span>
                <span class="event-price">€29.99</span>
            </div>
            <a href="#" class="event-link">SABER MAIS ⟶</a>
        </div>

        <div class="event-card">
            <div class="event-date">15/10/2024</div>
            <h2 class="event-title">Evento 2</h2>
            <div class="event-image"></div>
            <div class="event-details">
                <span class="event-location"><i class="fas fa-map-marker-alt"></i> Location</span>
                <span class="event-price">€45.00</span>
            </div>
            <a href="#" class="event-link">SABER MAIS ⟶</a>
        </div>

        <div class="event-card">
            <div class="event-date">22/10/2024</div>
            <h2 class="event-title">Evento 3</h2>
            <div class="event-image"></div>
            <div class="event-details">
                <span class="event-location"><i class="fas fa-map-marker-alt"></i> Location</span>
                <span class="event-price">€35.00</span>
            </div>
            <a href="#" class="event-link">SABER MAIS ⟶</a>
        </div>

        <div class="event-card">
            <div class="event-date">29/10/2024</div>
            <h2 class="event-title">Evento 4</h2>
            <div class="event-image"></div>
            <div class="event-details">
                <span class="event-location"><i class="fas fa-map-marker-alt"></i> Location</span>
                <span class="event-price">€25.50</span>
            </div>
            <a href="#" class="event-link">SABER MAIS ⟶</a>
        </div>

        <div class="event-card">
            <div class="event-date">05/11/2024</div>
            <h2 class="event-title">Evento 5</h2>
            <div class="event-image"></div>
            <div class="event-details">
                <span class="event-location"><i class="fas fa-map-marker-alt"></i> Location</span>
                <span class="event-price">€30.00</span>
            </div>
            <a href="#" class="event-link">SABER MAIS ⟶</a>
        </div>

        <div class="event-card">
            <div class="event-date">12/11/2024</div>
            <h2 class="event-title">Evento 6</h2>
            <div class="event-image"></div>
            <div class="event-details">
                <span class="event-location"><i class="fas fa-map-marker-alt"></i> Location</span>
                <span class="event-price">€28.00</span>
            </div>
            <a href="#" class="event-link">SABER MAIS ⟶</a>
        </div>
    </div>
</div>
@endsection 