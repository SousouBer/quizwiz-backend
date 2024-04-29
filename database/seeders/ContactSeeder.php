<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class ContactSeeder extends Seeder
{
	public function run(): void
	{
		$contacts = Config::get('contacts.contacts');

		Contact::create([
			'email'    => $contacts['email'],
			'tel'      => $contacts['tel'],
			'facebook' => $contacts['facebook'],
			'linkedin' => $contacts['linkedin'],
		]);
	}
}
