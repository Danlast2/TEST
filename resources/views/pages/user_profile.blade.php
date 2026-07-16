@extends('template.app')
@section('page')

<section class="profile-card">
    <h2>Профиль: {{ $user->username }}</h2>
    <p class="profile-email">{{ $user->email }}</p>

    <hr>
    <h3>Записи пользователя</h3>

    @if($events->isEmpty())
        <p>Пользователь ещё не записался на мероприятия.</p>
    @else
        <div class="favorite-list">
            @foreach($events as $event)
                <div class="fav-item">
                    <a href="{{ route('event.show', $event->id) }}">{{ $event->title }}</a>
                </div>
            @endforeach
        </div>
    @endif
</section>

@endsection
