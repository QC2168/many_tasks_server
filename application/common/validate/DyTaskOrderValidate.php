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
        'status'=>'between:1,5'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [];
    protected $scene=[
      'createDyOrder'=>['dy_task_id','check_pic'],
        'changeDyOrderStatus'=>['orderSn','status'],
        'myPushDyTaskOrder'=>['dy_task_id_select'],
    ];
}
