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
    public function getServiceByType($type, $group, $helper, $cfg)
    {
        switch ($type)
        {
            case 'first_recharge':
                return new FirstRechargeService($group, $helper, $cfg);
            case 'recharge':
                return new AccumulateRechargeService($group, $helper, $cfg);
            case 'sale_recharge':
                return new SaleService($group, $helper, $cfg);
            case 'login':
                return new LoginService($group, $helper, $cfg);
            default:
                Log::error("ActivityManager type {$type} not supported!");
        }
    }
}