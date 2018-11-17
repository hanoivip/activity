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
     * @var IActivityDataService
     */
    protected $activityData;
    /**
     * 
     * @var PlatformHelper
     */
    protected $helper;
    
    public function __construct($group)
    {
        $this->group = $group;
        $this->activityData = app()->make(IActivityDataService::class);
        $this->helper = new PlatformHelper();
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
        $record->user_id = $uid;
        $record->activity_id = $activityId;
        $record->current_recharge = 0;
        $record->rewards = '[]';
        if (!empty($role))
            $record->role_id = $role;
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
        $type = $this->getType();
        return $this->activityData->getConfig($this->group, $type, true);
    }
    
    public function isActive()
    {
        $activity = $this->getActive();
        return !empty($activity);
    }
    
    /**
     * Lấy ID hoạt động đang được kích hoạt tương ứng
     * @return number
     */
    protected function getActiveId()
    {
        $activity = $this->getActive();
        //Log::debug('.........' . print_r($activity, true) . '...' . $this->group . ' .. ' . $this->getType());
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
}