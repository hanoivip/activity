<?php

namespace Hanoivip\Activity\Services;

use Hanoivip\Events\Game\UserRecharge;

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
    
    public function canUserGet($uid, $indexOrAmount, $role = null)
    {
        $current = $this->getCurrentRecharge($uid, $this->getActiveId(), $role);
        if ($current < $indexOrAmount)
            return false;
        $record = $this->getRecord($uid, $this->getActiveId(), $role);
        $rewards = json_decode($record->rewards, true);
        if (!empty($rewards))
        {
            return !isset($rewards[$indexOrAmount]);
        }
        return true;
    }

    public function canUserBuy($uid, $indexOrAmount, $role = null)
    {
        return false;
    }

    public function hasGotReward($uid, $indexOrAmount, $role = null)
    {
        $record = $this->getRecord($uid, $this->getActiveId(), $role);
        $rewards = json_decode($record->rewards, true);
        if (!empty($rewards))
        {
            return isset($rewards[$indexOrAmount]);
        }
        return false;
    }

    public function onUserProgress($uid, $amount, $role = null)
    {
        $activity = $this->activityData->getConfig($this->platform, self::TYPE_NAME, true);
        $record = $this->getRecord($uid, $activity['id'], $role);
        if ($record->isEmpty())
        {
            $record = $this->newRecord($uid, $activity['id'], $role);
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
    
    

    public function getUserProgress($uid, $role = null)
    {
        $activity = $this->getActive();
        $progress = [];
        foreach ($activity['params'] as $amount => $rewards)
        {
            $index = new RewardIndex();
            $index->amountOrIndex = $amount;
            $index->canBuy = false;
            $index->price = 0;
            $index->canReceived = $this->canUserGet($uid, $amount, $role);
            $index->received = $this->hasGotReward($uid, $amount, $role);
            if (empty($role))
            {
                if (!isset($progress[0]))
                    $progress[0] = [];
                $progress[0][$amount] = $index;
            }
            else
            {
                if (!isset($progress[$role]))
                    $progress[$role] = [];
                $progress[$role][$amount] = $index;
            }
        }
        return $progress;
    }
    
    protected function getType()
    {
        return self::TYPE_NAME;
    }



}