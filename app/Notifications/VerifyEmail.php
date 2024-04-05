<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmail extends Notification
{
	use Queueable;

	protected $verificationUrl;

	public function __construct($verificationUrl)
	{
		$this->verificationUrl = explode('/verify-email/', $verificationUrl)[1];
	}

	public function via(object $notifiable): array
	{
		return ['mail'];
	}

	public function toMail(object $notifiable): MailMessage
	{
		return (new MailMessage)->view('verify-email', ['verificationUrl' => $this->verificationUrl, 'username' => $notifiable->username, 'email' => $notifiable->email]);
	}

	public function toArray(object $notifiable): array
	{
		return [
		];
	}
}
