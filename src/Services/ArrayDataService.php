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
                    if ($ontime)
                    {
                        $now = date();
                        if ($detail['start'] > 0 && $detail['end'] > 0 &&
                            $now >= $detail['start'] && $now < $detail['end'])
                            return detail;
                    }
                    else
                        return $detail;
                }
            }
        }
        return $cfg;
    }

}