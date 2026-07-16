@extends('template.app')

@section('page')
    <!-- ========== 1. РЕГИСТРАЦИЯ ========== -->
    <section class="form-card">
        <h2>Регистрация</h2>
        <form method="POST" action="{{ route('send.reg') }}">
            @csrf

            <div class="form-group">
                <label>Логин</label>
                <input type="text" placeholder="" name="username" value="{{ old('username') }}">
                @error('username')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="text" placeholder="" name="email" value="{{ old('email') }}">
                
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
            <div class="form-group">
                <label>Повтор пароля</label>
                <input type="password" placeholder="" name="password_confirmation">
                
                @error('password_confirmation')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <input type="submit" class="btn btn-primary" value="Зарегистрироваться">
        </form>
    </section>
@endsection