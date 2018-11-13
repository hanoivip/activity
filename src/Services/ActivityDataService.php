<?php

namespace Hanoivip\Activity\Services;
/**
 * Config activity by php arrays
 * @author hanoivip
 *
 */
class ActivityDataService
{
    public function activeGroup()
    {
        $groups = config('activities');
        $active = [];
        foreach ($groups as $group => $activities)
        {
            $platformName = config('activity.' . $group . '.platform');
            $platform = $this->platformHelper->getPlatform($platformName);
            if (!empty($platform))
                $active[] = $group;
        }
        return $active;
    }
    
    public function getConfig($group, $type = null)
    {
        $groups = config('activities');
        if (!isset($groups[$group]))
            return;
        $cfg = $groups[$group];
        if (!empty($type))
        {
            if (!isset($cfg[$type]))
                return;
            $cfg = $cfg[$type];
        }
        return $cfg;
    }
}