@extends('template.app')

@section('page')
<section class="form-card">
    <h2>Профиль книжного клуба</h2>

    <div class="detail-card">
        <div class="detail-img">
            <img src="https://via.placeholder.com/320x220?text=Club+Avatar" alt="Аватар клуба" height="100%" width="100%">
        </div>
        <div class="detail-content">
            <h3>Название клуба</h3>
            <p><strong>Описание:</strong> Книжный клуб для любителей литературы, обсуждений и совместных встреч.</p>
            <p><strong>Контакты:</strong> club@example.com · +7 (999) 000-00-00</p>

            <div class="flex">
                <a href="#" class="btn btn-primary">Написать</a>
                <a href="#" class="btn btn-edit">Подписаться</a>
            </div>
        </div>
    </div>

    <div class="form-group">
        <h3>Список мероприятий клуба</h3>
        <ul>
            <li>Мастер-класс по чтению вслух — 20 июля</li>
            <li>Обсуждение романа «1984» — 25 июля</li>
            <li>Книжная ярмарка — 30 июля</li>
        </ul>
    </div>

    <div class="form-group">
        <h3>Отзывы</h3>
        <p>«Очень уютное пространство для обсуждений»</p>
        <p>«Много интересных встреч и полезных рекомендаций»</p>
    </div>

    <div class="form-group">
        <h3>Модераторы</h3>
        <p>Анна Иванова</p>
        <p>Сергей Петров</p>
    </div>
</section>
@endsection
