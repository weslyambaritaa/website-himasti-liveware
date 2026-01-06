<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:req-limit'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('app.beranda');
    })->name('/');

    Route::group(['prefix' => 'sso'], function () {
        Route::get('/callback', [AuthController::class, 'ssoCallback'])->name('sso.callback');
    });

    Route::group(['prefix' => 'auth'], function () {
        Route::get('/register', [AuthController::class, 'register'])->name('auth.register');
        Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
        Route::get('/totp', [AuthController::class, 'totp'])->name('auth.totp');
        Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    });

    Route::group(['middleware' => 'check.auth'], function () {
        Route::group(['prefix' => 'app'], function () {
            Route::get('/profile', [AppController::class, 'profile'])->name('app.profile');
            Route::get('/beranda', [AppController::class, 'beranda'])->name('app.beranda');
            Route::get('/variabel', [AppController::class, 'variabel'])->name('app.variabel');
            Route::get('/hak-akses', [AppController::class, 'hakAkses'])->name('app.hak-akses');

            // Tim
            Route::get('/tim', [AppController::class, 'tim'])->name('app.tim');
        });
    });
});
