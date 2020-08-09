<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use think\App;
use think\Controller;
use think\Request;
use app\common\validate\TaskOrderValidate;
use app\common\model\TaskOrder as TaskOrderModel;
class TaskOrder extends BaseController
{

    // 创建任务订单
    public function  createTaskOrder(){
        (new TaskOrderValidate())->goCheck('createOrder');
        (new TaskOrderModel())->createTaskOrder();
        return self::showResCodeWithOutData('报名成功');
    }
    // 上传提交任务截图接口
    public function uploadTaskOrderPic()
    {
        (new TaskOrderValidate())->goCheck('uploadTaskOrderPic');
        $pic = request()->file('pic');
        $info = $pic->validate(['size' => 2097152, 'ext' => 'jpg,png,gif'])->move('../public/static/TaskOrderPic');
        if ($info == false) TApiException('图片上传失败', 20009, 200);
        $getSaveName = str_replace("\\", "/", $info->getSaveName());
        return self::showResCode('上传成功','/static/TaskOrderPic/'.$getSaveName);
    }
    // 查询我的任务订单
    public function myTaskOrder(){
        $data=(new TaskOrderModel())->myTaskOrder();
        return self::showResCode('获取成功',$data);
    }
    // 查询指定任务订单信息
    public function getTaskOrderInfo(){
        (new TaskOrderValidate())->goCheck('getTaskOrderInfo');
        $data=(new TaskOrderModel())->getTaskOrderInfo();
        return self::showResCode('获取成功',$data);
    }

    // 提交任务订单提交内容并审核
    public function placeOrder()
    {
        (new TaskOrderValidate())->goCheck('placeOrder');
        $data=(new TaskOrderModel())->placeOrder();
        return self::showResCodeWithOutData('提交成功');
    }

    // 查询我发布的任务的任务订单
    public function myPushTaskOrder(){
        (new TaskOrderValidate())->goCheck('myPushTaskOrder');
        $data= (new TaskOrderModel())->myPushTaskOrder();
        return self::showResCode('获取成功',$data);
    }

    // 操作订单状态
    public function changeOrderStatus(){
        (new TaskOrderValidate())->goCheck('changeOrderStatus');
        (new TaskOrderModel())->changeOrderStatus();
        return self::showResCodeWithOutData('操作成功');
    }

    // 查询订单提交的图片
    public function selectOrderPic(){
        (new TaskOrderValidate())->goCheck('selectOrderPic');
        $data=(new TaskOrderModel())->selectOrderPic();
        return self::showResCode('获取成功',$data);
}
// 后台获取订单
    public function getATaskOrderList(){
        $data=(new TaskOrderModel())->getATaskOrderList();
        return self::showResCode('获取成功',$data);
    }
    // 后台  获取该订单详细信息
    public function getTaskOrderDetail(){
        $data=(new TaskOrderModel())->getTaskOrderDetail();
        return self::showResCode('获取成功',$data);

    }
}
