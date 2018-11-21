<?php

namespace Hanoivip\Activity\Services;
use Hanoivip\Platform\PlatformHelper;
use Carbon\Carbon;

/**
 * Config activity by php arrays
 * @author hanoivip
 *
 */
class ArrayDataService implements IActivityDataService
{
    protected $platformHelper;
    
    public function __construct(PlatformHelper $helper)
    {
        $this->platformHelper = $helper;
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
                        $now = Carbon::now()->getTimestamp();
                        if ($now >= $detail['start'] && $now < $detail['end'])
                            return $detail;
                    }
                    else
                        return $detail;
                }
            }
        }
        else
        {
            $out = [];
            foreach ($cfg as $id => $detail)
            {
                {
                    if ($ontime &&
                        $detail['start'] > 0 &&
                        $detail['end'] > 0)
                    {
                        $now = Carbon::now()->getTimestamp();
                        if ($now >= $detail['start'] && $now < $detail['end'])
                            $out[] = $detail;
                    }
                    else
                        $out[] = $detail;
                }
            }
            return $out;
        }
    }
    
    public function getWebGroups($checktime = true)
    {
        $groups = config('activities');
        $webs = [];
        foreach ($groups as $group => $activities)
        {
            if ($group == 'web')
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
            if ($group != 'web')
                $webs[$group] = $activities;
        }
        return $webs;
    }


}