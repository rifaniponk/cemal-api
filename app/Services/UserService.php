<?php

namespace Cemal\Services;

use Cemal\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
	/**
	 * create user
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data){
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
}