@extends('template.app')
@section('page')

<div class="detail-card">
    <div class="detail-img">
        <img src="{{ $event->image_url }}" alt="" height="100%" width="100%">
    </div>
    <div class="detail-content">
        <h2>{{ $event->title}}</h2>
        <p><strong>Дата:</strong> {{ $event->date}}</p>
        <p><strong>Место:</strong> {{ $event->place}}</p>
        <p><strong>Описание:</strong> {{ $event->description}}</p>
        <div class="flex">

            @auth
            @if(auth()->user()->hasFavorited($event->id))
            <form action="{{route('favorites.destroy', $event->id)}}" method="POST">
                @csrf
                <input class="btn btn-primary" type="submit" value="Удалить">
            </form>
            @else
            <form action="{{route('favorites.store', $event->id)}}" method="POST">
                @csrf
                <input class="btn btn-primary" type="submit" value="В избранное">
            </form>

            @endif
            
            
            
            <a href="{{route('event.edit', $event->id)}}" class="btn btn-edit">Редактировать</a>
            <a href="{{route('event.delete', $event->id)}}" class="btn btn-delete">Удалить</a>
            @endauth
            
        </div>
    </div>
</div>

@endsection