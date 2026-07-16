@extends('template.app')

@section('page')
<section class="form-card">
    <h2>Все мероприятия</h2>

    <div class="form-group">
        <label>Поиск мероприятия</label>
        <input type="text" placeholder="Введите название или место">
    </div>

    <div class="form-group">
        <label>Фильтр</label>
        <select>
            <option>Все типы</option>
            <option>Встречи</option>
            <option>Лекции</option>
            <option>Обмены</option>
        </select>
    </div>

    <div class="object-grid">
        <div class="card">
            <div class="card-content">
                <h3 class="card-title">Книжная встреча</h3>
                <p>Дата: 20 июля · Москва</p>
                <a href="{{ route('event.show', 1) }}" class="btn btn-outline">Подробнее</a>
            </div>
        </div>

        <div class="card">
            <div class="card-content">
                <h3 class="card-title">Лекция по современной литературе</h3>
                <p>Дата: 25 июля · Казань</p>
                <a href="{{ route('event.show', 2) }}" class="btn btn-outline">Подробнее</a>
            </div>
        </div>
    </div>
</section>
@endsection
