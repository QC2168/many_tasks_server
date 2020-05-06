<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use app\common\validate\TaskListValidate;
use app\common\model\TaskList as TaskListModel;
use think\Controller;
use think\Request;

class TaskList extends BaseController
{
    public function getTaskList(){
        $list=(new TaskListModel())->getTaskList();
        return self::showResCode('获取成功',$list);
    }
    public function getTaskDetail(){
        (new TaskListValidate())->goCheck('getTaskDetail');
        $data=(new TaskListModel())->getTaskDetail();
        return self::showResCode('获取成功',$data);
    }
public function pushTask(){
    (new TaskListValidate())->goCheck('pushTask');
    (new TaskListModel())->pushTask();
    return self::showResCodeWithOutData('发布成功');
}
    public function deleteTask(){
        (new TaskListValidate())->goCheck('deleteTask');
        (new TaskListModel())->deleteTask();
        return self::showResCodeWithOutData('删除成功');
    }

    // 上传任务步骤图接口
    public function uploadTaskDetailPic()
    {
        (new TaskListValidate())->goCheck('uploadTaskDetailPic');
        $pic = request()->file('pic');
        $info = $pic->validate(['size' => 2097152, 'ext' => 'jpg,png,gif'])->move('../public/static/TaskPic');
        if ($info == false) TApiException('图片上传失败', 20009, 200);
        $getSaveName = str_replace("\\", "/", $info->getSaveName());
        return self::showResCode('上传成功','/static/TaskPic/'.$getSaveName);
    }

    public function myPushTask(){
        $list=(new TaskListModel())->myPushTask();
        return self::showResCode('获取成功',$list);
    }
}

