<?php

namespace app\common\validate;

use think\Validate;

class PrivilegedGoodsValidate extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'type'=>'require|number|between:1,3',
	    'name'=>'require|typeName'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [];
    protected $scene=[
        'buyGoods'=>'type',
        'getServePrice'=>'name',
    ];

}
