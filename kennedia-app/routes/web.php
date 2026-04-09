<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['App\Http\Middleware\CheckAuth'])->group(function () {
    Route::get('/', function () {
        return view('convocation');
    })->name('dashboard');

    Route::get('/settings', [AuthController::class, 'settings'])->name('settings');
    Route::post('/settings/password', [AuthController::class, 'updatePassword'])->name('settings.password');

    Route::get('/booklet-log', function () {
        return view('booklet-log', [
            'userName' => (string) session('user_name', 'User'),
        ]);
    })->name('booklet.log');

    Route::get('/how-to-use', function () {
        return view('how-to-use', [
            'userName' => (string) session('user_name', 'User'),
        ]);
    })->name('how.to.use');
});
