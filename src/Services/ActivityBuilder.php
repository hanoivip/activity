<?php

namespace Hanoivip\Activity\Services;

use Illuminate\Support\Facades\Log;

class ActivityBuilder
{   
    /**
     *
     * @param string $type
     * @return IActivityLogic
     */
    public function getServiceByType($type, $group)
    {
        switch ($type)
        {
            case 'first_recharge':
                return new FirstRechargeService($group);
            case 'recharge':
                return new AccumulateRechargeService($group);
            default:
                Log::error("ActivityManager type {$type} not supported!");
        }
    }
}