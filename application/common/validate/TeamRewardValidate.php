<?php

namespace app\common\validate;

use think\Validate;

class TeamRewardValidate extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'number'=>'number|require|between:1,5',
	    'price'=>'number|require',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [];
    protected $scene=[
      'setOutReward'=>['number','price']
    ];
}
