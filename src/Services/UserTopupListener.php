<?php

namespace Hanoivip\Activity\Services;

use Hanoivip\GateClient\Events\UserTopup;
use Exception;
use Hanoivip\Platform\PlatformHelper;
use Illuminate\Support\Facades\Log;

class UserTopupListener
{
    protected $data;
    
    protected $builder;
    
    protected $helper;
    
    protected $types = ['recharge', 'first_recharge', 'sale_recharge'];
    
    public function __construct(
        IActivityDataService $data,
        ActivityBuilder $builder,
        PlatformHelper $helper)
    {
        $this->data = $data;
        $this->builder = $builder;
        $this->helper = $helper;
    }
    
    public function handle(UserTopup $event)
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
            Log::error("Activity process UserTopup event error! Ex:" . $ex->getMessage());
        }
    }
}