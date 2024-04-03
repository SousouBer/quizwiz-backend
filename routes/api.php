<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\CheckTokenExpiration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
	return $request->user();
});

Route::post('/register', [AuthController::class, 'store'])
				->middleware('guest')
				->name('register');

Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->name('login');
Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth:sanctum')->name('logout');

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
				->middleware([CheckTokenExpiration::class, 'signed', 'throttle:6,1'])
				->name('verification.verify');
