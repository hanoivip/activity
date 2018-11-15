<?php

namespace Hanoivip\Activity;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'Hanoivip\Events\Game\UserRecharge' => [
            'Hanoivip\Activity\Services\AccumulateRechargeService',
            'Hanoivip\Activity\Services\FirstRechargeService',
        ],
        'Hanoivip\Events\Gate\UserTopup' => [
            'Hanoivip\Activity\Services\AccumulateTopupService',
            'Hanoivip\Activity\Services\FirstTopupService',
        ],
    ];

    public function boot()
    {
        parent::boot();

        //
    }
}
