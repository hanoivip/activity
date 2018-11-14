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
     * @return array index => array of RewardIndex
     */
    public function getUserProgress($uid, $role = null);
    /**
     * 
     * @param number $uid
     * @param number $index
     */
    public function canUserGet($uid, $index);
    /**
     * 
     * @param number $uid
     * @param number $index
     */
    public function hasGotReward($uid, $index);
    /**
     *
     * @param number $uid
     * @param number $index
     */
    public function canUserBuy($uid, $index);
}