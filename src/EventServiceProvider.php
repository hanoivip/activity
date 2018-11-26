<?php

namespace Hanoivip\Activity;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'Hanoivip\Events\Game\UserRecharge' => [
            'Hanoivip\Activity\Services\UserRechargeListener',
        ],
        // TODO : move to events lib
        'Hanoivip\GateClient\Events\UserTopup' => [
            'Hanoivip\Activity\Services\UserTopupListener',
        ],
        'Hanoivip\Events\UserLogin' => [
            'Hanoivip\Activity\Services\UserLoginListener',
        ],
    ];

    public function boot()
    {
        parent::boot();

        //
    }
}
