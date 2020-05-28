<?php

namespace app\common\validate;

use think\Validate;

class RewardTaskOrderValidate extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'reward_task_id'=>'number|require|isRewardTaskId',
        'reward_task_id_select'=>'number|require|isRewardTaskIdSelect',
        'check_pic'=>'file',
        'orderSn'=>'require|isRewardTaskOrderSn',
        'status'=>'between:1,7',
        'pic'=>'file',
        'pic_list'=>'require|isArr',
        'content'=>'require',
        'goods_url'=>'require|alphaNum'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [];
    protected $scene=[
        'createOrder'=>['reward_task_id'],
        'getRewardTaskOrderInfo'  =>  ['orderSn'],
        'uploadTaskOrderPic'=>['pic'],
        'placeRewardOrder'=>['orderSn','goods_orderSn','content','pic_list'],
        'myPushTaskOrder'=>['task_id_select'],
        'changeOrderStatus'=>['orderSn','status'],
        'selectOrderPic'=>['orderSn']
    ];
}
