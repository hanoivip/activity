<?php

namespace Hanoivip\Activity\Services;

use Carbon\Carbon;

/**
 * Hoạt động dưa trên đăng nhập web (không phải game)
 * Chú ý: nếu áp dụng cho game, người chơi có thể nhận quà
 * mà không phải thực sự đăng nhập vào game
 * 
 * Sử dụng: để cho quà miễn phí, hàng ngày
 * 
 * @author hanoivip
 *
 */
class LoginService extends AbstractActivityService
{

    public function canUserBuy($uid, $index, $role = null)
    {
        return false;
    }

    protected function getType()
    {
        return "login";
    }

    public function onGetUserProgress($uid)
    {
        $activity = $this->getActive();
        $progress = [];
        $roles = $this->getRoles($uid);
        foreach ($roles as $r)
        {
            $role = $r->role_id;
            foreach ($activity['params'] as $amount => $rewards)
            {
                $index = new RewardIndex();
                $index->amountOrIndex = $amount;
                $index->canBuy = false;
                $index->price = 0;
                $index->canReceived = $this->canUserGet($uid, $amount, $role);
                $index->received = $this->hasGotReward($uid, $amount, $role);
                if (!isset($progress[$role]))
                    $progress[$role] = [];
                $progress[$role][$amount] = $index;
            }
        }
        return $progress;
    }
    
    // Web Login Activity
    public function onUserProgress($uid, $amount, $role = null)
    {
        $day = Carbon::now()->day;
        $record = $this->getRecord($uid, $this->getActiveId(), $role);
        if (empty($record))
            $record = $this->newRecord($uid, $this->getActiveId());
        $rewards = json_decode($record->rewards, true);
        if (!isset($rewards[$day]))
        {
            $rewards[$day] = 1;
            $record->rewards = json_encode($rewards);
            $record->save();
        }
    }


    public function hasGotReward($uid, $indexOrAmount, $role = null)
    {
        $record = $this->getRecord($uid, $this->getActiveId(), $role);
        if (empty($record))
            return false;
        $rewards = json_decode($record->data, true);
        if (!empty($rewards))
        {
            return isset($rewards[$indexOrAmount]);
        }
        return false;
    }
    
    public function onGetReward($uid, $index, $role)
    {
        $record = $this->getRecord($uid, $this->getActiveId(), $role);
        $rewards = json_decode($record->data, true);
        $rewards[$index] = 1;
        $record->data = json_encode($rewards);
        $record->save();
        return;
    }
}