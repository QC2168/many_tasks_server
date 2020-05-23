<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use app\common\model\DyTaskList as DyTaskListModel;
use app\common\validate\DyTaskListValidate;
use think\Controller;
use think\Request;

class DyTaskList extends BaseController
{
    public function getDyTaskList(){
        $data=(new DyTaskListModel())->getDyTaskList();
        return self::showResCode('获取成功',$data);
    }
    public function getADyTaskList(){
        $data=(new DyTaskListModel())->getADyTaskList();
        return self::showResCode('获取成功',$data);
    }

    public function getDyTaskDetail(){
        (new DyTaskListValidate())->goCheck('getDyTaskDetail');
        $list=(new DyTaskListModel())->getDyTaskDetail();
        return self::showResCode('获取成功',$list);
    }

    public function pushDyTask(){
        (new DyTaskListValidate())->goCheck('pushDyTask');
        (new DyTaskListModel())->pushDyTask();
        return self::showResCodeWithOutData('发布成功');
    }

    public function deleteDyTask(){
        (new DyTaskListValidate())->goCheck('deleteDyTask');
        (new DyTaskListModel())->deleteDyTask();
        return self::showResCodeWithOutData('删除成功');
    }

    public function myPushDyTask(){
        $list=(new DyTaskListModel())->myPushDyTask();
        return self::showResCode('获取成功',$list);
    }


}
