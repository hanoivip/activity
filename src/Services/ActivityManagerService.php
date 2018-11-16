<?php

namespace Hanoivip\Activity\Services;

use Hanoivip\Platform\Contracts\IPlatform;
use Illuminate\Contracts\Auth\Authenticatable;
use Hanoivip\Platform\PlatformHelper;

/**
 * Index-Rewards activities
 * 
 * @author hanoivip
 *
 */
class ActivityManagerService
{   
    /**
     * 
     * @var string
     */
    protected $group;
    /**
     * 
     * @var IPlatform
     */
    protected $platform;
    /**
     * 
     * @var IActivityDataService
     */
    protected $data;
    /**
     * 
     * @var PlatformHelper
     */
    protected $helper;
    /**
     * 
     * @var ActivityBuilder
     */
    protected $builder;
    
    public function __construct(
        string $group, 
        IPlatform $platform,
        IActivityDataService $data)
    {
        $this->group = $group;
        $this->platform = $platform;
        $this->data = $data;
        $this->helper = new PlatformHelper();
        $this->builder = new ActivityBuilder();
    }
    
    /**
     * 
     * @param Authenticatable $user
     * @param array Array of RewardIndex, Group By Role, If any
     */
    public function detail($user)
    {
        $uid = $user->getAuthIdentifier();
        $activities = $this->data->getConfig($this->group);
        $groupByRole = [];
        foreach ($activities as $activity)
        {
            $type = $activity['type'];
            $aid = $activity['id'];
            $service = $this->getServiceByType($type);
            if (empty($service))
                continue;
            $detail = $service->getUserProgress($uid);
            if (!empty($detail))
            {
                foreach ($detail as $role => $roleActivities)
                {
                    if (!isset($groupByRole[$role]))
                        $groupByRole[$role] = [];
                    if (!isset($groupByRole[$role][$type]))
                        $groupByRole[$role][$aid] = [];
                    array_push($groupByRole[$role][$aid], $roleActivities);
                }
            }
        }
        return $groupByRole;
    }
    
    /**
     * Yêu cầu nhận phần thưởng
     * 
     * Business:
     * + Nếu không thể mua
     * - Kiểm tra người dùng có thể nhận hay không
     * - Gửi quà 
     * + Nếu có thể mua
     * - Kiểm tra có đủ tiền không
     * - Gửi quà
     * 
     * @param Authenticatable $user
     * @param string $type
     * @param number $index
     * @param string $role
     * @return boolean
     */
    public function reward($user, $type, $index, $role = null)
    {
        $service = $this->getServiceByType($type);
        if (!$service->canUserGet($user->getAuthIdentifier(), $index, $role))
        {
            return false;
        }
        $cfg = $this->data->getConfig($this->group, $type);
        $rewards = $cfg['params'][$index];
        foreach ($rewards as $reward)
            $this->helper->sendReward($this->platform, $reward);
        return true;
    }
    
    /**
     * 
     * @param string $type
     * @return IActivityLogic
     */
    private function getServiceByType($type)
    {
        return $this->builder->getServiceByType($type, $this->group);
    }
}