<?php

namespace Hanoivip\Activity\Services;

use Hanoivip\Activity\Models\Activity;
use Hanoivip\Platform\PlatformHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

abstract class AbstractActivityService implements IActivityLogic
{
    /**
     * Group
     * @var string
     */
    protected $group;
    /**
     * 
     * @var PlatformHelper
     */
    protected $helper;

    protected $activityCfg;
    
    public function __construct($group, $helper, $cfg)
    {
        $this->group = $group;
        $this->helper = $helper;
        $this->activityCfg = $cfg;
    }
    /**
     * 
     * @param number $uid
     * @param number $activityId
     * @param string $role
     * @return \Hanoivip\Activity\Models\Activity
     */
    protected function newRecord($uid, $activityId, $role = null)
    {
        $table = $this->getTableName();
        $record = new Activity();
        $record->setTable($table);
        $record->user_id = intval($uid);
        $record->activity_id = intval($activityId);
        $record->current_recharge = 0;
        $record->rewards = strval("[]");
        $record->data = strval("");
        if (!empty($role))
            $record->role_id = intval($role);
        $record->save();
        return $record;
    }
    /**
     * Lấy bản ghi của người chơi, đối với 1 sk nhất định, với 1 nhóm nhất định
     * 
     * @param number $uid
     * @param number $activityId
     * @param string $role
     * @return \stdClass|NULL An instance of Activity => stdClass
     */
    protected function getRecord1($uid, $activityId, $role = null)
    {
        $table = $this->getTableName();
        Log::debug("{$uid} {$activityId} {$table} {$role}");
        if (empty($role))
            $record = DB::table($table)
            ->where('user_id', $uid)
            ->where('activity_id', $activityId)
            ->first();
        else
            $record = DB::table($table)
            ->where('user_id', $uid)
            ->where('activity_id', $activityId)
            ->where('role_id', $role)
            ->first();
        return $record;
    }
    /**
     * Lấy bản ghi của người chơi, đối với 1 sk nhất định, với 1 nhóm nhất định
     *
     * @param number $uid
     * @param number $activityId
     * @param string $role
     * @return Activity|NULL
     */ 
    protected function getRecord($uid, $activityId, $role = null)
    {
        $table = $this->getTableName();
        $record = new Activity();
        $record->setTable($table);
        $builder = $record->newQuery();
        if (empty($role))
        $record = $builder
        ->where('user_id', $uid)
        ->where('activity_id', $activityId)
        ->first();
        else
        $record = $builder
        ->where('user_id', $uid)
        ->where('activity_id', $activityId)
        ->where('role_id', $role)
        ->first();
        //Log::debug(print_r($record, true));
        //??
        if (!empty($record))
            $record->setTable($table);
        return $record;
    }
    
    protected function getTableName()
    {
        return config('activity.' . $this->group . '.table');
    }
    
    protected abstract function getType();
    
    protected function getActive()
    {
        return $this->activityCfg;
    }
    
    /**
     * Lấy ID hoạt động đang được kích hoạt tương ứng
     * @return number
     */
    protected function getActiveId()
    {
        $activity = $this->getActive();
        return $activity['id'];
    }
    
    protected function getCurrentRecharge($uid, $activityId, $role = null)
    {
        $record = $this->getRecord($uid, $activityId, $role);
        if (empty($record))
            return 0;
        else
            return $record->current_recharge;
    }
    /**
     * Lấy tất cả các nhân vật đã tham gia vào sự kiện này
     * @param number $uid
     * @return number[] Role Uids. Trả về array(stdOject(0)) nếu không phân biệt nv
     */
    protected function getRoles($uid)
    {
        $table = $this->getTableName();
        $roles = DB::table($table)
        ->select('role_id')
        ->where('user_id', $uid)
        ->where('activity_id', $this->getActiveId())
        ->distinct()
        ->get()
        ->toArray();
        //Log::debug("......" . print_r($roles, true));
        return $roles;
    }
    
    public function onGetReward($uid, $index, $role)
    {
        $record = $this->getRecord($uid, $this->getActiveId(), $role);
        $rewards = [];
        if (!empty($record))
            $rewards = json_decode($record->rewards, true);
        $rewards[$index] = 1;
        $record->rewards = json_encode($rewards);
        $record->save();
        return;
    }
    
    // Init record for each roles
    public function getUserProgress($uid)
    {
        $roles = $this->getRoles($uid);
        if (empty($roles))
        {
            $this->newRecord($uid, $this->getActive(), 0);
        }
        else
            foreach ($roles as $r)
            {
                $role = $r->role_id;
                $record = $this->getRecord($uid, $this->getActiveId(), $role);
                if (empty($record))
                    $this->newRecord($uid, $this->getActiveId(), $role);
            }
        return $this->onGetUserProgress($uid);
    }
    
    /**
     *
     * @param number $uid
     * @return array index => array ( role => RewardIndex )
     */
    public abstract function onGetUserProgress($uid);
    
    public function canUserGet($uid, $indexOrAmount, $role = null)
    {
        $record = $this->getRecord($uid, $this->getActiveId(), $role);
        if (empty($record))
            return false;
        $rewards = json_decode($record->rewards, true);
        if (!empty($rewards))
        {
            return isset($rewards[$indexOrAmount]);
        }
        return false;
    }
    
    public function hasGotReward($uid, $indexOrAmount, $role = null)
    {
        $record = $this->getRecord($uid, $this->getActiveId(), $role);
        if (empty($record))
            return false;
        $rewards = json_decode($record->rewards, true);
        if (!empty($rewards))
        {
            return isset($rewards[$indexOrAmount]);
        }
        return false;
    }
}