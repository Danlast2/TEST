@extends('template.app')

@section('page')
<div class="container" style="margin-bottom: 60px;">
    <div class="page-shell">
        <div class="page-header">
            <div>
                <h2 class="section-title" style="margin: 0 0 8px;">Статьи</h2>
                <p class="page-subtitle">Читайте полезные материалы, находите по тегам и открывайте новые темы.</p>
            </div>
            @auth
                <a href="{{ route('articles.create') }}" class="btn btn-primary">Написать статью</a>
            @endauth
        </div>

        <form method="GET" action="{{ route('articles.index') }}" class="filter-panel">
            <div class="filter-grid">
                <div class="filter-field">
                    <label>Поиск статьи</label>
                    <input type="text" name="q" value="{{ $query ?? '' }}" placeholder="Введите заголовок, описание или тег">
                </div>

                <div class="filter-field" style="grid-column: 1 / -1;">
                    <label>Теги</label>
                    <select name="tags[]" class="tag-select" multiple size="6">
                        @foreach($availableTags as $value => $label)
                            <option value="{{ $value }}" {{ in_array($value, $selectedTags ?? [], true) ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button class="btn btn-outline" style="margin-top: 12px;">Применить</button>
        </form>

        <div class="object-grid">
            @forelse($articles as $article)
                <div class="card">
                    <div class="card-content">
                        <h3 class="card-title">{{ $article->title }}</h3>
                        <p>{{ Str::limit($article->description ?: $article->content, 140) }}</p>
                        @if(!empty($article->tags))
                            <p><strong>Теги:</strong> {{ implode(', ', $article->tags) }}</p>
                        @endif
                        <p><strong>Автор:</strong> {{ $article->user->username ?? 'Пользователь' }}</p>
                        <a href="{{ route('articles.show', $article) }}" class="btn btn-outline">Читать</a>
                    </div>
                </div>
            @empty
                <p>Пока нет опубликованных статей.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
