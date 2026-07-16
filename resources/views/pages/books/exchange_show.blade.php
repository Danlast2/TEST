@extends('template.app')

@section('page')
<section class="form-card">
    <h2>Объявление</h2>

    <div class="detail-card">
        <div class="detail-content">
            <h3>Обмен «Война и мир»</h3>
            <p><strong>Описание:</strong> Готов обменять на книгу по психологии.</p>
            <p><strong>Тип:</strong> Обмен</p>
            <div class="flex">
                <a href="{{ route('exchange.edit', 1) }}" class="btn btn-edit">Редактировать</a>
                <a href="{{ route('exchange.delete', 1) }}" class="btn btn-delete">Удалить</a>
            </div>
        </div>
    </div>
</section>
@endsection
