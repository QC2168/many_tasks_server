<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use app\common\validate\OutOrderListValidate;
use think\Controller;
use app\common\model\OutOrderList as OutOrderListModel;
use think\Request;
class OutOrderList extends BaseController
{
   public function postOutOrder(){
       (new OutOrderListValidate())->goCheck('out');
       $orderSn=(new OutOrderListModel())->postOutOrder();
       return self::showResCode('提交提现成功',$orderSn);
   }

    public function getOutOrder(){
        $orderList=(new OutOrderListModel())->getOutOrder();
        return self::showResCode('获取成功',$orderList);
    }
}
