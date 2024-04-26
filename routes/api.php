<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\EmailController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DifficultyLevelController;
use App\Http\Controllers\QuizController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
	return $request->user();
});

Route::middleware('guest')->group(function () {
	Route::post('/register', [AuthController::class, 'register'])->name('register');
	Route::post('/login', [AuthController::class, 'login'])->name('login')->middleware('verified');
	Route::post('/forgot-password', [PasswordController::class, 'forgotPassword'])->name('password.forgot');
	Route::post('/resend-email-verification/{email}', [EmailController::class, 'resendEmailVerification'])->name('email.resend_verification');
	Route::post('/reset-password/{email}/{token}', [PasswordController::class, 'resetPassword'])->name('password.reset');
	Route::get('/reset-password/{email}/{token}/check-expiration', [EmailController::class, 'checkExpiration'])->name('password.check_expiration');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');

Route::get('/verify-email/{id}/{hash}', [EmailController::class, 'verifyEmail'])
->middleware(['valid', 'signed', 'throttle:6,1'])
->name('verification.verify');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
Route::get('/difficulty-levels', [DifficultyLevelController::class, 'index'])->name('difficultyLevels.index');

Route::get('/quiz/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
Route::post('/answers', [QuizController::class, 'store'])->name('answers.store');
Route::get('/similar-quizzes/{quiz}', [QuizController::class, 'similarQuizzes'])->name('quizzes.similar_quizzes');
