@extends('template.app')

@section('page')
<section class="form-card">
    <h2>Объявление</h2>

    <div class="detail-card">
        <div class="detail-content">
            <h3>{{ $exchange->title }}</h3>
            <p><strong>Описание:</strong> {{ $exchange->description }}</p>
            <p><strong>Место:</strong> {{ $exchange->place }}</p>
            <p><strong>Дата:</strong> {{ $exchange->date }}</p>
            <p><strong>Контакты:</strong> {{ $exchange->contacts }}</p>
            <p><strong>Статус:</strong> {{ $exchange->status === 'booked' ? 'Забронировано' : 'Активно' }}</p>

            @if($exchange->latitude && $exchange->longitude)
                <div style="margin-top: 16px;">
                    <h4 style="margin-bottom: 8px;">Карта</h4>
                    <div id="exchange-detail-map" style="height: 320px; width: 100%; border-radius: 16px; border: 1px solid #ddd;"></div>
                </div>
            @endif

            @auth
                @if(auth()->user()->id !== $exchange->user_id)
                    @if($exchange->status !== 'booked')
                        <form method="POST" action="{{ route('exchange.book', $exchange->id) }}" style="margin-top: 12px;">
                            @csrf
                            <button class="btn btn-primary">Забронировать</button>
                        </form>
                    @else
                        <p style="color: #dc2626; margin-top: 12px;">Книга уже забронирована.</p>
                    @endif
                @else
                    <p style="color: #64748b; margin-top: 12px;">Это ваше объявление, бронирование недоступно.</p>
                @endif

                @if(auth()->user()->canManageBookExchange($exchange))
                    <div class="flex" style="margin-top: 12px;">
                        <a href="{{ route('exchange.edit', $exchange->id) }}" class="btn btn-edit">Редактировать</a>
                        <a href="{{ route('exchange.delete', $exchange->id) }}" class="btn btn-delete">Удалить</a>
                    </div>
                @endif
            @endauth
        </div>
    </div>
</section>

@if($exchange->latitude && $exchange->longitude)
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const lat = {{ (float) $exchange->latitude }};
            const lng = {{ (float) $exchange->longitude }};
            const map = L.map('exchange-detail-map').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap',
                maxZoom: 19
            }).addTo(map);

            L.marker([lat, lng]).addTo(map)
                .bindPopup('{{ addslashes($exchange->place) }}')
                .openPopup();
        });
    </script>
@endif
@endsection
