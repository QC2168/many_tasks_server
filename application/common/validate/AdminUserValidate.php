<?php

namespace app\common\validate;

use think\Validate;

class AdminUserValidate extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'username'=>'require|length:5,10',
        'password'=>'require|length:5,10',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [];
    protected $scene =[
        'login'=>['username','password']
    ];
}
