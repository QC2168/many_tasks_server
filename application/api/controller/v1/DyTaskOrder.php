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
    // 后台  获取该订单详细信息
    public function getDyTaskOrderDetail(){
        $data=(new DyTaskOrderModel())->getDyTaskOrderDetail();
        return self::showResCode('获取成功',$data);

    }
    // 查询订单提交的图片
    public function selectOrderPic(){
        (new DyTaskOrderValidate())->goCheck('selectOrderPic');
        $data=(new DyTaskOrderModel())->selectOrderPic();
        return self::showResCode('获取成功',$data);
    }
    // 上传提交抖音任务截图接口
    public function uploadDyTaskOrderPic()
    {
        (new DyTaskOrderValidate())->goCheck('uploadDyTaskOrderPic');
        $pic = request()->file('pic');
        $info = $pic->validate(['size' => 5242880, 'ext' => 'jpg,png,gif'])->move('../public/static/DyTaskOrderPic');
        if ($info == false) TApiException('图片上传失败', 20009, 200);
        $getSaveName = str_replace("\\", "/", $info->getSaveName());
        return self::showResCode('上传成功','/static/DyTaskOrderPic/'.$getSaveName);
    }
}
