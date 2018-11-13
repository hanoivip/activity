<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'Hanoivip\Events\Game\UserRecharge' => [
            'Hanoivip\Activity\Services\AccumulateRechargeService',
            'Hanoivip\Activity\Services\FirstRechargeService',
        ],
    ];

    public function boot()
    {
        parent::boot();

        //
    }
}
