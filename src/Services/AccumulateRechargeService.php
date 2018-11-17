<?php

namespace Hanoivip\Activity\Services;

class AccumulateRechargeService extends AbstractActivityService
{
    const TYPE_NAME = 'recharge';
    
    public function canUserGet($uid, $indexOrAmount, $role = null)
    {
        $current = $this->getCurrentRecharge($uid, $this->getActiveId(), $role);
        if ($current < $indexOrAmount)
            return false;
        $record = $this->getRecord($uid, $this->getActiveId(), $role);
        if (empty($record))
            return false;
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
        if (empty($record))
            return false;
        $rewards = json_decode($record->rewards, true);
        if (!empty($rewards))
        {
            return isset($rewards[$indexOrAmount]);
        }
        return false;
    }

    public function onUserProgress($uid, $amount, $role = null)
    {
        $record = $this->getRecord($uid, $this->getActiveId(), $role);
        if (empty($record))
        {
            $record = $this->newRecord($uid, $this->getActiveId(), $role);
            $record->current_recharge = $amount;
            $record->rewards = '[]';
            $record->save();
        }
        else
        {
            $record->current_recharge = $record->current_recharge + $amount;
            $record->update();
        }
    }

    public function getUserProgress($uid)
    {
        $activity = $this->getActive();
        $progress = [];
        $roles = $this->getRoles($uid);
        foreach ($roles as $r)
        {
            $role = $r->role_id;
            foreach ($activity['params'] as $amount => $rewards)
            {
                $index = new RewardIndex();
                $index->amountOrIndex = $amount;
                $index->canBuy = false;
                $index->price = 0;
                $index->canReceived = $this->canUserGet($uid, $amount, $role);
                $index->received = $this->hasGotReward($uid, $amount, $role);
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