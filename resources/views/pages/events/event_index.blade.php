@extends('template.app')

@section('page')
<div class="container" style="margin-bottom: 60px;">
    <div class="page-shell">
        <div class="page-header">
            <div>
                <h2 class="section-title" style="margin: 0 0 8px;">Все мероприятия</h2>
                <p class="page-subtitle">Ищите события по дате, тегам и ключевому слову в одном удобном списке.</p>
            </div>
        </div>

        <form method="GET" class="filter-panel">
            <div class="filter-grid">
                <div class="filter-field">
                    <label>Поиск мероприятия</label>
                    <input type="text" name="q" value="{{ $query ?? '' }}" placeholder="Введите название или место">
                </div>

                <div class="filter-field">
                    <label>От</label>
                    <input type="date" name="date_from" value="{{ $dateFrom ?? '' }}">
                </div>

                <div class="filter-field">
                    <label>До</label>
                    <input type="date" name="date_to" value="{{ $dateTo ?? '' }}">
                </div>

                <div class="filter-field">
                    <label>Сортировка</label>
                    <select name="sort">
                        <option value="date" {{ ($sort ?? 'date') === 'date' ? 'selected' : '' }}>По дате</option>
                        <option value="popular" {{ ($sort ?? '') === 'popular' ? 'selected' : '' }}>По популярности</option>
                    </select>
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

        <div class="object-grid">
            @forelse($events as $event)
                <div class="card">
                    <div class="card-img">
                        <img src="{{ $event->image_url }}" alt="{{ $event->title }}" height="100%" width="100%">
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">{{ $event->title }}</h3>
                        <p>Дата: {{ $event->date }} · {{ $event->place }}</p>
                        @if(!empty($event->tags_labels))
                            <p>Теги: {{ implode(', ', $event->tags_labels) }}</p>
                        @endif
                        <p>Записалось: {{ $event->registered_count }}</p>
                        <a href="{{ route('event.show', $event->id) }}" class="btn btn-outline">Подробнее</a>
                    </div>
                </div>
            @empty
                <p>Мероприятий не найдено.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
