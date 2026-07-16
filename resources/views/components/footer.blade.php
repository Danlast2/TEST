<footer class="footer">
    <div class="footer-container">
        <div class="footer-column">
            <h3 class="footer-title">Ваш аккаунт</h3>
            <ul class="footer-links">
                @guest
                    <li><a href="{{ route('show.login') }}">Войти</a></li>
                    <li><a href="{{ route('show.reg') }}">Регистрация</a></li>
                @endguest

                @auth
                    <li><a href="{{ route('profile') }}">Профиль</a></li>
                @endauth
            </ul>
        </div>
        
        <div class="footer-column">
            <h3 class="footer-title">Разделы</h3>
            <ul class="footer-links">
                <li><a href="{{ route('articles') }}">Статьи</a></li>
                <li><a href="{{ route('event.index') }}">Мероприятия</a></li>
                <li><a href="{{ route('club.index') }}">Клубы</a></li>
            </ul>
        </div>
        
        <div class="footer-column">
            <h3 class="footer-title">Устройство сайта</h3>
            <ul class="footer-links">
                <li><a href="{{ route('author.faq') }}">Для авторов</a></li>
                <li><a href="{{ route('club.faq') }}">Для клубов</a></li>
            </ul>
        </div>
        
        <div class="footer-column">
            <h3 class="footer-title">Услуги</h3>
            <ul class="footer-links">
                <li><a href="{{ route('exchange.index') }}">Обмен книг</a></li>
            </ul>
        </div>
    </div>
    
    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> Книжный клуб. Все права защищены.</p>
    </div>
</footer>