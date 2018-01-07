<?php

namespace Cemal\Models;

use Webpatser\Uuid\Uuid;

trait GenerateUuid {
	public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string) Uuid::generate(4);
        });
    }
}