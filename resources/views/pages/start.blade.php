@extends('template.app')

@section('page')
    <!-- ========== СПИСОК МЕРОПРИЯТИЙ ========== -->
    <div class="container" style="margin-bottom: 60px;" id="events-list">
        <h2 class="section-title">Лента мероприятий</h2>

        <form method="GET" class="filter-panel" style="margin-bottom: 20px;">
            <div class="filter-grid">
                <div class="filter-field">
                    <label>Поиск мероприятия</label>
                    <input type="text" name="q" value="{{ $query ?? '' }}" placeholder="Введите название, место или тег">
                </div>

                <div class="filter-field">
                    <label>От</label>
                    <input type="date" name="date_from" value="{{ $dateFrom ?? '' }}">
                </div>

                <div class="filter-field">
                    <label>До</label>
                    <input type="date" name="date_to" value="{{ $dateTo ?? '' }}">
                </div>
            </div>

            <div class="filter-field" style="margin-top: 12px;">
                <label>Теги</label>
                <select name="tags[]" class="tag-select" multiple size="6">
                    @foreach($availableTags ?? [] as $value => $label)
                        <option value="{{ $value }}" {{ in_array($value, $tags ?? [], true) ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <input type="submit" class="btn btn-outline" value="Применить" style="margin-top: 12px;">
        </form>

        @if($events->count() > 0)
            <div class="object-grid">
                @foreach ($events as $event)
                    <div class="card" data-event-id="{{ $event->id }}">
                        <div class="card-img">
                            <img src="{{ $event->image_url }}" alt="{{ $event->title }}">
                        </div>
                        <div class="card-content">
                            <h3 class="card-title">{{ $event->title }}</h3>
                            <div class="card-meta">
                                {{ $event->date }} {{ $event->place }}
                            </div>
                            @if(!empty($event->tags_labels))
                                <div class="card-meta" style="margin-top: 6px; font-size: 13px; color: #64748b;">
                                    {{ implode(', ', $event->tags_labels) }}
                                </div>
                            @endif
                            <a href="{{ route('event.show', $event->id) }}" class="btn btn-outline">
                                Подробнее
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <h3>Мероприятий не найдено</h3>
                <p>Попробуйте изменить параметры поиска.</p>
                <a href="{{ route('home') }}" class="btn btn-outline">
                    Показать все мероприятия
                </a>
            </div>
        @endif
    </div>

        <!-- ========== 3. КАРТА ========== -->
    <div class="container-map">
        <div class="map-section">
            <h2 class="section-title" style="margin-bottom: 15px;">Карта мероприятий</h2>
            <div id="map" class="map-container"></div>
            <div class="map-info">
                <span id="markers-count">Загрузка маркеров...</span>
            </div>
        </div>
    </div>


    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        .map-section { margin-bottom: 50px; }
        .map-container {
            height: 500px;
            width: 100%;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            z-index: 1;
            background: #e2e8f0;
        }
        .map-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding: 15px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        #markers-count { font-weight: 600; color: #3b82f6; }
        .btn-sm { padding: 8px 16px; font-size: 14px; }

        .custom-marker {
            background: #f59e0b;  /* оранжевый для мероприятий */
            border: 3px solid white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            transition: transform 0.2s;
        }
        .custom-marker:hover { transform: scale(1.1); z-index: 1000 !important; }
        .filter-panel {
            background: #f8f4ea;
            border: 1px solid #e8dcc8;
            border-radius: 16px;
            padding: 18px;
        }
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
        }
        .filter-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .filter-field label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #6b4c1d;
            text-transform: none;
        }
        .filter-field input,
        .filter-field select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d8c8a8;
            border-radius: 10px;
            background: #fffdf9;
        }
        .tag-select {
            width: 100%;
            min-height: 140px;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            background: #fff;
            color: #0f172a;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
        }
        .tag-select option {
            padding: 8px 10px;
            border-radius: 8px;
            margin: 2px 0;
        }
        .tag-select option:checked {
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            color: #fff;
        }

        .leaflet-popup-content-wrapper { border-radius: 12px; padding: 0; overflow: hidden; }
        .leaflet-popup-content { margin: 0; width: 250px !important; }
        .popup-card { padding: 15px; }
        .popup-card h3 { margin: 0 0 10px 0; font-size: 16px; color: #1e293b; }
        .popup-card p { margin: 5px 0; font-size: 14px; color: #64748b; }
        .popup-card .btn { margin-top: 10px; padding: 6px 12px; font-size: 13px; }

        @media(max-width: 768px) {
            .map-container { height: 350px; }
            .map-info { flex-direction: column; gap: 10px; text-align: center; }
        }
    </style>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Данные для маркеров – переданы из контроллера
            const markersData = @json($mapMarkers ?? []);

            if (!document.getElementById('map')) return;

            // Карта по умолчанию на Москву, если нет маркеров
            const map = L.map('map').setView([55.751244, 37.618423], 10);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            // Кастомная иконка
            const createCustomIcon = () => L.divIcon({
                className: 'custom-marker',
                html: '📍',
                iconSize: [42, 42],
                iconAnchor: [21, 42],
                popupAnchor: [0, -42]
            });

            let markersCount = 0;
            const markersGroup = [];

            markersData.forEach((data) => {
                const lat = parseFloat(data.latitude);
                const lon = parseFloat(data.longitude);

                if (!isNaN(lat) && !isNaN(lon)) {
                    const marker = L.marker([lat, lon], {
                        icon: createCustomIcon()
                    }).addTo(map);

                    const popupContent = `
                        <div class="popup-card">
                            <h3>${data.title}</h3>
                            <p> ${data.place}</p>
                            <p> ${new Date(data.date).toLocaleDateString('ru-RU')}</p>
                            <a href="${data.url}" class="btn btn-primary" style="display: inline-block; text-decoration: none; background: #3b82f6; color: white; padding: 6px 12px; border-radius: 6px;">
                                Подробнее
                            </a>
                        </div>
                    `;

                    marker.bindPopup(popupContent);

                    // При клике на маркер – скролл к карточке
                    marker.on('click', function() {
                        const card = document.querySelector(`[data-event-id="${data.id}"]`);
                        if (card) {
                            card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            card.style.boxShadow = '0 0 0 4px rgba(245, 158, 11, 0.5)';
                            setTimeout(() => { card.style.boxShadow = ''; }, 2000);
                        }
                    });

                    markersGroup.push(marker);
                    markersCount++;
                }
            });

            document.getElementById('markers-count').textContent =
                `Показано мероприятий: ${markersCount}`;

            if (markersGroup.length > 0) {
                const group = new L.featureGroup(markersGroup);
                map.fitBounds(group.getBounds().pad(0.1));
            }

            // Кнопка скрытия/показа списка
            const toggleBtn = document.getElementById('toggle-map-view');
            const eventsList = document.getElementById('events-list');
            if (toggleBtn && eventsList) {
                toggleBtn.addEventListener('click', function() {
                    if (eventsList.style.display === 'none') {
                        eventsList.style.display = 'block';
                        this.textContent = 'Скрыть список';
                    } else {
                        eventsList.style.display = 'none';
                        this.textContent = 'Показать список';
                        document.querySelector('.map-section').scrollIntoView({ behavior: 'smooth' });
                    }
                });
            }
        });
    </script>
@endsection