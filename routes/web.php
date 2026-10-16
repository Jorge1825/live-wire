<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard;
use App\Livewire\Perfil;
use App\Livewire\Productos;
use App\Livewire\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Rutas para visitantes (si ya iniciaste sesión, te redirige al dashboard)
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// Rutas protegidas (si no iniciaste sesión, te redirige a /login)
Route::middleware('auth')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/productos', Productos::class)->name('productos');
    Route::get('/perfil', Perfil::class)->name('perfil');

    Route::get('/usuarios', Usuarios::class)
        ->middleware('can:admin')
        ->name('usuarios');

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');
});
