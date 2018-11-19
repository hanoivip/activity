<?php

namespace Hanoivip\Activity\Services;

class ShopService extends AbstractActivityService
{
    public function canUserGet($uid, $index, $role = null)
    {
        
    }

    public function canUserBuy($uid, $index, $role = null)
    {
        return true;
    }

    public function hasGotReward($uid, $index, $role = null)
    {}

    protected function getType()
    {
        return "shop";
    }

    public function onUserProgress($uid, $amount, $role = null)
    {

    }

    public function getUserProgress($uid)
    {}



}