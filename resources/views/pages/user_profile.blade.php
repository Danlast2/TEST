@extends('template.app')
@section('page')

<section class="profile-card">
    <h2>Профиль: {{ $user->username }}</h2>
    <p class="profile-email">{{ $user->email }}</p>

    <div style="margin: 20px 0;">
        @php
            $avatarUrl = $user->avatar_url;
        @endphp
        @if($avatarUrl)
            <img src="{{ $avatarUrl }}" alt="Аватар" style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%; border: 2px solid #e8dcc8;">
        @else
            <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #f7e6c5, #e8c38d); display: flex; align-items: center; justify-content: center; color: #8a5a20; font-size: 1.8rem; border: 2px solid #e8dcc8; box-shadow: inset 0 0 0 3px rgba(255,255,255,0.35);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 6px;">
                    <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" fill="#8a5a20"/>
                    <path d="M4 20C4 16.6863 7.13401 14 11 14H13C16.866 14 20 16.6863 20 20V21H4V20Z" fill="#8a5a20"/>
                </svg>
                <span style="font-weight: 700;">{{ strtoupper(substr($user->username, 0, 1)) }}</span>
            </div>
        @endif
    </div>

    @if(!empty($user->description))
        <div style="margin: 16px 0 8px; padding: 12px 14px; background: #fdf7eb; border: 1px solid #e8dcc8; border-radius: 8px;">
            <strong>Описание:</strong><br>
            {{ $user->description }}
        </div>
    @endif

    <hr>
    <h3>Комментарии</h3>
    @auth
        <form method="POST" action="{{ route('comments.profile.store', $user) }}" style="margin-top: 12px;">
            @csrf
            <textarea name="content" rows="3" placeholder="Оставьте комментарий" required></textarea>
            <button class="btn btn-primary" style="margin-top: 8px;">Отправить</button>
        </form>
    @endauth

    @if($comments->isNotEmpty())
        <div style="margin-top: 16px;">
            @foreach($comments as $comment)
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
                            <div style="position: relative;">
                                <button type="button" onclick="var menu=this.parentNode.querySelector('.comment-menu'); menu.style.display=(menu.style.display==='block'?'none':'block');" style="border: none; background: transparent; cursor: pointer; font-size: 1.2rem; color: #8a5a20; padding: 2px 6px; display: inline-block; position: relative; z-index: 2;">⋯</button>
                                <div class="comment-menu" style="display: none; position: absolute; right: 0; top: 28px; background: white; border: 1px solid #e8dcc8; border-radius: 8px; padding: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); z-index: 10; min-width: 200px;">
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

    <hr>
    <h3>Мероприятия пользователя</h3>

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

    <hr>
    <h3>Забронированные обмены</h3>
    <div class="favorite-list">
        @if($bookedExchanges->isEmpty())
            <p>Пользователь ещё не бронировал обмены.</p>
        @else
            @foreach($bookedExchanges as $exchange)
                <div class="fav-item">
                    <a href="{{ route('exchange.show', $exchange->id) }}">{{ $exchange->title }}</a>
                </div>
            @endforeach
        @endif
    </div>

    <hr>
    <h3>Клубы</h3>
    <div class="favorite-list">
        @if($joinedClubs->isEmpty())
            <p>Пользователь пока не состоит ни в одном клубе.</p>
        @else
            @foreach($joinedClubs as $club)
                <div class="fav-item">
                    <a href="{{ route('club.profile', $club->id) }}">{{ $club->username }}</a>
                </div>
            @endforeach
        @endif
    </div>

    <hr>
    <h3>Мероприятия клубов пользователя</h3>
    <div class="favorite-list">
        @if($clubEvents->isEmpty())
            <p>У клубов пользователя пока нет мероприятий.</p>
        @else
            @foreach($clubEvents as $event)
                <div class="fav-item">
                    <a href="{{ route('event.show', $event->id) }}">{{ $event->title }}</a>
                </div>
            @endforeach
        @endif
    </div>
</section>

@endsection
