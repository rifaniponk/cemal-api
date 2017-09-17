<?php

namespace Cemal\Models;

use Webpatser\Uuid\Uuid;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $table = 'users';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'register_date',
        'last_login_date',
        'status',
        'phone',
        'address',
        'biography',
        'register_ip',
        'activation_date',
        'verified',
        'verification_code',
    ];
    protected $hidden = [
        'password', 'remember_token', 'verification_code',
    ];
    protected $casts = [
        'id' => 'uuid',
        'role' => 'integer',
        'status' => 'integer',
        'verified' => 'boolean',
    ];
    protected $dates = [
        'register_date',
        'last_login_date',
        'activation_date',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string) Uuid::generate(4);
        });
    }

    public static function getValidationRules(array $group = [], array $param = [])
    {
        $rules = [
            'name' => 'required|max:255|min:4',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ];

        if (in_array('update', $group)) {
            $rules['email'] .= ',email,'.$param['id'];
            $rules['password'] = 'min:6|confirmed';
        }

        return $rules;
    }
}
