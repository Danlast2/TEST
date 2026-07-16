@extends('template.app')

@section('page')
<section class="form-card">
    <h2>Обмен книгами</h2>

    <div class="flex" style="justify-content: space-between; align-items: center;">
        <h3>Объявления</h3>
        <a href="{{ route('exchange.create') }}" class="btn btn-primary">Выложить объявление</a>
    </div>

    <div class="object-grid">
        <div class="card">
            <div class="card-content">
                <h3 class="card-title">Обмен «Война и мир»</h3>
                <p>Готов обменять на книгу по психологии.</p>
                <a href="{{ route('exchange.show', 1) }}" class="btn btn-outline">Подробнее</a>
            </div>
        </div>

        <div class="card">
            <div class="card-content">
                <h3 class="card-title">Подари «Преступление и наказание»</h3>
                <p>Отдам бесплатно в хорошем состоянии.</p>
                <a href="{{ route('exchange.show', 2) }}" class="btn btn-outline">Подробнее</a>
            </div>
        </div>
    </div>
</section>
@endsection
