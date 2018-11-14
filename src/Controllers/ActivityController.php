<?php

namespace Hanoivip\Activity\Controllers;

use Hanoivip\Activity\Services\ActivityManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;
use Hanoivip\Activity\Services\IActivityDataService;
use Hanoivip\Platform\PlatformHelper;

class ActivityController extends Controller
{
    protected $activity;
    
    protected $activityData;
    
    protected $platformHelper;
    
    public function __contruct(
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
            return $groups;
        else 
            return view('hanoivip:activity-group', ['groups' => $groups]);
    }
    
    private function getActivityService($group)
    {
        $table = config('activity.' . $group . '.table');
        $platformName = config('activity.' . $group . '.platform');
        $platform = $this->platformHelper->getPlatform($platformName);
        return new ActivityManagerService($table, $platform);
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
        try
        {
            $service = $this->getActivityService($group);
            $activities = $service->detail($user);
        }
        catch (Exception $ex)
        {
            Log::error('Activity detail activities ex: ' . $ex->getMessage());
        }
        if ($request->ajax())
            return $activities;
        else
            return view('hanoivip:activities', ['activities' => $activities]);
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
        try
        {
            $service = $this->getActivityService($group);
            $result = $service->reward($user, $type, $index);
        }
        catch (Exception $ex)
        {
            Log::error('Activity get rewards ex: ' . $ex->getMessage());
        }
        if ($request->ajax())
            return $result;
        else
            return view('hanoivip:activity-reward', ['result' => $result]);
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
            return view('hanoivip:activity-configs', ['configs' => $configs]);
    }
}