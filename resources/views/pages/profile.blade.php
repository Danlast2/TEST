@extends('template.app')
@section('page')

<section class="profile-card">
    <h2>Профиль: {{auth()->user()->username}}</h2>
    <p class="profile-email">{{auth()->user()->email}}</p>
    <hr>
    <h3>Избранные мероприятия</h3>
    <div class="favorite-list">
        @foreach($events as $event)

        <div class="fav-item">
            <a href="{{route('event.show', $event->id)}}">
                {{$event->title}}
            </a>
            <form action="{{route('favorites.destroy', $event->id)}}" method="POST">
                @csrf
                <input class="btn btn-primary" type="submit" value="❤️ Удалить">
            </form>
        </div>

        @endforeach
       
    </div>
</section>


@endsection