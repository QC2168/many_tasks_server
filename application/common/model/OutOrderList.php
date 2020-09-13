<?php

namespace app\common\model;

use think\Model;

class OutOrderList extends Model
{
    protected $autoWriteTimestamp = true;
    public function User()
    {
        return $this->hasOne('User','username', 'username');
    }
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
        // 获取当前用户提现手续费
        $servePrice=get_serve_price(request()->username,'out');

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
            'amount' => $amount,
            'final_amount' => $amount-($amount*$servePrice)
        ]);
        add_wallet_details(2,$amount,"账户提现");
        // 插入通知
        $HbAreaNoticeBar=new HbAreaNoticeBar();
        $HbAreaNoticeBar->save(['username'=>request()->username,'status'=>1,'content'=>'用户 '.substr(request()->username,0,2).'*** 申请提现'.$amount.'元']);
        return $orderSn;
    }

    // 获取提现订单
    public function getOutOrder(){
        return $this->where('username',request()->username)->select();
    }

    // 获取提现订单  后台
    public function getAOutOrder(){
        $page=request()->param('index');
        $data= $this->with('User')->visible(['user'=>['phone']])->page($page,10)->select();
        $row=$this->count();
        return ['data'=>$data,'row'=>$row];
    }

    // 修改提现订单状态  后台
    public function changeAOutOrderStatus(){
        $target=request()->param('target');
        $orderSn=request()->param('orderSn');
        // 如果订单不是0 就是被修改过的了，不能继续修改
        $currentStatus=$this->where('orderSn',$orderSn)->value('status');
        if($currentStatus!==0)TApiException('当前订单已被修改过了',20018,200);
        $this->save(['status'=>$target],['orderSn'=>$orderSn]);
        // 非提现成功  不赐予奖励
        if($target!=1)return;
        //  查该提现订单的上级
        $username=$this->where('orderSn',$orderSn)->value('username');
        $f_username=get_f_username($username);
        // 判断有没有上级
 if(empty($f_username)) return;
        // 判断这个用户的第几次提现
        $out_count=$this->where(['username'=>$username,'status'=>1])->count();
        switch ($out_count){
            case 1:
                Assets::where(['username'=>$f_username])->setInc('wallet',get_reward_value('sub_out_one'));
                add_wallet_details(1,get_reward_value('sub_out_one'),'下级'.$username.'提现奖励',$f_username);
                break;
            case 2:
                Assets::where(['username'=>$f_username])->setInc('wallet',get_reward_value('sub_out_two'));
                add_wallet_details(1,get_reward_value('sub_out_two'),'下级'.$username.'提现奖励',$f_username);
                break;
            case 3:
                Assets::where(['username'=>$f_username])->setInc('wallet',get_reward_value('sub_out_three'));
                add_wallet_details(1,get_reward_value('sub_out_three'),'下级'.$username.'提现奖励',$f_username);
                break;
            case 4:
                Assets::where(['username'=>$f_username])->setInc('wallet',get_reward_value('sub_out_four'));
                add_wallet_details(1,get_reward_value('sub_out_four'),'下级'.$username.'提现奖励',$f_username);
                break;
            case 5:
                Assets::where(['username'=>$f_username])->setInc('wallet',get_reward_value('sub_out_five'));
                add_wallet_details(1,get_reward_value('sub_out_five'),'下级'.$username.'提现奖励',$f_username);
                break;
        }
    }
}
