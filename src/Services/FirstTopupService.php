<?php

namespace Hanoivip\Activity\Services;

use Hanoivip\Events\Gate\UserTopup;

class FirstTopupService extends FirstRechargeService
{
    public function handle(UserTopup $event)
    {
        if ($this->targetWebPlatform())
            return;
        $role = isset($event->params['roleid']) ? $event->params['roleid'] : 0;
        $record = $this->getRecord($event->uid, $this->getActiveId(), $role);
        if (empty($record))
        {
            $record = $this->newRecord($event->uid, $this->getActiveId(), $role);
            $record->current_recharge = $event->coin;
            $record->save();
        }
    }
}