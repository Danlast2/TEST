@extends('template.app')

@section('page')
<section class="form-card">
    <h2>Панель администратора</h2>

    <div class="form-group">
        <label>Поиск пользователя</label>
        <input type="text" placeholder="Введите имя или email">
    </div>

    <div class="form-group">
        <h3>Список пользователей</h3>
        <ul>
            <li>Алексей Иванов — admin</li>
            <li>Мария Смирнова — user</li>
            <li>Илья Козлов — moderator</li>
        </ul>
    </div>

    <div class="form-group">
        <h3>Смена роли</h3>
        <select>
            <option>admin</option>
            <option>moderator</option>
            <option>user</option>
        </select>
        <button class="btn btn-primary">Сохранить</button>
    </div>
</section>
@endsection
