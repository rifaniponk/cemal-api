<?php

namespace Cemal\Services;

use Cemal\Models\User;
use Cemal\Exceptions\FormException;
use Cemal\Exceptions\NotValidException;
use Cemal\Exceptions\NotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthService
{
	public function verifyRegistration($verification_code)
	{
		$user = User::where('verification_code', $verification_code)->first();

		if (!$user) throw new NotFoundException('Verification Code');

		$user->verification_code = null;
		$user->verified = true;
		$user->activation_date = new \DateTime;
		$user->save();

		return $user;
	}
}