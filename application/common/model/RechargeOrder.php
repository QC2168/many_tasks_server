<?php

namespace app\common\model;

use think\Model;

class RechargeOrder extends Model
{
    protected $autoWriteTimestamp = true;

    // 充值
    public function postRechargeOrder()
    {
        // 充值数量
        $amount = request()->param('amount');
        if($amount<=0)  TApiException('充值失败', 20008, 200);
        Assets::where('username', request()->username)->setDec('wallet', $amount);
        //生成订单号
        $orderSn = create_OrderSn();
        //创建充值记录
        $this->create([
            'username' => request()->username,
            'type' => 2,
            'status' => 0,
            'orderSn' => $orderSn,
            'amount' => $amount
        ]);
        add_wallet_details(1,$amount,"充值");
        return $orderSn;
    }

    // 获取充值订单
    public function getRechargeOrder(){
        return $this->where('username',request()->username)->select();
    }
}
