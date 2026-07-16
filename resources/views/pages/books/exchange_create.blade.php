@extends('template.app')

@section('page')
<section class="form-card">
    <h2>Новое объявление</h2>
    <form method="POST" action="{{ route('exchange.store') }}">
        @csrf

        <div class="form-group">
            <label>Название книги</label>
            <input type="text" name="title" placeholder="Введите название">
        </div>

        <div class="form-group">
            <label>Описание</label>
            <textarea name="description" rows="3"></textarea>
        </div>

        <div class="form-group">
            <label>Тип обмена</label>
            <select name="type">
                <option>Обмен</option>
                <option>Дарение</option>
            </select>
        </div>

        <input type="submit" class="btn btn-primary" value="Создать">
    </form>
</section>
@endsection
