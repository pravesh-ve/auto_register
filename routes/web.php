<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/code/verify', function () {
    return view('auth.code_verify');
})->middleware(['auth', 'verified'])->name('auth.code_verify');

Route::post('/verify/recaptcha', [RegisteredUserController::class,'verifyCode'])->middleware(['auth', 'verified'])->name('verifyCode');

Route::post('/verify/captcha', [RegisteredUserController::class,'verifyCaptcha'])->middleware(['auth', 'verified'])->name('verifyCaptcha');




Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware(['auth', 'throttle:6,1']);

Route::middleware('auth','verified')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
