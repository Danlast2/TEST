@extends('template.app')

@section('page')
<section class="form-card">
    <h2>Клубы</h2>

    <div class="form-group">
        <label>Поиск клуба</label>
        <input type="text" placeholder="Введите название клуба">
    </div>

    <div class="form-group">
        <label>Фильтр</label>
        <select>
            <option>Все направления</option>
            <option>Фантастика</option>
            <option>Детектив</option>
            <option>История</option>
        </select>
    </div>

    <div class="object-grid">
        <div class="card">
            <div class="card-content">
                <h3 class="card-title">Клуб «Книжные странствия»</h3>
                <p>Обсуждаем современные романы и классическую литературу.</p>
                <a href="{{ route('club.profile') }}" class="btn btn-outline">Подробнее</a>
            </div>
        </div>

        <div class="card">
            <div class="card-content">
                <h3 class="card-title">Клуб «Тайны страниц»</h3>
                <p>Любители детективов и мистики.</p>
                <a href="{{ route('club.profile') }}" class="btn btn-outline">Подробнее</a>
            </div>
        </div>
    </div>
</section>
@endsection
