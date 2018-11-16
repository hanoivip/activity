<?php

namespace Hanoivip\Activity\Services;

use Hanoivip\Events\Gate\UserTopup;

class AccumulateTopupService extends AccumulateRechargeService
{
    public function handle(UserTopup $event)
    {
        if ($this->targetGamePlatform())
            return;
        $this->onUserProgress($event->uid, $event->coin,
            isset($event->params['roleid']) ? $event->params['roleid'] : null);
    }
}