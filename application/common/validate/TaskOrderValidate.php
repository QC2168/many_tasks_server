<?php

namespace app\common\validate;

use think\Validate;

class TaskOrderValidate extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'task_id'=>'number|require|isTaskId',
        'task_id_select'=>'number|require|isTaskIdSelect',
        'check_pic'=>'file',
        'orderSn'=>'require|isTaskOrderSn',
        'status'=>'between:1,5',
        'pic'=>'file',
        'pic_list'=>'require|isArr',
        'content'=>'require'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [

    ];

    protected $scene=[
        'createOrder'=>['task_id'],
        'getTaskOrderInfo'  =>  ['orderSn'],
        'uploadTaskOrderPic'=>['pic'],
        'placeOrder'=>['orderSn','content','pic_list'],
        'myPushTaskOrder'=>['task_id_select'],
        'changeOrderStatus'=>['orderSn','status'],
        'selectOrderPic'=>['orderSn']
    ];
}
