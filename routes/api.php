<?php

use App\Http\Controllers\VerifyEmailController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\CheckTokenExpiration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
	return $request->user();
});

Route::controller(AuthController::class)->middleware('guest')->group(function () {
	Route::post('/register', 'register')->name('register');
	Route::post('/login', 'login')->name('login');
	Route::post('/forgot-password', 'forgotPassword')->name('password.forgot');
	Route::post('/resend-email-verification/{email}', 'resendEmailVerification')->name('email.resendVerification');
	Route::post('/reset-password/{email}/{token}', 'resetPassword')->name('password.reset');
	Route::get('/reset-password/{email}/{token}/check-expiration', 'CheckExpration')->name('password.checkExpiration');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
->middleware([CheckTokenExpiration::class, 'signed', 'throttle:6,1'])
->name('verification.verify');
