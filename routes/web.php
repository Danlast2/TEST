<?php
use App\Http\Controllers\FavoriteController; 
use App\Http\Controllers\AccountController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

// ========== ПУБЛИЧНЫЕ МАРШРУТЫ ==========
Route::get('/', [EventController::class, 'index'])->name('home');
Route::get('/event-image/{path}', [EventController::class, 'image'])->where('path', '.*')->name('event.image');
Route::get('/events/{id}', [EventController::class, 'show'])->name('event.show');
Route::get('/users/{id}', [AccountController::class, 'showUserProfile'])->name('user.profile');

Route::get('/club-index', function () { return view('pages.club_index'); })->name('club.index');
Route::get('/club-profile', function () { return view('pages.club_profile'); })->name('club.profile');
Route::get('/club-faq', function () { return view('pages.club_faq'); })->name('club.faq');
Route::get('/event-index', function () { return view('pages.event_index'); })->name('event.index');
Route::get('/admin-panel', function () { return view('pages.admin_panel'); })->name('admin.panel');
Route::get('/moderator-panel', function () { return view('pages.moderator_panel'); })->name('moderator.panel');
Route::get('/articles', function () { return view('pages.articles'); })->name('articles');
Route::get('/author-faq', function () { return view('pages.author_faq'); })->name('author.faq');

Route::get('/books-exchange', function () { return view('pages.books.exchange_index'); })->name('exchange.index');
Route::get('/exchange-create', function () { return view('pages.books.exchange_create'); })->name('exchange.create');
Route::get('/exchange-edit/{id}', function ($id) { return view('pages.books.exchange_edit', compact('id')); })->name('exchange.edit');
Route::get('/exchange-show/{id}', function ($id) { return view('pages.books.exchange_show', compact('id')); })->name('exchange.show');
Route::get('/exchange-delete/{id}', function ($id) { return view('pages.books.exchange_delete', compact('id')); })->name('exchange.delete');

// ========== ГОСТЕВЫЕ МАРШРУТЫ (только для неавторизованных) ==========
Route::middleware('guest')->group(function () {
    // Регистрация
    Route::get('/reg', [AccountController::class, 'showReg'])->name('show.reg');
    Route::post('/reg', [AccountController::class, 'sendReg'])->name('send.reg');
    // Авторизация
    Route::get('/login', [AccountController::class, 'showLogin'])->name('show.login');
    Route::post('/send-login', [AccountController::class, 'sendLogin'])->name('send.login');
});

// ========== АВТОРИЗОВАННЫЕ МАРШРУТЫ ==========
Route::middleware('auth')->group(function () {
    // Выход
    Route::post('/logout', [AccountController::class, 'logout'])->name('logout');
    // Профиль
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');


    Route::post('/favorites/store/{id}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::post('/favorites/destroy/{id}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    
    // ========== АДМИНСКИЕ МАРШРУТЫ (только для админов) ==========
    Route::middleware('admin')->group(function () {
        // Создание
        Route::get('/add', [EventController::class, 'create'])->name('event.create');
        Route::post('/add', [EventController::class, 'store'])->name('event.store');
        
        // Редактирование (форма) - GET
        Route::get('/edit/{id}', [EventController::class, 'edit'])->name('event.edit');
        
        // Обновление данных - POST (ИСПРАВЛЕНО: было get, стало post)
        Route::post('/update/{id}', [EventController::class, 'update'])->name('event.update');
        
     

        Route::get('/delete/{id}', [EventController::class, 'delete'])->name('event.delete');
        Route::delete('/destroy/{id}', [EventController::class, 'destroy'])->name('event.destroy');
      
    });
});