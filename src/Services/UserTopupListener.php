<?php

namespace Hanoivip\Activity\Services;


use Hanoivip\GateClient\Events\UserTopup;

class UserTopupListener
{
    protected $data;
    
    protected $builder;
    
    protected $types = ['recharge', 'first_recharge'];
    
    public function __construct(
        IActivityDataService $data,
        ActivityBuilder $builder)
    {
        $this->data = $data;
        $this->builder = $builder;
    }
    
    public function handle(UserTopup $event)
    {
        $groups = $this->data->allWebGroup();
        foreach ($groups as $group => $activities)
        {
            foreach ($this->types as $type)
            {
                $service = $this->builder->getServiceByType($type, $group);
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
}