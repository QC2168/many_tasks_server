<?php

namespace app\common\validate;

use think\Validate;

class TaskListValidate extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'title'=>'require|chsDash|length:2,15',
	    'tag'=>'require',
	    'content'=>'require',
	    'price'=>'require',
	    'quota'=>'require|number|between:1,100',
	    'task_id'=>'require|number',
	    'task_step_list'=>'require|isArr',
        'pic'=>'file'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [];

    protected $scene = [
        'pushTask'  =>  ['title','content','price','quota','task_step_list'],
        'getTaskDetail'  =>  ['task_id'],
        'getTaskOrderInfo'  =>  ['task_id'],
        'deleteTask'  =>  ['task_id'],
        'uploadTaskDetailPic'=>['pic']
    ];
}
