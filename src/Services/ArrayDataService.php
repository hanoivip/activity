<?php

namespace Hanoivip\Activity\Services;
use Hanoivip\Platform\PlatformHelper;

/**
 * Config activity by php arrays
 * @author hanoivip
 *
 */
class ArrayDataService implements IActivityDataService
{
    protected $platformHelper;
    
    public function __construct()
    {
        $this->platformHelper = new PlatformHelper();
    }
    
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
    
    public function getConfig($group, $type = null, $ontime = false)
    {
        $groups = config('activities');
        if (!isset($groups[$group]))
            return;
        $cfg = $groups[$group];
        if (!empty($type))
        {
            foreach ($cfg as $id => $detail)
            {
                if ($detail['type'] == $type)
                {
                    if ($ontime && 
                        $detail['start'] > 0 && 
                        $detail['end'] > 0)
                    {
                        $now = date();
                        if ($now >= $detail['start'] && $now < $detail['end'])
                            return detail;
                    }
                    else
                        return $detail;
                }
            }
        }
        return $cfg;
    }
    
    public function getWebGroups($checktime = true)
    {
        $groups = config('activities');
        $webs = [];
        foreach ($groups as $group => $activities)
        {
            if (strpos($group, 'web') !== false)
                $webs[$group] = $activities;
        }
        return $webs;
    }

    public function getGameGroups($checktime = true)
    {
        $groups = config('activities');
        $webs = [];
        foreach ($groups as $group => $activities)
        {
            if (strpos($group, 'game') !== false)
                $webs[$group] = $activities;
        }
        return $webs;
    }


}