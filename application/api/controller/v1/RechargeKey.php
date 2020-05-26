<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use app\common\validate\RechargeKeyValidate;
use app\common\model\RechargeKey as RechargeKeyModel;
use think\Controller;
use think\Request;

class RechargeKey extends BaseController
{
    // 充值
    public function recharge(){
        (new RechargeKeyValidate())->goCheck('cKey');
        (new RechargeKeyModel())->recharge();
        return self::showResCodeWithOutData('充值成功');
    }
    // 创建卡密
    public function createRechargeKey(){
        (new RechargeKeyValidate())->goCheck('create_cKey');
        $data=(new RechargeKeyModel())->createRechargeKey();
        return self::showResCode('创建成功',$data);
    }
    // 获取卡密
    public function getARechargeKey(){
        $data=(new RechargeKeyModel())->getARechargeKey();
        return self::showResCode('获取成功',$data);
    }
}
