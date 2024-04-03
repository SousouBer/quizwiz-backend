<?php

use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use Illuminate\Support\Facades\Route;

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
				->middleware('guest')
				->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
				->middleware('guest')
				->name('password.store');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
				->middleware(['auth', 'throttle:6,1'])
				->name('verification.send');
