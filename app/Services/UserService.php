<?php

namespace Cemal\Services;

use Cemal\Models\User;
use Cemal\Exceptions\FormException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserService
{
    /**
     * create user.
     * @param  array  $data
     * @return User
     */
    public function create(array $data)
    {
        $validator = $this->validate($data);
        if ($validator->fails()) {
            throw new FormException($validator);
        }

        $data['password'] = Hash::make($data['password']);
        $data['register_date'] = new \DateTime;

        try {
            \DB::beginTransaction();

            $user = User::create($data);
            if (isset($data['verification_code']) && $data['verification_code']) {
                Mail::to($user->email)->send(
                    new \Cemal\Mails\Auth\RegisterConfirmation($user)
                );
            }

            \DB::commit();

            return $user;
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    /**
     * find user by id.
     * @param  string $id uuid
     * @return User
     */
    public function find($id)
    {
        return User::find($id);
    }

    /**
     * validate data.
     * @param  array  $data
     * @param  array  $group
     * @param  array  $param
     * @return Validator
     */
    public function validate(array $data, array $group = [], array $param = [])
    {
        $rules = User::getValidationRules($group, $param);

        $validator = \Validator::make($data, $rules);

        return $validator;
    }
}
