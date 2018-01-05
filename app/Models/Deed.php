<?php

namespace Cemal\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $id
 * @property mixed $user_id
 * @property string $title
 * @property string $description
 * @property boolean $public
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Users $users
 */
class Deed extends Model
{
    public $incrementing = false;

    protected $fillable = ['user_id', 'title', 'description', 'public'];

    protected $dates = ['created_at', 'updated_at'];

    protected $casts = [
        'public' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsTo('Users', 'user_id');
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string) Uuid::generate(4);
        });
    }
}
