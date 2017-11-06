<?php

namespace Cemal\Services;

use Cemal\Models\User;
use Cemal\Models\UserToken;
use Cemal\Models\PasswordReset;
use Cemal\Exceptions\FormException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Cemal\Exceptions\NotFoundException;
use Cemal\Exceptions\NotValidException;

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

    /**
     * login.
     * @param  array  $data
     * @param  array  $aditionalData
     * @return UserToken
     */
    public function login(array $data, array $aditionalData = array())
    {
        $validator = \Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            throw new FormException($validator);
        }

        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            throw new NotFoundException('User');
        }

        if (! Hash::check($data['password'], $user->password)) {
            throw new NotValidException('email atau password salah');
        }

        $token = UserToken::create([
            'user_id' => $user->id,
            'api_token' => str_random(60),
            'expired_at' => null,
            'ip' => isset($aditionalData['ip']) ? $aditionalData['ip'] : null,
            'browser' => isset($aditionalData['browser']) ? substr($aditionalData['browser'], 0, 50) : null,
        ]);

        return $token;
    }

    /**
     * logout.
     */
    public function logout()
    {
        $user = \Auth::user();
        UserToken::where('user_id', $user->id)->delete();
    }

    /**
     * get logged in user.
     * @param  string $token
     * @return User
     */
    public function getLoggedInUser($token)
    {
        if (! $token) {
            return;
        }

        $ut = UserToken::where('api_token', $token)->first();
        if (! $ut) {
            return;
        }

        $now = new \DateTime;
        if (! $ut->expired_at) {
            return $ut->user;
        } elseif ($ut->expired_at <= $now) {
            return $ut->user;
        }
    }
}
