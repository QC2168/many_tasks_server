<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use app\common\validate\PrivilegedGoodsValidate;
use think\Controller;
use think\Request;
use app\common\model\PrivilegedGoods as PrivilegedGoodsModel;
class PrivilegedGoods extends BaseController
{
    // 获取vip商品
    public function getPrivilegedGoods(){
        $list=(new PrivilegedGoodsModel())->getPrivilegedGoods();
        return self::showResCode('获取成功',$list);
    }

    // 购买会员商品
    public function buyPrivilegedGoods(){
        (new PrivilegedGoodsValidate())->goCheck('buyGoods');
        (new PrivilegedGoodsModel())->buyPrivilegedGoods();
        return self::showResCodeWithOutData('购买成功');
    }

    // 获取服务费
    public function getServePrice(){
        (new PrivilegedGoodsValidate())->goCheck('getServePrice');
        $price=(new PrivilegedGoodsModel())->getServePrice();
        return self::showResCode('获取成功',$price);
    }

    // 修改商品数据
    public function changePrivilegedGood(){
        (new PrivilegedGoodsValidate())->goCheck('changePrivilegedGood');
        $price=(new PrivilegedGoodsModel())->changePrivilegedGood();
        return self::showResCodeWithOutData('修改生效');
    }

}
