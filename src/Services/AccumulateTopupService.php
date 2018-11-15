<?php

namespace Hanoivip\Activity\Services;

use Hanoivip\Events\Game\UserRecharge;

class AccumulateRechargeService extends AbstractActivityService
{
    public function canUserGet($uid, $index, $role = null)
    {}

    public function canUserBuy($uid, $index, $role = null)
    {}

    public function hasGotReward($uid, $index, $role = null)
    {}

    protected function getType()
    {}

    public function onUserProgress($uid, $amount, $role = null)
    {}

    public function getUserProgress($uid, $role = null)
    {}


}