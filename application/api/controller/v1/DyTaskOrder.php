<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use app\common\validate\DyTaskListValidate;
use app\common\validate\DyTaskOrderValidate;
use app\common\model\DyTaskOrder as DyTaskOrderModel;
use think\Controller;
use think\Request;
class DyTaskOrder extends BaseController
{
    // 创建dy任务订单
   public function  createDyTaskOrder(){
    (new DyTaskOrderValidate())->goCheck('createDyOrder');
    (new DyTaskOrderModel())->createDyTaskOrder();
    return self::showResCodeWithOutData('提交成功');
}
    // 查询我的任务订单
   public function myDyTaskOrder(){
       $data=(new DyTaskOrderModel())->myDyTaskOrder();
        return self::showResCode('获取成功',$data);
   }

   // 操作订单状态
    public function changeDyOrderStatus(){
        (new DyTaskOrderValidate())->goCheck('changeDyOrderStatus');
        (new DyTaskOrderModel())->changeDyOrderStatus();
        return self::showResCodeWithOutData('操作成功');
    }


    // 查询我发布的任务的任务订单
    public function myPushDyTaskOrder(){
        (new DyTaskOrderValidate())->goCheck('myPushDyTaskOrder');
       $data= (new DyTaskOrderModel())->myPushDyTaskOrder();
       return self::showResCode('获取成功',$data);
    }
    // 后台获取订单
    public function getADyTaskOrderList(){
        $data=(new DyTaskOrderModel())->getADyTaskOrderList();
        return self::showResCode('获取成功',$data);
    }
}
