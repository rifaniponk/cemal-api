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
    public function login(array $data, array $aditionalData = [])
    {
        $expireTime  = (int)config('app.auth_expire_time');
        $expiredTime = null;
        if ($expireTime){
            $expiredTime = new \DateTime;
            $expiredTime = $expiredTime->add(new \DateInterval('PT'.config('app.auth_expire_time').'M'));
        }

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

        // remove same user token
        $token = UserToken::where('user_id', $user->id);
        foreach ($aditionalData as $key => $value) {
            $token->where($key, $value);
        }
        $token->delete();

        $token = UserToken::create([
            'user_id' => $user->id,
            'api_token' => str_random(60),
            'expired_at' => $expiredTime,
            'ip' => isset($aditionalData['ip']) ? $aditionalData['ip'] : null,
            'browser' => isset($aditionalData['browser']) ? $aditionalData['browser'] : null,
        ]);

        return $token;
    }

    /**
     * logout.
     * @param  array  $data
     */
    public function logout(array $data = [])
    {
        $user = \Auth::user();
        $token = UserToken::where('user_id', $user->id);
        foreach ($data as $key => $value) {
            $token->where($key, $value);
        }
        $token->delete();
    }
}
