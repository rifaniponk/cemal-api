<?php

namespace Cemal\Services;

use Cemal\Models\User;
use Cemal\Models\PasswordReset;
use Cemal\Exceptions\FormException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Cemal\Exceptions\NotFoundException;

class AuthService
{
    /**
     * verify registration confirmation.
     * @param  string $verification_code
     * @return User
     */
    public function verifyRegistration($verification_code)
    {
        $user = User::where('verification_code', $verification_code)->first();

        if (! $user) {
            throw new NotFoundException('verification_code');
        }
        $user->verification_code = null;
        $user->verified = true;
        $user->activation_date = new \DateTime;
        $user->save();

        return $user;
    }

    /**
     * request reset password.
     * @param  array  $data
     * @return PasswordReset
     */
    public function requestReset(array $data)
    {
        $validator = \Validator::make($data, [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            throw new FormException($validator);
        }

        if (User::where('email', $data['email'])->count() === 0) {
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

    /**
     * Reset password.
     * @param  array  $data
     * @return User
     */
    public function resetPassword(array $data)
    {
        $validator = \Validator::make($data, [
            'email' => 'required|email|max:255',
            'password' => 'required|min:6|confirmed',
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            throw new FormException($validator);
        }

        if (PasswordReset::where('email', $data['email'])
                                    ->where('token', $data['token'])
                                    ->count() === 0) {
            throw new NotFoundException('Token');
        }

        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            throw new NotFoundException('User');
        }
        // clear all tokens
        PasswordReset::where('email', $data['email'])->delete();

        $user->forceFill([
            'password' => Hash::make($data['password']),
        ])->save();
    }
}
