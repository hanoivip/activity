<?php

namespace Hanoivip\Activity\Services;

use Illuminate\Support\Facades\Log;

class FirstRechargeService extends AbstractActivityService
{
    const TYPE_NAME = 'first_recharge';
    
    public function canUserGet($uid, $index, $role = null)
    {
        $record = $this->getRecord($uid, $this->getActiveId(), $role);
        if (!empty($record))
        {
            //Log::debug(print_r($record, true));
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
    {
        $record = $this->getRecord($uid, $this->getActiveId(), $role);
        if (empty($record))
        {
            $record = $this->newRecord($uid, $this->getActiveId(), $role);
            $record->current_recharge = $amount;
            $record->save();
        }
    }

    public function getUserProgress($uid, $role = null)
    {
        $index = new RewardIndex();
        $index->amountOrIndex = 0;
        $index->canBuy = false;
        $index->canReceived = $this->canUserGet($uid, 0, $role);
        $index->price = 0;
        $index->received = $this->hasGotReward($uid, 0, $role);
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
        if (!empty($record))
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