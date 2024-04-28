<?php

namespace Tests\Feature;

use App\Actions\EmailVerificationUrl;
use App\Models\User;
use App\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegisterTest extends TestCase
{
	use RefreshDatabase;

	private User $user;

	protected function setUp(): void
	{
		parent::setUp();

		$this->user = User::factory()->create([
			'email_verified_at' => null,
		]);
	}

	public function test_register_should_give_us_errors_if_no_inputs_are_provided(): void
	{
		$response = $this->post(route('register'));

		$response->assertSessionHasErrors(
			[
				'username',
				'email',
				'password',
			]
		);
	}

	public function test_register_should_give_us_errors_if_username_is_not_provided()
	{
		$response = $this->post(route('register'), [
			'email'                              => 'test@mail.com',
			'password'                           => 'password',
			'password_confirmation'              => 'password',
		]);

		$response->assertSessionHasErrors(
			[
				'username',
			]
		);

		$response->assertSessionDoesntHaveErrors(
			[
				'email',
				'password',
				'password_confirmation',
			]
		);
	}

	public function test_register_should_give_us_errors_if_email_is_not_provided()
	{
		$response = $this->post(route('register'), [
			'username'                              => 'username',
			'password'                              => 'password',
			'password_confirmation'                 => 'password',
		]);

		$response->assertSessionHasErrors(
			[
				'email',
			]
		);

		$response->assertSessionDoesntHaveErrors(
			[
				'username',
				'password',
				'password_confirmation',
			]
		);
	}

	public function test_register_should_give_us_errors_if_password_is_not_provided()
	{
		$response = $this->post(route('register'), [
			'username'                              => 'username',
			'email'                                 => 'test@mail.com',
			'password_confirmation'                 => 'password',
		]);

		$response->assertSessionHasErrors(
			[
				'password',
			]
		);

		$response->assertSessionDoesntHaveErrors(
			[
				'email',
				'username',
				'password_confirmation',
			]
		);
	}

	public function test_register_should_give_us_errors_if_password_confirmation_is_not_provided()
	{
		$response = $this->post(route('register'), [
			'username'                              => 'username',
			'email'                                 => 'test@mail.com',
			'password'                              => 'password',
		]);

		$response->assertSessionHasErrors(
			[
				'password',
			]
		);

		$response->assertSessionDoesntHaveErrors(
			[
				'email',
				'username',
			]
		);
	}

	public function test_register_user_has_been_successfully_registered_and_stored_in_database()
	{
		$newUser = User::factory()->make();

		$response = $this->post(route('register'), [
			'username'                           => $newUser->username,
			'email'                              => $newUser->email,
			'password'                           => 'password',
			'password_confirmation'              => 'password',
		]);

		$response->assertSuccessful();

		$registeredUser = User::Firstwhere('email', $newUser->email);

		$this->assertDatabaseHas('users', ['username' => $registeredUser->username]);
	}

	public function test_user_email_verification_link_has_been_successfully_sent()
	{
		Notification::fake();

		$newUser = User::factory()->create(['email_verified_at' => null]);

		$response = $this->post(route('email.resend_verification', ['email' => $newUser->email]));

		Notification::assertSentTo($newUser, VerifyEmail::class);

		$response->assertSuccessful();
	}

	public function test_user_email_has_been_successfully_verified()
	{
		$newUser = User::factory()->create(['email_verified_at' => null]);

		$emailVerificationUrl = new EmailVerificationUrl();
		$verificationUrl = $emailVerificationUrl->handle($newUser);

		$this->actingAs($newUser)->get($verificationUrl);

		$this->assertTrue(User::find($newUser->id)->hasVerifiedEmail());
	}
}
