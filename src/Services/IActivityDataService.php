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
     * @return array
     */
    public function getConfig($group, $type = null, $ontime = false);
    
    public function getWebGroups($checktime = true);
    
    public function getGameGroups($checktime = true);
}