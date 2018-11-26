<?php

namespace Hanoivip\Activity\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Hanoivip\Events\UserLogin;
use Hanoivip\Platform\PlatformHelper;

class UserLoginListener
{
    protected $data;
    
    protected $builder;
    
    protected $helper;
    
    protected $types = ['login'];
    
    public function __construct(
        IActivityDataService $data,
        ActivityBuilder $builder,
        PlatformHelper $helper)
    {
        $this->data = $data;
        $this->builder = $builder;
        $this->helper = $helper;
    }
    
    public function handle(UserLogin $event)
    {   
        try
        {
            $groups = $this->data->getWebGroups();
            foreach ($groups as $group => $activities)
            {
                foreach ($this->types as $type)
                {
                    $cfg = $this->data->getConfig($group, $type, true);
                    if (empty($cfg))
                    {
                        Log::debug("Activity type {$type} not active atm!");
                        continue;
                    }
                    $service = $this->builder->getServiceByType($type, $group, $this->helper, $cfg);
                    if (!empty($service))
                    {
                        $service->onUserProgress($event->uid, 0);
                    }
                }
            }
        }
        catch (Exception $ex)
        {
            Log::error("Activity process UserLogin event error! Ex:" . $ex->getMessage());
        }
    }
}