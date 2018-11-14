<?php

namespace Hanoivip\Activity\Services;

use Hanoivip\Events\Game\UserRecharge;
use Illuminate\Support\Facades\DB;

class FirstTopupService extends FirstRechargeService
{
    public function handle(UserRecharge $event)
    {
        if ($this->targetGamePlatform())
            return;
        $activity = $this->activityData->getActive(self::TYPE_NAME);
        // Check exists
        $record = DB::table($this->getTableName())
        ->where('activity_id', $activity['id'])
        ->where('user_id', $event->uid)
        ->first();
        if ($record->isEmpty())
        {
            $record = $this->getRecord();
            $record->user_id = $event->uid;
            $record->activity_id = $activity['id'];
            $record->current_recharge = $event->coin;
            $record->save();
        }
    }

}