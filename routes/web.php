<?php
use App\Http\Controllers\FavoriteController; 
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BookExchangeController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;



// ========== ПУБЛИЧНЫЕ МАРШРУТЫ ==========
Route::get('/', [EventController::class, 'index'])->name('home');
Route::get('/start', [EventController::class, 'start'])->name('start');

Route::get('/api/events/map-data', [EventController::class, 'mapData'])->name('events.map'); 


Route::get('/event-image/{path}', [EventController::class, 'image'])->where('path', '.*')->name('event.image');
Route::get('/events/{id}', [EventController::class, 'show'])->name('event.show');
Route::get('/users/{id}', [AccountController::class, 'showUserProfile'])->name('user.profile');
Route::get('/ban', function () { return view('pages.ban'); })->name('ban');

Route::get('/club-index', [ClubController::class, 'index'])->name('club.index');
Route::get('/club-profile/{id}', [ClubController::class, 'profile'])->name('club.profile');
Route::get('/club-edit/{id}', [ClubController::class, 'editProfile'])->name('club.edit');
Route::post('/club-edit/{id}', [ClubController::class, 'updateProfile'])->name('club.update');
Route::post('/club-join/{id}', [ClubController::class, 'join'])->name('club.join');
Route::post('/club-leave/{id}', [ClubController::class, 'leave'])->name('club.leave');
Route::post('/club-ban/{id}', [ClubController::class, 'banUser'])->name('club.ban');
Route::post('/club-assign-role/{id}', [ClubController::class, 'assignRole'])->name('club.assignRole');
Route::get('/club-faq', function () { return view('pages.club_faq'); })->name('club.faq');
Route::get('/event-index', [EventController::class, 'index'])->name('event.index');
Route::get('/admin-panel', function () { return view('pages.admin_panel'); })->name('admin.panel');
Route::get('/moderator-panel', function () { return view('pages.moderator_panel'); })->name('moderator.panel');
Route::get('/articles', function () { return view('pages.articles_index'); })->name('articles');
Route::get('/author-faq', function () { return view('pages.author_faq'); })->name('author.faq');

Route::get('/books-exchange', [BookExchangeController::class, 'index'])->name('exchange.index');
Route::get('/exchange-show/{exchange}', [BookExchangeController::class, 'show'])->name('exchange.show');

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

    Route::get('/exchange-create', [BookExchangeController::class, 'create'])->name('exchange.create');
    Route::post('/exchange-create', [BookExchangeController::class, 'store'])->name('exchange.store');
    Route::get('/exchange-edit/{exchange}', [BookExchangeController::class, 'edit'])->name('exchange.edit');
    Route::post('/exchange-update/{exchange}', [BookExchangeController::class, 'update'])->name('exchange.update');
    Route::get('/exchange-delete/{exchange}', [BookExchangeController::class, 'delete'])->name('exchange.delete');
    Route::delete('/exchange-destroy/{exchange}', [BookExchangeController::class, 'destroy'])->name('exchange.destroy');
    Route::post('/exchange-book/{exchange}', [BookExchangeController::class, 'book'])->name('exchange.book');
    
    // ========== АДМИНСКИЕ МАРШРУТЫ (только для админов) ==========
    Route::get('/add', [EventController::class, 'create'])->name('event.create');
    Route::post('/add', [EventController::class, 'store'])->name('event.store');

    Route::get('/edit/{id}', [EventController::class, 'edit'])->name('event.edit');
    Route::post('/update/{id}', [EventController::class, 'update'])->name('event.update');

    Route::get('/delete/{id}', [EventController::class, 'delete'])->name('event.delete');
    Route::delete('/destroy/{id}', [EventController::class, 'destroy'])->name('event.destroy');
});