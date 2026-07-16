@extends('template.app')
@section('page')

<div class="detail-card">
    <div class="detail-img">
        <img src="{{ $event->image_url }}" alt="" height="100%" width="100%">
    </div>
    <div class="detail-content">
        <h2>{{ $event->title}}</h2>
        <p><strong>Описание:</strong> {{ $event->description}}</p>
        @if(!empty($event->tags_labels))
            <p><strong>Теги:</strong> {{ implode(', ', $event->tags_labels) }}</p>
        @endif
        <p><strong>Дата:</strong> {{ $event->date}}</p>
        <p><strong>Место:</strong> {{ $event->place}}</p>
        <p><strong>Минимум для проведения:</strong> {{ $event->min_entries ?? 0 }}</p>
        <p><strong>Записались:</strong> {{ $event->registered_count }}/{{ $event->max_entries }}</p>

        @guest
            <p>Чтобы записаться на мероприятие, пожалуйста, <a href="{{ route('show.login') }}">войдите</a></p>
        @endguest

        @auth
        <div class="flex">
            @if(auth()->user()->hasRegistered($event->id))
                <form action="{{ route('favorites.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Отменить запись?');">
                    @csrf
                    <input class="btn btn-delete" type="submit" value="Отменить запись">
                </form>
            @elseif($event->registered_count < $event->max_entries)
                <form action="{{ route('favorites.store', $event->id) }}" method="POST" onsubmit="return confirm('Записаться на мероприятие?');">
                    @csrf
                    <input class="btn btn-primary" type="submit" value="Записаться">
                </form>
            @else
                <button class="btn btn-delete" disabled>Мест нет</button>
            @endif

            @if(auth()->user()->canManageEvent($event))
                <a href="{{ route('event.edit', $event->id) }}" class="btn btn-edit">Редактировать</a>
                <a href="{{ route('event.delete', $event->id) }}" class="btn btn-delete">Удалить</a>
            @endif
        </div>
        @endauth

        <div class="form-group">
            <h3>Список записавшихся</h3>
            @if($event->registrations->isNotEmpty())
                <ul>
                    @foreach($event->registrations as $registration)
                        <li>
                            <a href="{{ route('user.profile', $registration->user->id) }}">
                                {{ $registration->user->username ?? $registration->user->email }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>Пока никто не записался.</p>
            @endif
        </div>

        <div class="form-group" style="margin-top: 24px;">
            <h3>Комментарии</h3>
            @auth
                <form method="POST" action="{{ route('comments.event.store', $event) }}" style="margin-top: 12px;">
                    @csrf
                    <textarea name="content" rows="3" placeholder="Напишите комментарий" required></textarea>
                    <button class="btn btn-primary" style="margin-top: 8px;">Отправить</button>
                </form>
            @endauth

            @if($event->comments->isNotEmpty())
                <div style="margin-top: 16px;">
                    @foreach($event->comments()->with('user')->latest()->get() as $comment)
                        <div style="padding: 12px 0; border-bottom: 1px solid #eee; display: flex; gap: 12px; align-items: flex-start;">
                            @php
                                $commentAvatarUrl = $comment->user && $comment->user->avatar_url ? $comment->user->avatar_url : null;
                            @endphp
                            @if($commentAvatarUrl)
                                <img src="{{ $commentAvatarUrl }}" alt="Аватар" style="width: 44px; height: 44px; object-fit: cover; border-radius: 50%; border: 1px solid #e8dcc8; flex-shrink: 0;">
                            @else
                                <div style="width: 44px; height: 44px; border-radius: 50%; background: #f5e8d4; display: flex; align-items: center; justify-content: center; color: #8a5a20; font-weight: 700; flex-shrink: 0;">
                                    {{ strtoupper(substr($comment->user->username ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: space-between; align-items: center; gap: 8px; flex-wrap: wrap;">
                                    <a href="{{ route('user.profile', $comment->user->id) }}" style="font-weight: 700; color: #6b4c1d; text-decoration: none;">
                                        {{ $comment->user->username ?? 'Пользователь' }}
                                    </a>
                                    <span style="font-size: 0.85rem; color: #8b7b63;">{{ $comment->created_at->setTimezone('Europe/Moscow')->translatedFormat('d.m.Y H:i') }}</span>
                                </div>
                                <p style="margin: 6px 0 0; white-space: pre-wrap;">{{ $comment->content }}</p>
                            </div>
                            @auth
                                @if(auth()->user()->canDeleteComment($comment) || auth()->id() === $comment->user_id)
                                    <div class="comment-menu-wrapper">
                                        <button type="button" onclick="var menu=this.parentNode.querySelector('.comment-menu'); menu.style.display=(menu.style.display==='block'?'none':'block');" style="border: none; background: transparent; cursor: pointer; font-size: 1.2rem; color: #8a5a20; padding: 2px 6px; display: inline-block; position: relative; z-index: 2;">⋯</button>
                                        <div class="comment-menu">
                                            @if(auth()->id() === $comment->user_id)
                                                <form method="POST" action="{{ route('comments.update', $comment) }}" style="margin: 0 0 6px;">
                                                    @csrf
                                                    @method('PUT')
                                                    <textarea name="content" rows="3" style="width: 100%; min-width: 180px; margin-bottom: 6px;">{{ $comment->content }}</textarea>
                                                    <button type="submit" class="btn btn-edit" style="width: 100%; padding: 6px 10px; font-size: 0.85rem;">Сохранить</button>
                                                </form>
                                            @endif
                                            @if(auth()->user()->canDeleteComment($comment))
                                                <form method="POST" action="{{ route('comments.destroy', $comment) }}" onsubmit="return confirm('Удалить комментарий?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-delete" style="width: 100%; padding: 6px 10px; font-size: 0.85rem;">Удалить</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    @endforeach
                </div>
            @else
                <p style="margin-top: 12px;">Комментариев пока нет.</p>
            @endif
        </div>
    </div>
</div>

@endsection