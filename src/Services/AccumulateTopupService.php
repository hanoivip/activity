<?php

namespace Hanoivip\Activity\Services;

use Hanoivip\Events\Game\UserRecharge;

class AccumulateRechargeService extends AbstractActivityService
{
    const TYPE_NAME = 'recharge';
    
    public function handle(UserRecharge $event)
    {
        
    }
    public function canUserGet($uid, $index)
    {}

    public function canUserBuy($uid, $index)
    {}

    public function hasGotReward($uid, $index)
    {}

    public function onUserProgress($uid, $amount)
    {}

    public function getUserProgress($uid)
    {}

}