@extends('template.app')

@section('page')
<section class="form-card">
    <h2>Клубы</h2>

    <form method="GET" class="form-group">
        <label>Поиск клуба</label>
        <input type="text" name="q" value="{{ $query ?? '' }}" placeholder="Введите название клуба">
        <input type="submit" class="btn btn-outline" value="Найти">
    </form>

    <div class="object-grid">
        @if($clubs->isEmpty())
            <p>Клубов пока нет.</p>
        @else
            @foreach($clubs as $club)
                <div class="card">
                    <div class="card-content">
                        <h3 class="card-title">{{ $club->username }}</h3>
                        <p>{{ $club->email }}</p>
                        <a href="{{ route('club.profile', $club->id) }}" class="btn btn-outline">Подробнее</a>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</section>
@endsection
