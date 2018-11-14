<?php

namespace Hanoivip\Activity\Services;

interface IActivityDataService
{
    /**
     * Lấy tất cả các nhóm (platform) đủ khả năng tổ chức sk
     */
    public function activeGroup();
    /**
     * Lấy tất cả cấu hình sự kiện hiện tại
     * @param string $group
     * @param string $type
     * @param boolean $ontime Có kiểm tra thời gian hay không
     */
    public function getConfig($group, $type = null, $ontime = false);
    
    /**
     * Lấy 01 hoạt động đầu tiên đang trong thời gian hiện tại
     * @param string $group
     * @param string $type
     */
    //public function getActive($group, $type = null);
}