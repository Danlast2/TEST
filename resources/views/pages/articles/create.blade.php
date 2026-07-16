@extends('template.app')

@section('page')
<section class="form-card">
    <h2>Новая статья</h2>
    <form method="POST" action="{{ route('articles.store') }}">
        @csrf
        <div class="form-group">
            <label for="title">Заголовок</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required>
            @error('title') <span class="error">* {{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="description">Краткое описание</label>
            <textarea name="description" id="description" rows="3">{{ old('description') }}</textarea>
            @error('description') <span class="error">* {{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="content">Текст статьи</label>
            <textarea name="content" id="content" rows="8" required>{{ old('content') }}</textarea>
            @error('content') <span class="error">* {{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Теги статьи</label>
            @php $selectedTags = old('tags', []); @endphp
            <div class="tag-select-wrap">
                <select name="tags[]" class="tag-select" multiple size="6">
                    @foreach($availableTags as $value => $label)
                        <option value="{{ $value }}" {{ in_array($value, $selectedTags, true) ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <small style="color: #64748b; display: block; margin-top: 6px;">Можно выбрать несколько тегов.</small>
            </div>
            @error('tags') <span class="error">* {{ $message }}</span> @enderror
        </div>

        <button class="btn btn-primary">Опубликовать</button>
    </form>
</section>
<style>
    .tag-select-wrap {
        margin-top: 6px;
    }
    .tag-select {
        width: 100%;
        min-height: 140px;
        padding: 10px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        background: #fff;
        color: #0f172a;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
    }
    .tag-select option {
        padding: 8px 10px;
        border-radius: 8px;
        margin: 2px 0;
    }
    .tag-select option:checked {
        background: linear-gradient(90deg, #3b82f6, #8b5cf6);
        color: #fff;
    }
</style>
@endsection
