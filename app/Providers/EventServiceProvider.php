<?php

namespace Cemal\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Cemal\Events\SomeEvent' => [
            'Cemal\Listeners\EventListener',
        ],
    ];
}
