@extends('template.app')

@section('page')
<section class="form-card">
    <h2>Редактировать профиль клуба</h2>

    <form method="POST" action="{{ route('club.update', $club->id) }}" class="form-group">
        @csrf

        <label>Название клуба</label>
        <input type="text" name="username" value="{{ old('username', $club->username) }}" required>
        @error('username')
            <span class="error">* {{ $message }}</span>
        @enderror

        <label>Email клуба</label>
        <input type="email" name="email" value="{{ old('email', $club->email) }}" required>
        @error('email')
            <span class="error">* {{ $message }}</span>
        @enderror

        <input type="submit" class="btn btn-primary" value="Сохранить">
    </form>
</section>
@endsection
