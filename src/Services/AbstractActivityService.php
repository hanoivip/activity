<?php

namespace Hanoivip\Activity\Services;

use Hanoivip\Activity\Models\Activity;
use Hanoivip\Platform\PlatformHelper;
use Illuminate\Support\Facades\DB;

abstract class AbstractActivityService implements IActivityLogic
{
    /**
     * 
     * @var string
     */
    protected $platform;
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
    
    public function __construct($platform, $data)
    {
        $this->platform = $platform;
        $this->activityData = $data;
        $this->helper = new PlatformHelper();
    }
    
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
        return config('activity.' . $this->platform . '.platform');
    }
    
    protected function targetWebPlatform()
    {
        return strpos("web", $this->platform) !== false;
    }
    
    protected function targetGamePlatform()
    {
        return strpos("game", $this->platform) !== false;
    }
    
    protected abstract function getType();
    
    protected function getActive()
    {
        $type = $this->getType();
        return $this->activityData->getConfig($this->platform, $type, true);
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
        if ($record->isEmpty())
            return 0;
        else
            return $record->current_recharge;
    }
}