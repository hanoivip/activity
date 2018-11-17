<?php

namespace Hanoivip\Activity\Controllers;

use Hanoivip\Activity\Services\ActivityManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;
use Hanoivip\Activity\Services\IActivityDataService;
use Hanoivip\Platform\PlatformHelper;
use Hanoivip\Activity\Services\ArrayDataService;

class ActivityController extends Controller
{
    protected $activityData;
    
    protected $platformHelper;
    
    public function __construct(
        IActivityDataService $data,
        PlatformHelper $helper)
    {
        $this->activityData = $data;
        $this->platformHelper = $helper;
    }
    
    /**
     * Danh sách các group kích hoạt.
     * 
     * Lọc:
     * + Danh sách các group đã có thông tin các hoạt động
     * + Danh sách các group đã có thông tin cấu hình
     * 
     * @param Request $request
     */
    public function listGroup(Request $request)
    {
        $groups = $this->activityData->activeGroup();
        if ($request->ajax())
            return ['groups' => $groups];
        else 
            return view('hanoivip::activity-group', ['groups' => $groups]);
    }
    
    private function getActivityService($group)
    {
        $platformName = config('activity.' . $group . '.platform');
        $platform = $this->platformHelper->getPlatform($platformName);
        return new ActivityManagerService($group, $platform, $this->activityData, $this->platformHelper);
    }
    
    /**
     * Chi tiết các hoạt động có trong nhóm
     * 
     * @param Request $request
     */
    public function detailGroup(Request $request)
    {
        $group = $request->input('group');
        $user = Auth::user();
        $activities = [];
        $configs = [];
        try
        {
            $service = $this->getActivityService($group);
            $configs = $this->activityData->getConfig($group);
            $activities = $service->detail($user);
        }
        catch (Exception $ex)
        {
            Log::error('Activity detail activities ex: ' . $ex->getMessage());
        }
        if ($request->ajax())
            return ['group' => $group, 'configs' => $configs, 'activities' => $activities];
        else
            return view('hanoivip::activity-group-detail', 
                ['group' => $group, 'configs' => $configs, 'activities' => $activities]);
    }
    
    /**
     * Nhận phần thưởng từ 1 hoạt động đã đạt được
     * 
     * @param Request $request
     */
    public function getReward(Request $request)
    {
        $group = $request->input('group');
        $type = $request->input('type');
        $index = $request->input('index');
        $user = Auth::user();
        $result = false;
        $error = null;
        try
        {
            $role = null;
            if ($request->has('role'))
                $role = $request->input('role');
            $service = $this->getActivityService($group);
            $result = $service->reward($user, $type, $index, $role);
        }
        catch (Exception $ex)
        {
            Log::error('Activity get rewards ex: ' . $ex->getMessage());
            $error = __('activity.reward.exception');
        }
        if ($request->ajax())
            return [ 'result' => $result, 'error' => $error];
        else
            return view('hanoivip::activity-reward', [ 'result' => $result, 'error' => $error]);
    }
    
    /**
     * Lấy thông tin các hoạt động trong 1 nhóm
     * 
     * @param Request $request
     */
    public function getInfo(Request $request)
    {
        $group = $request->input('group');
        try
        {
            $configs = $this->activityData->getConfig($group);
        }
        catch (Exception $ex)
        {
            Log::error('Activity get activity config ex: ' . $ex->getMessage());
        }
        if ($request->ajax())
            return $configs;
        else
            return view('hanoivip::activity-configs', ['configs' => $configs]);
    }
}