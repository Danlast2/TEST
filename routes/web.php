<?php
use App\Http\Controllers\FavoriteController; 
use App\Http\Controllers\AccountController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

// ========== ПУБЛИЧНЫЕ МАРШРУТЫ ==========
Route::get('/', [EventController::class, 'index'])->name('home');
Route::get('/event-image/{path}', [EventController::class, 'image'])->where('path', '.*')->name('event.image');
Route::get('/events/{id}', [EventController::class, 'show'])->name('event.show');


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