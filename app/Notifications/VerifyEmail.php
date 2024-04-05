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
		// $this->verificationUrl = $verificationUrl;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @return array<int, string>
	 */
	public function via(object $notifiable): array
	{
		return ['mail'];
	}

	/**
	 * Get the mail representation of the notification.
	 */
	public function toMail(object $notifiable): MailMessage
	{
		return (new MailMessage)->view('verify-email', ['verificationUrl' => $this->verificationUrl, 'username' => $notifiable->username, 'email' => $notifiable->email]);
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(object $notifiable): array
	{
		return [
		];
	}
}
