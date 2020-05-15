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
        'cKey'=>'require|isCKey'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [];

    protected $scene=[
      'cKey'=>'cKey'
    ];
}
