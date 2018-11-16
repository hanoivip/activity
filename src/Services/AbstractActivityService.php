<?php

namespace Hanoivip\Activity\Services;

use Hanoivip\Activity\Models\Activity;
use Hanoivip\Platform\PlatformHelper;
use Illuminate\Support\Facades\DB;

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
        if (!empty($role))
            $record->role_id = $role;
        $record->save();
        return $record;
    }
    /**
     * 
     * @param number $uid
     * @param number $activityId
     * @param string $role
     * @return Activity|NULL
     */
    protected function getRecord($uid, $activityId, $role = null)
    {
        $table = $this->getTableName();
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
    
    protected function getTableName()
    {
        return config('activity.' . $this->group . '.table');
    }
    
    /*protected function targetWebPlatform()
    {
        return strpos("web", $this->group) !== false;
    }
    
    protected function targetGamePlatform()
    {
        return strpos("game", $this->group) !== false;
    }*/
    
    protected abstract function getType();
    
    protected function getActive()
    {
        $type = $this->getType();
        return $this->activityData->getConfig($this->group, $type, true);
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
}