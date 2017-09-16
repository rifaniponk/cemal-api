<?php

namespace Cemal\Services;

use Cemal\Models\User;
use Cemal\Exceptions\FormException;
use Illuminate\Support\Facades\Hash;

class UserService
{
	/**
	 * create user
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data){
		$validator = $this->validate($data);
        if ($validator->fails()){
            throw new FormException($validator);
        }

		$data['password'] = Hash::make($data['password']);
		$user = User::create($data);
		return $user;
	}

	/**
	 * find user by id
	 * @param  string $id uuid
	 * @return User     
	 */
	public function find($id){
		return User::find($id);
	}

	/**
	 * get validation rules
	 * @param  array  $data 
	 * @param  array  $group
	 * @param  array  $param
	 * @return Validator       
	 */
	public function validate(array $data, array $group=[], array $param=[]){
        $rules = User::getValidationRules($group, $param);

        $validator = \Validator::make($data, $rules);

        return $validator;
    }
}