<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use think\Controller;
use think\Request;
use app\common\model\RewardTaskList as RewardTaskListModel;
use app\common\validate\RewardTaskListValidate;
class RewardTaskList extends BaseController
{
    public function getRewardTaskList(){
        $list=(new RewardTaskListModel())->getRewardTaskList();
        return self::showResCode('获取成功',$list);
    }
    public function getARewardTaskList(){
        $list=(new RewardTaskListModel())->getARewardTaskList();
        return self::showResCode('获取成功',$list);
    }
    public function getRewardTaskDetail(){
        (new RewardTaskListValidate())->goCheck('getRewardTaskDetail');
        $data=(new RewardTaskListModel())->getRewardTaskDetail();
        return self::showResCode('获取成功',$data);
    }
    public function pushRewardTask(){
        (new RewardTaskListValidate())->goCheck('pushRewardTask');
        (new RewardTaskListModel())->pushRewardTask();
        return self::showResCodeWithOutData('发布成功');
    }
    // 上传任务步骤图接口
    public function uploadTaskDetailPic()
    {
        (new RewardTaskListValidate())->goCheck('uploadRewardTaskDetailPic');
        $pic = request()->file('pic');
        $info = $pic->validate(['size' => 2097152, 'ext' => 'jpg,png,gif'])->move('../public/static/TaskPic');
        if ($info == false) TApiException('图片上传失败', 20009, 200);
        $getSaveName = str_replace("\\", "/", $info->getSaveName());
        return self::showResCode('上传成功','/static/RewardTaskPic/'.$getSaveName);
    }
// 我发布的福利任务
    public function myPushRewardTask(){
        $list=(new RewardTaskListModel())->myPushRewardTask();
        return self::showResCode('获取成功',$list);
    }
}
