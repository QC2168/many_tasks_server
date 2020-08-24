<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
//异常类输出函数

use app\common\model\Privilege;
use app\common\model\PrivilegedGoods;

function TApiException($msg='异常', $errorCode=999, $code=400){
    throw new \app\lib\exception\BaseException(['code' =>$code, 'msg' =>$msg, 'errorCode' => $errorCode]);
}
function _PATH(){
    return "http://task.taskarea.top/";
}
// 添加到明细

function add_wallet_details($type,$value,$msg='未设置消息',$username=NULL){
    $WalletDetails=new \app\common\model\WalletDetails();
    $WalletDetails->create([
        'username'=>$username==NULL?request()->username:$username,
        'type'=>$type,
        'value'=>$value,
        'msg'=>$msg
    ]);
}
// 查看是不是vip
function is_vip($username){
    // 查询
    $privilege=new Privilege();
    return $privilege->where(['username'=>$username])->value('vip')?true:false;
}
// 获取服务费用
function get_serve_price($username,$taskType){
    // 查询
    $privilege=new Privilege();
    $privilegedGoods=new PrivilegedGoods();
    $grade= $privilege->where(['username'=>$username])->value('vip');
    if($grade==0){
        switch ($taskType){
            // 提现手续费  百分比  0.02 =2%
            case 'out':
                return 0.05;
                // 发布悬赏任务
            case 'push_task':
                return 2;
            // 发布抖音任务
            case 'push_dy_task':
                return 0.8;
                // 发布福利任务
            case 'push_reward_task':
                return 2.5;
        }
    }else{
        // 是会员
        return $privilegedGoods->where(['type'=>$grade])->value($taskType);
    }
}
function create_OrderSn(){
    $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
    $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
    return $orderSn;
}

function create_InvitationCode()
{
    $code = 'abcdefghijklmnopqrstuvwxyz';
    $rand = $code[rand(0, 25)]
        . strtoupper(dechex(date('m')))
        . date('d') . substr(time(), -5)
        . substr(microtime(), 2, 5)
        . sprintf('%02d', rand(0, 99));
    for (
        $a = md5($rand, true),
        $s = '0123456789abcdefghijklmnopqrstuvwxyz',
        $d = '',
        $f = 0;
        $f < 5;
        $g = ord($a[$f]),
        $d .= $s[($g ^ ord($a[$f + 8])) - $g & 0x1F],
        $f++
    ) ;
    return $d;
}

// 获取上级
function get_f_username($username){
    return \app\common\model\User::where('username',$username)->value('f_username');
}

// 获取福利对应的数据
function get_reward_value($type){
    return \app\common\model\TeamReward::where(['type'=>$type])->value('value');
}

function randBonus($bonus_total=0, $bonus_count=3, $bonus_type=1){
    $bonus_items = array(); // 将要瓜分的结果
    $bonus_balance = $bonus_total; // 每次分完之后的余额
    $bonus_avg = number_format($bonus_total/$bonus_count, 2); // 平均每个红包多少钱
    $i = 0;
    while($i<$bonus_count){
        if($i<$bonus_count-1){
            $rand = $bonus_type?(rand(1, $bonus_balance*100-1)/100):$bonus_avg; // 根据红包类型计算当前红包的金额
            $bonus_items[] = $rand;
            $bonus_balance -= $rand;
        }else{
            $bonus_items[] = $bonus_balance; // 最后一个红包直接承包最后所有的金额，保证发出的总金额正确
        }
        $i++;
    }
    return $bonus_items;
}

