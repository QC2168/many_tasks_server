<?php

namespace app\common\validate;

use think\Validate;

class RechargeKeyValidate extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'cKey'=>'require|length:32|isCKey',
        'create_number'=>'require|number|between:1,100',
        'create_money'=>'require|number'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'create_number.between'=>'创建数量不符合内置要求'
    ];

    protected $scene=[
      'cKey'=>'cKey',
      'create_cKey'=>['create_number','create_money'],
    ];
}
