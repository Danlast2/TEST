@extends('template.app')
@section('page')

<section class="form-card">
        <h2>Добавить мероприятие</h2>
        <form method="POST" action="" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Название</label>
            <input type="text" name="title" placeholder="" value="{{ old('title') }}">
            @error('title')
                <span class="error">* {{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Дата и время</label>
            <input type="datetime-local" name="date" placeholder="" value="{{ old('date') }}">
            @error('date')
                <span class="error">* {{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Место</label>
            <input type="text" name="place" placeholder="" value="{{ old('place') }}">
            @error('place')
                <span class="error">* {{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Описание</label>
            <textarea name="description" rows="3">{{ old('description') }}</textarea>
            @error('description')
                <span class="error">* {{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Минимум записей</label>
            <input type="number" name="min_entries" min="0" value="{{ old('min_entries', 0) }}">
            @error('min_entries')
                <span class="error">* {{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Максимум записей</label>
            <input type="number" name="max_entries" min="1" value="{{ old('max_entries', 10) }}">
            @error('max_entries')
                <span class="error">* {{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Афиша (jpg/webp, до 50kb)</label>
            <input type="file" name="image" accept=".jpg,.jpeg,.webp">
            @error('image')
                <span class="error"> {{ $message }}</span>
            @enderror
        </div>

        <input type="submit" class="btn btn-primary" value="Создать">
    </form>
</section>

@endsection