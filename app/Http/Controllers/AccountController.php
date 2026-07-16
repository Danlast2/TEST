<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;    // Для хеширования пароля
use App\Models\User;                    // Для работы с моделью User
use Illuminate\Support\Facades\Auth;


class AccountController extends Controller
{
    public function showReg(){
        return view('pages.reg');
    }

    public function sendReg(Request $request){
        $data = $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
        ]);

        // запись в БД
        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect('/')->with('success','Успешная регистрация');
    }

    // ========== АВТОРИЗАЦИЯ ==========
    public function showLogin(){
        return view('pages.login');
    }

    public function sendLogin(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/')->with('success', 'Добро пожаловать!');
        }

        return back()->withErrors([
            'email' => 'Пользователь не найден или неверный пароль',
        ])->onlyInput('email');
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Вы вышли из системы');
    }

    public function profile() {
        $events = Auth::user()->favoriteEvents;
        return view('pages.profile', compact('events'));
    }

}