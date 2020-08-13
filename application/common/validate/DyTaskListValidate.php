<?php

namespace app\common\validate;

use think\Validate;

class DyTaskListValidate extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'title'=>'require|chsDash|length:4,25',
        'dy_url'=>'require|url',
        'dy_task_pic'=>'file',
        'tag'=>'require',
        'content'=>'require',
        'price'=>'require',
        'quota'=>'require|number|between:1,1000',
        'dy_task_id'=>'require|number',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [];
    protected $scene = [
        'pushDyTask'  =>  ['title','content','price','quota','dy_url','dy_task_pic'],
        'getDyTaskDetail'  =>  ['dy_task_id'],
        'deleteDyTask'  =>  ['dy_task_id'],
    ];
}
