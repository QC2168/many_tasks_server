<?php

namespace app\common\model;

use think\Model;

class OutOrderList extends Model
{
    protected $autoWriteTimestamp = true;

    // 提现
    public function postOutOrder()
    {
        // 提现数量
        $amount = request()->param('amount');
        if($amount==0)  TApiException('当前余额不足提现', 20008, 200);
        // 获取余额
        $currentUserWallet = Assets::where('username', request()->username)->value('wallet');
        // 判断当前用户余额是否大于提现金额
        if ($currentUserWallet < $amount) TApiException('当前余额不足提现', 20008, 200);
        // 继续
        // 足够
        // 扣除
        Assets::where('username', request()->username)->setDec('wallet', $amount);
        //生成订单号
        $orderSn = create_OrderSn();
        //创建提现记录
        $this->create([
            'username' => request()->username,
            'type' => 1,
            'status' => 0,
            'orderSn' => $orderSn,
            'amount' => $amount
        ]);
        add_wallet_details(2,$amount,"账户提现");
        return $orderSn;
    }

    // 获取提现订单
    public function getOutOrder(){
        return $this->where('username',request()->username)->select();
    }
}
