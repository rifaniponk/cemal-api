<?php

namespace Cemal\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PasswordReset
 */
class PasswordReset extends Model
{
    protected $table = 'password_resets';

    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = [
        'email',
        'token'
    ];

    protected $fillable = [
        'email',
        'token',
        'created_at'
    ];

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->created_at = new \DateTime;
        });
    }
}