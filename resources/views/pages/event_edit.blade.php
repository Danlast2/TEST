@extends('template.app')
@section('page')

<!-- ========== ИЗМЕНЕНИ МЕРОПРИЯТИЯ ========== -->
<section class="form-card">
    <h2>✏️ Изменить мероприятие</h2>
    <form method="POST" action="{{route('event.update', $event->id)}}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Название</label>
            <input type="text" placeholder="Новое мероприятие" name="title" value="{{ $event->title }}">
            @error('title')
                <span class="error">* {{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Дата и время</label>
            <input type="date" name="date" value="{{ $event->date }}">
            @error('datetime')
                <span class="error">* {{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Место</label>
            <input type="text" placeholder="Казань, IT-парк" name="place" value="{{ $event->place }}">
            @error('place')
                <span class="error">* {{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Описание</label>
            <textarea rows="3" name="description">{{ $event->description }}</textarea>
            @error('description')
                <span class="error">* {{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Афиша (jpg/webp, до 50kb)</label>
            <input type="file" name="image" accept=".jpg,.jpeg,.webp">
            @error('image')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <input type="submit" class="btn btn-primary" value="Сохранить">
    </form>
</section>

@endsection