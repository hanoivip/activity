<?php

namespace Hanoivip\Activity\Services;

use Hanoivip\Events\Game\UserRecharge;
use Illuminate\Support\Facades\DB;

class AccumulateTopupService extends AbstractActivityService
{
    const TYPE_NAME = 'recharge';
    
    public function handle(UserRecharge $event)
    {
        if ($this->targetWebPlatform())
            return;
        $this->onUserProgress($event->uid, $event->coin,
            isset($event->params['roleid']) ? $event->params['roleid'] : null);
    }
    
    public function canUserGet($uid, $indexOrAmount)
    {
        
    }

    public function canUserBuy($uid, $indexOrAmount)
    {
        return false;
    }

    public function hasGotReward($uid, $indexOrAmount)
    {
        
    }

    public function onUserProgress($uid, $amount, $role = null)
    {
        $activity = $this->activityData->getConfig($this->platform, self::TYPE_NAME, true);
        $table = $this->getTableName();
        if (empty($role))
            $record = DB::table($table)
            ->where('user_id', $uid)
            ->where('activity_id', $activity['id'])
            ->first();
        else
            $record = DB::table($table)
            ->where('user_id', $uid)
            ->where('activity_id', $activity['id'])
            ->where('role_id', $role)
            ->first();
        if ($record->isEmpty())
        {
            $record = $this->getRecord();
            $record->user_id = $uid;
            $record->activity_id = $activity['id'];
            if (!empty($role))
                $record->role_id = $role;
            $record->current_recharge = $amount;
            $record->rewards = '[]';
            $record->save();
        }
        else
        {
            $record->current_recharge += $amount;
            $record->save();
        }
    }
    
    private function getCurrentRecharge($uid, $role = null)
    {
        $activity = $this->activityData->getConfig($this->platform, self::TYPE_NAME, true);
        $table = $this->getTableName();
        if (empty($role))
            $record = DB::table($table)
            ->where('user_id', $uid)
            ->where('activity_id', $activity['id'])
            ->first();
        else
            $record = DB::table($table)
            ->where('user_id', $uid)
            ->where('activity_id', $activity['id'])
            ->where('role_id', $role)
            ->first();
        if ($record->isEmpty())
            return 0;
        else
            return $record->current_recharge;
    }

    public function getUserProgress($uid, $role = null)
    {
        $activity = $this->activityData->getConfig($this->platform, self::TYPE_NAME, true);
        $progress = [];
        foreach ($activity['params'] as $amount => $rewards)
        {
            $index = new RewardIndex();
            $index->amountOrIndex = $amount;
            $index->canBuy = false;
            $index->price = 0;
            $index->canReceived = $this->canUserGet($uid, $amount, $role);
            $index->received = $this->hasGotReward($uid, $amount, $role);
            $progress[$amount] = $index;
        }
        return $progress;
    }


}