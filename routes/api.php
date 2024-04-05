<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\CheckTokenExpiration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
	return $request->user();
});

Route::post('/register', [AuthController::class, 'register'])
				->middleware('guest')
				->name('register');

Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->name('login');
Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth:sanctum')->name('logout');

Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

Route::post('/resend-email-verification/{email}', [AuthController::class, 'resendEmailVerification'])
				->middleware('guest')
				->name('resendEmailVerification');

Route::post('/reset-password/{email}/{token}', [AuthController::class, 'resetPassword'])->name('password.reset')->middleware('guest');
Route::get('/reset-password/{email}/{token}/check-expiration', [AuthController::class, 'CheckExpration'])->name('password.reset-expiration')->middleware('guest');

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
->middleware([CheckTokenExpiration::class, 'signed', 'throttle:6,1'])
->name('verification.verify');
