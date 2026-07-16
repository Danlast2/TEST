@extends('template.app')

@section('page')

    <!-- ========== 3. СПИСОК МЕРОПРИЯТИЙ ========== -->
    <h2 class="section-title">Лента меорприятий</h2>
    <div class="object-grid">


        @foreach ($events as $event)
            
        
        <div class="card">
            <div class="card-img">
                <img src="{{ $event->image_url }}" alt="" height="100%" width="100%">
            </div>
            <div class="card-content">
                <h3 class="card-title">{{ $event->title}}</h3>
                <div class="card-meta">{{ $event->date}}  {{ $event->place}}</div>
                <a href="{{ route('event.show', $event->id)}}" class="btn btn-outline">
                    Подробнее
                </a>
            </div>
        </div>
        
        @endforeach
    </div>

@endsection