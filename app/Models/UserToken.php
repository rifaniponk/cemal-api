<?php

namespace Cemal\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserToken.
 */
class UserToken extends Model
{
    protected $table = 'user_tokens';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'api_token',
        'expired_at',
    ];

    protected $guarded = [];

    protected $casts = [
        'user_id' => 'uuid',
    ];
    protected $dates = [
        'expired_at',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo('Cemal\Models\User');
    }

    public function increaseExpired($minute){
        if (!$this->expired_at){
            $this->expired_at = new \DateTime;
        }
        $dv = new DateInterval('PT'.$minute.'M');
        $this->expired_at->add($dv);
    }

    public function isExpired()
    {
        if (!$this->expired_at) return false;
        $now = new \DateTime;
        return $now >= $this->expired_at;
    }
}
