<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactResource;
use App\Models\Contact;

class ContactController
{
	public function index(): ContactResource
	{
		return ContactResource::make(Contact::first());
	}
}
