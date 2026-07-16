@extends('template.app')

@section('page')
<section class="form-card">
    <div class="detail-card">
        <div class="detail-content">
            <h2>{{ $article->title }}</h2>
            <p><strong>Описание:</strong> {{ $article->description }}</p>
            <p><strong>Автор:</strong> {{ $article->user->username ?? 'Пользователь' }}</p>
            @if(!empty($article->tags))
                <p><strong>Теги:</strong> {{ implode(', ', $article->tags) }}</p>
            @endif
            <div style="margin-top: 20px; white-space: pre-wrap;">{{ $article->content }}</div>

            @auth
                @if($article->canBeManagedBy(auth()->user()))
                    <div class="flex" style="margin-top: 20px;">
                        <a href="{{ route('articles.edit', $article) }}" class="btn btn-edit">Редактировать</a>
                        <a href="{{ route('articles.delete', $article) }}" class="btn btn-delete">Удалить</a>
                    </div>
                @endif
            @endauth
        </div>
    </div>

    <div class="form-group" style="margin-top: 24px;">
        <h3>Комментарии</h3>
        @auth
            <form method="POST" action="{{ route('articles.comment.store', $article) }}" style="margin-top: 12px;">
                @csrf
                <textarea name="content" rows="3" placeholder="Напишите комментарий" required></textarea>
                <button class="btn btn-primary" style="margin-top: 8px;">Отправить</button>
            </form>
        @endauth

        @if($article->comments->isNotEmpty())
            <div style="margin-top: 16px;">
                @foreach($article->comments()->with('user')->latest()->get() as $comment)
                    <div style="padding: 12px 0; border-bottom: 1px solid #eee; display: flex; gap: 12px; align-items: flex-start;">
                        <div style="flex: 1;">
                            <div style="display: flex; justify-content: space-between; align-items: center; gap: 8px; flex-wrap: wrap;">
                                <div style="font-weight: 700; color: #6b4c1d;">{{ $comment->user->username ?? 'Пользователь' }}</div>
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
</section>
@endsection
