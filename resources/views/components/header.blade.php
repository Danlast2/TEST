<!-- ========== ХЕДЕР ========== -->
<header class="header">
    <a href="/" class="logo">Книжный</a>
    <div class="nav">
        <a href="/">Главная</a>

        @guest
            <a href="{{ route('show.reg') }}">Регистрация</a>
            <a href="{{ route('show.login') }}">Вход</a>
        @endguest

        @auth
            <a href="{{route('profile')}}">Профиль</a>

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('event.create') }}">Добавить</a>
            @endif

            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <input type="submit" value="Выйти">
            </form>
        @endauth
    </div>
</header>