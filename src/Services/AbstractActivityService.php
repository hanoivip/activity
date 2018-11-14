<?php

namespace Hanoivip\Activity\Services;

use Hanoivip\Activity\Models\Activity;
use Hanoivip\Platform\PlatformHelper;

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
    
    /**
     * 
     * @return \Hanoivip\Activity\Models\Activity
     */
    protected function getRecord()
    {
        $table = config('activity.' . $this->platform . '.platform');
        $record = new Activity();
        $record->setTable($table);
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
}