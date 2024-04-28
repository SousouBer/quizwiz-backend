<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
	use RefreshDatabase;

	public function test_auth_should_give_us_errors_if_no_inputs_are_provided(): void
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

	public function test_auth_should_give_us_errors_if_username_is_not_provided()
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

	public function test_auth_should_give_us_errors_if_email_is_not_provided()
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

	public function test_auth_should_give_us_errors_if_password_is_not_provided()
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

	public function test_auth_should_give_us_errors_if_password_confirmation_is_not_provided()
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
}
