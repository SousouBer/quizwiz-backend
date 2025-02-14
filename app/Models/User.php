<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Notifications\sendPasswordResetNotification;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
	use HasFactory;

	use Notifiable;

	protected $guarded = ['id'];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'email_verified_at' => 'datetime',
			'password'          => 'hashed',
		];
	}

	public function quizzes(): BelongsToMany
	{
		return $this->belongsToMany(Quiz::class)->withPivot('time_taken', 'score')->withTimestamps();
	}

	public function sendPasswordResetNotification($token): void
	{
		$url = config('app.frontend_url') . '/reset-password?email=' . $this->email . '&token=' . $token;

		$this->notify(new sendPasswordResetNotification($url));
	}
}
