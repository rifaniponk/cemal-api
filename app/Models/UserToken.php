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
        'ip',
        'browser',
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

    public function increaseExpired($minute = null, $save = true)
    {
        if (! $this->expired_at) {
            $this->expired_at = new \DateTime;
        }
        if (! $minute) {
            $minute = config('app.auth_expire_time');
        }
        $this->expired_at = \Carbon\Carbon::now()->addMinutes((int) $minute);
        if ($save) {
            $this->save();
        }
    }

    public function isExpired()
    {
        if (! $this->expired_at) {
            return false;
        }
        $now = new \DateTime;

        return $now >= $this->expired_at;
    }
}
