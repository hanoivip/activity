<?php

namespace Hanoivip\Activity\Services;

interface IActivityLogic
{
    /**
     * 
     * @param number $uid
     * @param number $amount Index or Amount
     * @param string $role
     */
    public function onUserProgress($uid, $amount, $role = null);
    /**
     * 
     * @param number $uid
     * @param string $role
     * @return array index => array ( role => RewardIndex )
     */
    public function getUserProgress($uid, $role = null);
    /**
     * 
     * @param number $uid
     * @param number $index
     */
    public function canUserGet($uid, $index, $role = null);
    /**
     * 
     * @param number $uid
     * @param number $index
     */
    public function hasGotReward($uid, $index, $role = null);
    /**
     *
     * @param number $uid
     * @param number $index
     */
    public function canUserBuy($uid, $index, $role = null);
}