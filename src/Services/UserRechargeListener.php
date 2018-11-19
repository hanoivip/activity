<?php

namespace Hanoivip\Activity\Services;

use Hanoivip\Events\Game\UserRecharge;
use Illuminate\Support\Facades\Log;
use Exception;
use Hanoivip\Platform\PlatformHelper;

class UserRechargeListener
{
    protected $data;
    
    protected $builder;
    
    protected $helper;
    
    protected $types = ['recharge', 'first_recharge'];
    
    public function __construct(
        IActivityDataService $data,
        ActivityBuilder $builder,
        PlatformHelper $helper)
    {
        $this->data = $data;
        $this->builder = $builder;
        $this->helper = $helper;
    }
    
    public function handle(UserRecharge $event)
    {
        try
        {
            $groups = $this->data->getGameGroups();
            foreach ($groups as $group => $activities)
            {
                foreach ($this->types as $type)
                {
                    $cfg = $this->data->getConfig($this->group, $type, true);
                    if (empty($cfg))
                    {
                        Log::debug("Activity type {$type} not active atm!");
                        continue;
                    }
                    $service = $this->builder->getServiceByType($type, $group, $this->helper, $cfg);
                    if (!empty($service) &&
                        $service->isActive())
                    {
                        $role = null;
                        if (isset($event->params['roleid']))
                            $role = $event->params['roleid'];
                        $service->onUserProgress($event->uid, $event->coin, $role);
                    }
                }
            }
        }
        catch (Exception $ex)
        {
            Log::error("Activity process UserRecharge event error!");
        }
    }
}