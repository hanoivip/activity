<?php

use Illuminate\Support\Facades\Route;
// User Domain
Route::middleware(['web', 'auth:web'])->namespace('Hanoivip\Activity\Controllers')->prefix('user')->group(function () {
    Route::get('/activity', function () {
        return redirect()->route('activity.group');
    });
    // Liệt kê tất cả các group đang được kích hoạt hiện tại
    Route::get('/activity/group', 'ActivityController@listGroup')->name('activity.group');
    // Liệt kê/cập nhật các hoạt động của người chơi đối với 1 group cụ thể
    Route::get('/activity/detail', 'ActivityController@detailGroup')->name('activity.detail');
    // Nhận phần thưởng đối với các hoạt động cụ thể
    Route::post('/activity/reward', 'ActivityController@getReward')->name('activity.reward');
    // Nhận cấu hình các hoạt động đối với 1 group cụ thể
    Route::get('/activity/info', 'ActivityController@getInfo')->name('activity.config');
});
// Admin Domain
Route::middleware(['web', 'admin'])->namespace('Hanoivip\Activity\Controllers')->prefix('ecmin')->group(function () {
    Route::get('/activity', function () {
        return redirect()->route('activity.cfg.list.ui');
    });
    Route::get('/activity/new', 'ActivityController@newUI')->name('activity.cfg.new.ui');
    Route::post('/activity/new/do', 'ActivityController@new');
    Route::post('/activity/delete', 'ActivityController@delete');
    Route::get('/activity/group/new', 'ActivityController@newGroupUI');
    Route::post('/activity/group/new/do', 'ActivityController@newGroup');
    Route::get('/activity/group/update', 'ActivityController@newGroupUI');
    Route::post('/activity/group/update/do', 'ActivityController@updateGroup');
    Route::post('/activity/group/delete', 'ActivityController@deleteGroup');
});