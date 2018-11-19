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
                $data[$index]--;
                $record->data = json_encode($data);
                $record->save();
                return true;
            }
        }
        return false;
    }

    protected function getType()
    {
        return "sale_recharge";
    }

    public function onUserProgress($uid, $amount, $role = null)
    {
        $config = $this->getActive();
        if (isset($config[$amount]))
        {
            // Một trong các mốc được phép
            $record = $this->getRecord($uid, $this->getActiveId(), $role);
            $data = [];
            if (!empty($record))
            {
                $data = json_decode($record->data, true);
                $data[$amount] = 1;
            }
            else
                $record = $this->newRecord($uid, $this->getActiveId(), $role);
            $record->data = json_encode($data);
            $record->save();
        }
        else
            Log::error("Sale Activity amount {$amount} is not in value-list");
    }

}