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

        <label>Теги</label>
        <select name="tags[]" class="tag-select" multiple size="6">
            @foreach($availableTags ?? [] as $value => $label)
                <option value="{{ $value }}" {{ in_array($value, $tags ?? [], true) ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
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
</section>
<style>
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
</style>
@endsection
