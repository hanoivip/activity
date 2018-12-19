<?php

namespace Hanoivip\Activity\Services;

use Illuminate\Support\Facades\Log;

class SaleService extends AccumulateRechargeService
{
    public function canUserGet($uid, $index, $role = null)
    {
        $record = $this->getRecord($uid, $this->getActiveId(), $role);
        if (!empty($record))
        {
            $data = json_decode($record->data, true);
            if (isset($data[$index]) && 
                $data[$index] > 0)
            {
                return true;
            }
        }
        return false;
    }
    
    public function onGetReward($uid, $index, $role)
    {
        $record = $this->getRecord($uid, $this->getActiveId(), $role);
        $data = json_decode($record->data, true);
        //$rewards = json_decode($record->rewards, true);
        if (!isset($data[$index]))
        {
            Log::error("SaleService index {$index} not exists on rewardable list");
        }
        else
        {
            $data[$index]--;
            //if (isset($rewards[$index]))
            //    $rewards[$index]++;
            //else
            //    $rewards[$index] = 1;
            //$record->rewards = json_encode($rewards);
            $record->data = json_encode($data);
            $record->save();
        }
        return;
    }

    protected function getType()
    {
        return "sale_recharge";
    }

    public function onUserProgress($uid, $amount, $role = null)
    {
        Log::debug("Sale service onUserProgress {$uid} {$amount}");
        $config = $this->getActive();
        if (isset($config['params'][$amount]))
        {
            // Một trong các mốc được phép
            $record = $this->getRecord($uid, $this->getActiveId(), $role);
            if (empty($record))
                $record = $this->newRecord($uid, $this->getActiveId(), $role);
            $data = json_decode($record->data, true);
            if (isset($data[$amount]))
                $data[$amount]++;
            else
                $data[$amount] = 1;
            $record->data = json_encode($data);
            $record->save();
        }
        else
            Log::error("Sale Activity amount {$amount} is not in value-list");
    }

}