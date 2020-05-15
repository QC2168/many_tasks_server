<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use app\common\validate\RewardTaskOrderValidate;
use app\common\model\RewardTaskOrder as RewardTaskOrderModel;
use think\Controller;
use think\Request;

class RewardTaskOrder extends BaseController
{
    // 创建任务订单
    public function  createRewardTaskOrder(){
        (new RewardTaskOrderValidate())->goCheck('createOrder');
        (new RewardTaskOrderModel())->createRewardTaskOrder();
        return self::showResCodeWithOutData('报名成功');
    }
    // 上传提交任务截图接口
    public function uploadRewardTaskOrderPic()
    {
        (new RewardTaskOrderValidate())->goCheck('uploadTaskOrderPic');
        $pic = request()->file('pic');
        $info = $pic->validate(['size' => 2097152, 'ext' => 'jpg,png,gif'])->move('../public/static/RewardTaskOrderPic');
        if ($info == false) TApiException('图片上传失败', 20009, 200);
        $getSaveName = str_replace("\\", "/", $info->getSaveName());
        return self::showResCode('上传成功','/static/RewardTaskOrderPic/'.$getSaveName);
    }
    // 查询我的任务订单
    public function myRewardTaskOrder(){
        $data=(new RewardTaskOrderModel())->myRewardTaskOrder();
        return self::showResCode('获取成功',$data);
    }
    // 查询指定任务订单信息
    public function getRewardTaskOrderInfo(){
        (new RewardTaskOrderValidate())->goCheck('getRewardTaskOrderInfo');
        $data=(new RewardTaskOrderModel())->getRewardTaskOrderInfo();
        return self::showResCode('获取成功',$data);
    }

    // 提交任务订单提交内容并审核
    public function placeRewardOrder()
    {
        (new RewardTaskOrderValidate())->goCheck('placeRewardOrder');
        $data=(new RewardTaskOrderModel())->placeRewardOrder();
        return self::showResCodeWithOutData('提交成功');
    }

    // 查询我发布的任务的任务订单
    public function myPushRewardTaskOrder(){
        (new RewardTaskOrderValidate())->goCheck('myPushTaskOrder');
        $data= (new RewardTaskOrderModel())->myPushRewardTaskOrder();
        return self::showResCode('获取成功',$data);
    }

    // 操作订单状态
    public function changeOrderStatus(){
        (new RewardTaskOrderValidate())->goCheck('changeOrderStatus');
        (new RewardTaskOrderModel())->changeOrderStatus();
        return self::showResCodeWithOutData('操作成功');
    }

    // 查询订单提交的图片
    public function selectRewardOrderPic(){
        (new RewardTaskOrderValidate())->goCheck('selectOrderPic');
        $data=(new RewardTaskOrderModel())->selectRewardOrderPic();
        return self::showResCode('获取成功',$data);
    }
}
