<?php

namespace Hanoivip\Activity\Services;

interface IActivityLogic
{
    public function onUserProgress($uid, $amount);
    /**
     * 
     * @param number $uid
     * @return array index => 
     */
    public function getUserProgress($uid);
    
    public function canUserGet($uid, $index);
    
}