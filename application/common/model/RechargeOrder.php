<?php

namespace app\common\model;

use think\Model;

class RechargeOrder extends Model
{
    protected $autoWriteTimestamp = true;

    // 提现
    public function postRechargeOrder()
    {
        // 提现数量
        $amount = request()->param('amount');
        if($amount<=0)  TApiException('充值失败', 20008, 200);
        Assets::where('username', request()->username)->setDec('wallet', $amount);
        //生成订单号
        $orderSn = create_OrderSn();
        //创建提现记录
        $this->create([
            'username' => request()->username,
            'type' => 2,
            'status' => 0,
            'orderSn' => $orderSn,
            'amount' => $amount
        ]);
        return $orderSn;
    }

    // 获取提现订单
    public function getOutOrder(){
        return $this->where('username',request()->username)->select();
    }
}
