@extends('template.app')

@section('page')
<section class="form-card">
    <h2>Редактирование объявления</h2>
    <form method="POST" action="{{ route('exchange.update', 1) }}">
        @csrf

        <div class="form-group">
            <label>Название книги</label>
            <input type="text" name="title" value="Обмен «Война и мир»">
        </div>

        <div class="form-group">
            <label>Описание</label>
            <textarea name="description" rows="3">Готов обменять на книгу по психологии.</textarea>
        </div>

        <div class="form-group">
            <label>Тип обмена</label>
            <select name="type">
                <option selected>Обмен</option>
                <option>Дарение</option>
            </select>
        </div>

        <input type="submit" class="btn btn-primary" value="Сохранить">
    </form>
</section>
@endsection
