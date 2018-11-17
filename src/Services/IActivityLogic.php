<?php

namespace Hanoivip\Activity\Services;

interface IActivityLogic
{
    /**
     * Hiện tại có thể hiện nào đang hoạt động không
     * @return boolean
     */
    public function isActive();
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
    public function getUserProgress($uid);
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