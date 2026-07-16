@extends('template.app')

@section('page')
<section class="form-card">
    <h2>Статьи</h2>

    <div class="form-group">
        <label>Фильтр</label>
        <select>
            <option>Все разделы</option>
            <option>Книги</option>
            <option>Клубы</option>
            <option>Обмен</option>
        </select>
    </div>

    <div class="object-grid">
        <div class="card">
            <div class="card-content">
                <h3 class="card-title">Как выбрать книгу для чтения</h3>
                <p>Краткое описание статьи о том, как правильно выбирать литературу для чтения.</p>
                <p><strong>Дата:</strong> 12.07.2026</p>
                <p><strong>Раздел:</strong> Книги</p>
            </div>
        </div>

        <div class="card">
            <div class="card-content">
                <h3 class="card-title">Почему клубы так важны</h3>
                <p>Описание статьи про влияние книжных клубов на развитие интереса к чтению.</p>
                <p><strong>Дата:</strong> 10.07.2026</p>
                <p><strong>Раздел:</strong> Клубы</p>
            </div>
        </div>
    </div>

    <div class="flex" style="justify-content: space-between; align-items: center; margin-top: 20px;">
        <a href="#" class="btn btn-outline">← Назад</a>
        <a href="#" class="btn btn-outline">Вперёд →</a>
    </div>
</section>
@endsection
