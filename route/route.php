<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::group('api/:version/',function (){
    Route::post('register','api/:version.User/register');
    Route::post('login','api/:version.User/login');
    Route::get('get_task_list','api/:version.TaskList/getTaskList');
    Route::get('get_reward_task_list','api/:version.RewardTaskList/getRewardTaskList');
    Route::get('get_dy_task_list','api/:version.DyTaskList/getDyTaskList');
    Route::get('get_dy_task_detail','api/:version.DyTaskList/getDyTaskDetail');
    Route::get('get_task_detail','api/:version.TaskList/getTaskDetail');
    Route::get('get_reward_task_detail','api/:version.RewardTaskList/getRewardTaskDetail');
    Route::get('get_home_pic','api/:version.Info/getHomePic');
    Route::get('get_notice_bar','api/:version.Info/getNoticeBar');
});

Route::group('api/:version/',function(){
    Route::get('get_user','api/:version.User/get_user');
    Route::get('get_serve_price','api/:version.PrivilegedGoods/getServePrice');
    Route::get('get_assets','api/:version.Assets/assets');
    Route::get('my_dy_task_order','api/:version.DyTaskOrder/myDyTaskOrder');
    Route::get('my_task_order','api/:version.TaskOrder/myTaskOrder');
    Route::get('my_reward_task_order','api/:version.RewardTaskOrder/myRewardTaskOrder');
    Route::get('get_task_order_info','api/:version.TaskOrder/getTaskOrderInfo');
    Route::get('get_reward_task_order_info','api/:version.RewardTaskOrder/getRewardTaskOrderInfo');
    Route::get('my_push_dy_task','api/:version.DyTaskList/myPushDyTask');
    Route::get('my_push_task','api/:version.TaskList/myPushTask');
    Route::get('my_push_reward_task','api/:version.RewardTaskList/myPushRewardTask');
    Route::get('my_push_dy_task_order','api/:version.DyTaskOrder/myPushDyTaskOrder');
    Route::get('get_news','api/:version.News/getNews');
    Route::get('my_push_task_order','api/:version.TaskOrder/myPushTaskOrder');
    Route::get('my_push_reward_task_order','api/:version.RewardTaskOrder/myPushRewardTaskOrder');
    Route::get('get_out_order','api/:version.OutOrderList/getOutOrder');
    Route::get('get_team','api/:version.User/team');
    Route::post('push_task','api/:version.TaskList/pushTask');
    Route::post('push_reward_task','api/:version.RewardTaskList/pushRewardTask');
    Route::post('sign','api/:version.Sign/sign');
    Route::post('sign_data','api/:version.Sign/signData');
    Route::post('push_dy_task','api/:version.DyTaskList/pushDyTask');
    Route::post('delete_task','api/:version.TaskList/deleteTask');
    Route::post('delete_dy_task','api/:version.DyTaskList/deleteDyTask');
    Route::post('feedback','api/:version.User/feedback');
    Route::post('post_out_order','api/:version.OutOrderList/postOutOrder');
    Route::post('create_dy_task_order','api/:version.DyTaskOrder/createDyTaskOrder');
    Route::post('create_task_order','api/:version.TaskOrder/createTaskOrder');
    Route::post('create_reward_task_order','api/:version.RewardTaskOrder/createRewardTaskOrder');
    Route::post('change_dy_order_status','api/:version.DyTaskOrder/changeDyOrderStatus');
    Route::post('change_order_status','api/:version.TaskOrder/changeOrderStatus');
    Route::post('change_reward_order_status','api/:version.RewardTaskOrder/changeOrderStatus');
    Route::post('upload_task_detail_pic','api/:version.TaskList/uploadTaskDetailPic');
    Route::post('upload_task_order_pic','api/:version.TaskOrder/uploadTaskOrderPic');
    Route::post('upload_reward_task_order_pic','api/:version.RewardTaskOrder/uploadRewardTaskOrderPic');
    Route::post('place_order','api/:version.TaskOrder/placeOrder');
    Route::post('place_reward_order','api/:version.RewardTaskOrder/placeRewardOrder');
    Route::post('select_order_pic','api/:version.TaskOrder/selectOrderPic');
    Route::post('select_reward_order_pic','api/:version.RewardTaskOrder/selectRewardOrderPic');
    Route::post('get_privileged_goods','api/:version.PrivilegedGoods/getPrivilegedGoods');
    Route::post('buy_privileged_goods','api/:version.PrivilegedGoods/buyPrivilegedGoods');
    Route::post('recharge','api/:version.RechargeKey/recharge');
    Route::post('updateV','api/:version.Info/updateV');
    Route::post('check_version','api/:version.Info/checkVersion');
})->middleware('ApiUserAuth');