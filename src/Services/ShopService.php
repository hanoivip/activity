<?php

namespace Hanoivip\Activity\Services;

class ShopService extends AbstractActivityService
{
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