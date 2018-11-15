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
        $role = isset($event->params['roleid']) ? $event->params['roleid'] : 0;
        $record = $this->getRecord($event->uid, $this->getActiveId(), $role);
        if ($record->isEmpty())
        {
            $record = $this->newRecord($event->uid, $this->getActiveId(), $role);
            $record->current_recharge = $event->coin;
            $record->save();
        }
    }
    
    public function canUserGet($uid, $index, $role = null)
    {
        $record = $this->getRecord($uid, $this->getActiveId(), $role);
        if ($record->isNotEmpty())
        {
            $rewards = json_decode($record->rewards, true);
            return empty($rewards);
        }
        return false;
    }

    public function canUserBuy($uid, $index, $role = null)
    {
        return false;
    }

    public function onUserProgress($uid, $amount, $role = null)
    {}

    // TODO: move to abstract
    public function getUserProgress($uid, $role = null)
    {
        $index = new RewardIndex();
        $index->amountOrIndex = 0;
        $index->canBuy = false;
        $index->canReceived = $this->canUserGet($uid, 0);
        $index->price = 0;
        $index->received = $this->hasGotReward($uid, 0);
        $progress = [];
        if (empty($role))
        {
            if (!isset($progress[0]))
                $progress[0] = [];
                $progress[0][0] = $index;
        }
        else
        {
            if (!isset($progress[$role]))
                $progress[$role] = [];
                $progress[$role][0] = $index;
        }
        return $progress;
    }
    
    public function hasGotReward($uid, $index, $role = null)
    {
        $record = $this->getRecord($uid, $this->getActiveId(), $role);
        if ($record->isNotEmpty())
        {
            $rewards = json_decode($record->rewards, true);
            return !empty($rewards);
        }
        return false;
    }
    
    protected function getType()
    {
        return self::TYPE_NAME;
    }



}