@extends('template.app')

@section('page')
<section class="form-card">
    <h2>Все мероприятия</h2>

    <form method="GET" class="form-group">
        <label>Поиск мероприятия</label>
        <input type="text" name="q" value="{{ $query ?? '' }}" placeholder="Введите название или место">
    </form>

    <form method="GET" class="form-group">
        <label>Фильтр по дате</label>
        <select name="date_filter">
            <option value="all" {{ ($dateFilter ?? 'all') === 'all' ? 'selected' : '' }}>Все</option>
            <option value="upcoming" {{ ($dateFilter ?? '') === 'upcoming' ? 'selected' : '' }}>Предстоящие</option>
            <option value="past" {{ ($dateFilter ?? '') === 'past' ? 'selected' : '' }}>Прошедшие</option>
        </select>

        <label>Сортировка</label>
        <select name="sort">
            <option value="date" {{ ($sort ?? 'date') === 'date' ? 'selected' : '' }}>По дате</option>
            <option value="popular" {{ ($sort ?? '') === 'popular' ? 'selected' : '' }}>По популярности</option>
        </select>

        <input type="submit" class="btn btn-outline" value="Применить">
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
                    <p>Записалось: {{ $event->registered_count }}</p>
                    <a href="{{ route('event.show', $event->id) }}" class="btn btn-outline">Подробнее</a>
                </div>
            </div>
        @empty
            <p>Мероприятий не найдено.</p>
        @endforelse
    </div>
</section>
@endsection
