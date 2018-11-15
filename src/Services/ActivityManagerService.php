<?php

namespace Hanoivip\Activity\Services;

use Hanoivip\Platform\Contracts\IPlatform;
use Illuminate\Contracts\Auth\Authenticatable;
use Hanoivip\Platform\PlatformHelper;
use Exception;

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
    
    public function __construct(
        string $group, 
        IPlatform $platform)
    {
        $this->group = $group;
        $this->platform = $platform;
        $this->data = app()->make('IActivityDataService');
        $this->helper = new PlatformHelper();
    }
    
    /**
     * 
     * @param Authenticatable $user
     * @param array Array of RewardIndex
     */
    public function detail($user)
    {
        $uid = $user->getAuthIdentifier();
        $activities = $this->data->getConfig($this->group);
        $detail = [];
        foreach ($activities as $activity)
        {
            $type = $activity['type'];
            $service = $this->getServiceByType($type);
            $detail[$type] = $service->getUserProgress($uid);
        }
        return $detail;
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
     * @param string $name
     * @return IActivityLogic
     */
    private function getServiceByType($name)
    {
        switch ($name)
        {
            case 'first_recharge':
                return new FirstRechargeService($this->group, $this->platform);
            case 'recharge':
                return new AccumulateRechargeService($this->group, $this->platform);
            default:
                throw new Exception("ActivityManager type {$name} not supported!");
        }
    }
}