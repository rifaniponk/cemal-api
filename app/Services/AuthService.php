<?php

namespace Cemal\Services;

use Cemal\Models\User;
use Cemal\Models\PasswordReset;
use Cemal\Exceptions\FormException;
use Cemal\Exceptions\NotValidException;
use Cemal\Exceptions\NotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthService
{
	/**
     * verify registration confirmation
     * @param  string $verification_code 
     */
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

	/**
	 * request reset password
	 * @param  array  $data
	 * @return PasswordReset
	 */
	public function requestReset(array $data)
	{
		$validator = \Validator::make($data, [
            'email' => 'required|email|max:255',
		]);

		$validator->validate($data);
        if ($validator->fails()){
            throw new FormException($validator);
        }

        if (User::where('email', $data['email'])->count() === 0){
			throw new NotFoundException('user: '.$data['email']); 
        }

        $token = PasswordReset::create([
        	'email' => $data['email'],
        	'token' => str_random(60),
        ]);

        Mail::to($token->email)->send(
    		new \Cemal\Mails\Auth\ResetPassword($token->token)
    	);

    	return $token;
	}
}