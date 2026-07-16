@extends('template.app')

@section('page')
<section class="form-card">
    <h2>Обмен книгами</h2>

    <div class="flex" style="justify-content: space-between; align-items: center;">
        <h3>Объявления</h3>
        <a href="{{ route('exchange.create') }}" class="btn btn-primary">Выложить объявление</a>
    </div>

    <div class="object-grid">
        @forelse($exchanges as $exchange)
            <div class="card">
                <div class="card-content">
                    <h3 class="card-title">{{ $exchange->title }}</h3>
                    <p>{{ Str::limit($exchange->description, 120) }}</p>
                    <p><strong>Место:</strong> {{ $exchange->place }}</p>
                    <p><strong>Статус:</strong> {{ $exchange->status === 'booked' ? 'Забронировано' : 'Активно' }}</p>
                    <a href="{{ route('exchange.show', $exchange->id) }}" class="btn btn-outline">Подробнее</a>
                </div>
            </div>
        @empty
            <p>Пока нет объявлений.</p>
        @endforelse
    </div>

    <div class="container-map" style="margin-top: 24px;">
        <div class="map-section">
            <h3 class="section-title" style="margin-bottom: 15px;">Карта объявлений</h3>
            <div id="exchange-map" class="map-container"></div>
        </div>
    </div>
</section>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@php
$exchangeMarkers = $exchanges
    ->filter(fn($exchange) => !empty($exchange->latitude) && !empty($exchange->longitude))
    ->map(function ($exchange) {
        return [
            'id' => $exchange->id,
            'title' => $exchange->title,
            'latitude' => (float) $exchange->latitude,
            'longitude' => (float) $exchange->longitude,
            'place' => $exchange->place,
            'url' => route('exchange.show', $exchange->id),
        ];
    })
    ->values();
@endphp
<script>
document.addEventListener('DOMContentLoaded', function () {
    const map = L.map('exchange-map').setView([55.751244, 37.618423], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap',
        maxZoom: 19
    }).addTo(map);

    const markers = @json($exchangeMarkers);

    const markerGroup = [];
    markers.forEach((item) => {
        const marker = L.marker([item.latitude, item.longitude]).addTo(map);
        marker.bindPopup(`
            <div style="padding: 6px;">
                <strong>${item.title}</strong><br>
                ${item.place}<br>
                <a href="${item.url}" style="display:inline-block;margin-top:6px;">Подробнее</a>
            </div>
        `);
        markerGroup.push(marker);
    });

    if (markerGroup.length > 0) {
        const group = new L.featureGroup(markerGroup);
        map.fitBounds(group.getBounds().pad(0.1));
    }
});
</script>
@endsection
