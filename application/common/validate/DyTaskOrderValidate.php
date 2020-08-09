<?php

namespace app\common\validate;

use think\Validate;

class DyTaskOrderValidate extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'dy_task_id'=>'number|require|isDyTaskId',
	    'dy_task_id_select'=>'number|require|isDyTaskIdSelect',
	    'check_pic'=>'file',
        'orderSn'=>'require|isDyTaskOrderSn',
        'pic'=>'file',
        'status'=>'between:1,5',
           'pic_list'=>'require|isArr',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [];
    protected $scene=[
      'createDyOrder'=>['dy_task_id','pic_list'],
        'uploadDyTaskOrderPic'=>['pic'],
        'changeDyOrderStatus'=>['orderSn','status'],
        'myPushDyTaskOrder'=>['dy_task_id_select'],
        'myPushDyTaskOrder'=>['dy_task_id_select'],
        'selectOrderPic'=>['orderSn']
    ];
}
