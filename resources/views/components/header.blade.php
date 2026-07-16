<!-- ========== ХЕДЕР ========== -->
<header class="header" id="site-header">
    <div class="header-inner">
        <a href="/" class="logo">Книжный</a>
        <div class="nav">
            <a href="/">Главная</a>
            <a href="{{ route('event.index') }}">Мероприятия</a>
            <a href="{{ route('club.index') }}">Клубы</a>
            <a href="{{ route('articles') }}">Статьи</a>
            <a href="{{ route('exchange.index') }}">Обмен</a>

            @guest
                <a href="{{ route('show.reg') }}">Регистрация</a>
                <a href="{{ route('show.login') }}">Вход</a>
            @endguest

            @auth
                @if(auth()->user()->role === 'club')
                    <a href="{{ route('club.profile', auth()->user()->id) }}">Профиль</a>
                @else
                    <a href="{{ route('profile') }}">Профиль</a>
                @endif


                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'club' || auth()->user()->role === 'club_moderator')
                    <a href="{{ route('event.create') }}">Добавить мероприятие</a>
                @endif

                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <input type="submit" value="Выйти">
                </form>
            @endauth
        </div>
    </div>
</header>

<script>
    const header = document.getElementById('site-header');
    let lastScrollTop = 0;

    window.addEventListener('scroll', () => {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > lastScrollTop && scrollTop > 80) {
            header.classList.add('is-hidden');
        } else {
            header.classList.remove('is-hidden');
        }

        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    });
</script>