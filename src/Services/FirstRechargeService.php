<?php

namespace Hanoivip\Activity\Services;

use Hanoivip\Events\Game\UserRecharge;
use Illuminate\Support\Facades\DB;

class FirstRechargeService extends AbstractActivityService
{
    const TYPE_NAME = 'first_recharge';
    
    public function handle(UserRecharge $event)
    {
        if ($this->targetWebPlatform())
            return;
        $activity = $this->activityData->getConfig($this->platform, self::TYPE_NAME);
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
    
    public function canUserGet($uid, $index)
    {
        $activity = $this->activityData->getConfig($this->platform, self::TYPE_NAME);
        $record = DB::table($this->getTableName())
        ->where('activity_id', $activity['id'])
        ->where('user_id', $uid)
        ->first();
        if ($record->isNotEmpty())
        {
            $rewards = json_decode($record->rewards, true);
            return empty($rewards);
        }
        return false;
    }

    public function canUserBuy($uid, $index)
    {
        return false;
    }

    public function onUserProgress($uid, $amount)
    {}

    // TODO: move to abstract
    public function getUserProgress($uid)
    {
        $index = new RewardIndex();
        $index->amountOrIndex = 0;
        $index->canBuy = false;
        $index->canReceived = $this->canUserGet($uid, 0);
        $index->price = 0;
        $index->received = $this->hasGotReward($uid, 0);
    }
    
    public function hasGotReward($uid, $index)
    {
        $activity = $this->activityData->getConfig($this->platform, self::TYPE_NAME);
        $record = DB::table($this->getTableName())
        ->where('activity_id', $activity['id'])
        ->where('user_id', $uid)
        ->first();
        if ($record->isNotEmpty())
        {
            $rewards = json_decode($record->rewards, true);
            return !empty($rewards);
        }
        return false;
    }


}