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
    return "http://121.42.13.36:9000/";
}
// 获取服务费用
function get_serve_price($username,$taskType){
    // 查询
    $privilege=new Privilege();
    $privilegedGoods=new PrivilegedGoods();
    $grade= $privilege->where(['username'=>$username])->value('vip');
    if($grade==0){
        switch ($taskType){
            case 'out':
                return 0.02;
            case 'push_task':
                return 1.5;
            case 'push_dy_task':
                return 0.2;
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