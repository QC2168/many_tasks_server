<?php

namespace app\common\model;

use think\Db;
use think\Model;

class PrivilegedGoods extends Model
{
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    //获取全部VIP
public function getPrivilegedGoods(){
    return $this->where('status',1)->hidden(['id','status','push_reward_task'])->select();
}
//修改商品数据
public function changePrivilegedGood(){
    $param=request()->param();
    $this->save([
        'out'=>$param['out'],
        'push_task'=>$param['push_task'],
        'push_dy_task'=>$param['push_dy_task'],
        'term'=>$param['term'],
        'fans'=>$param['fans'],
        'price'=>$param['price'],
    ],[
        'level'=>$param['level']
    ]);
    return true;
}
// 购买VIP
public function buyPrivilegedGoods(){
    Db::transaction(function () {
 // 获取购买的会员类型
 $buy_menber_type=request()->param('type');
// 获取这个商品的价格
    $goodsPrice=$this->where('type',$buy_menber_type)->value('price');
    // 获取这个商品的购买期限
    $goodsTerm=$this->where('type',$buy_menber_type)->value('term');
    // get user of fans
        $User=new User();
        $fans_count= $User->where('f_username',request()->username)->count();
        $goods_fans=$this->where('type',$buy_menber_type)->value('fans');
        if($fans_count<$goods_fans)TApiException('粉丝不够',20014,200);
    // 获取用户余额 判断是否符合
    $assets=new Assets();
    $userWallet=$assets->where('username',request()->username)->value('wallet');
    if($goodsPrice>$userWallet)TApiException('余额不足',20012,200);
    // 扣除
    $userWallet=$assets->where('username',request()->username)->setDec('wallet',$goodsPrice);
        add_wallet_details(2,$goodsPrice,"购买特权");
    // 添加会员信息
    $privilege=new Privilege();
    $expire_time= strtotime("+{$goodsTerm}day");
    $privilege->save(['vip'=>$buy_menber_type,
        'expire_time'=>$expire_time],['username'=>request()->username]);
        // 给上级奖励
        // 查找上级
        $f_username=$User->where(['username'=>request()->username])->value('f_username');
        if(empty($f_username)){
            // 如果没有上级就不用奖励了
            return;
        }
        $serve_price=TeamReward::where('type','open_vip_one')->value('value');
        $assets->where(['username'=>$f_username])->setInc('wallet',($goodsPrice*$serve_price));
//        // 获取上级的上级
//        $f_username_f_username=$User->where(['username'=>$f_username])->value('f_username');
//        if(empty($f_username_f_username)){
//            // 如果没有上级就不用奖励了
//            return;
//        }
//        $serve_price=TeamReward::where('type','open_vip_two')->value('value');
//        $assets->where(['username'=>$f_username_f_username])->setInc('wallet',$goodsPrice*$serve_price);
    });


    return true;
}

public function getServePrice(){
    $type=request()->param('name');
    return get_serve_price(request()->username,$type);
}

}
