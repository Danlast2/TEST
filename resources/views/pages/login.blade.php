@extends('template.app')
@section('page')

<!-- ========== 2. АВТОРИЗАЦИЯ ========== -->
<section class="form-card">
    <h2>Вход</h2>
    <form method="POST" action="{{ route('send.login') }}">
        @csrf
        <div class="form-group">
            <label>Email</label>
            <input type="email" placeholder="" name="email" value="{{ old('email') }}">
            @error('email')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label>Пароль</label>
            <input type="password" placeholder="" name="password">
            @error('password')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
        <input type="submit" class="btn btn-primary" value="Войти">
    </form>
</section>

<section class="form-card">
    Ещё нет аккаунта? <a href="{{ route('show.reg') }}">Зарегестрируйтесь</a>
</section>


@endsection